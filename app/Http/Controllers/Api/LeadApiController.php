<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadHistory;
use App\Models\FollowUp;
use Illuminate\Http\Request;

class LeadApiController extends Controller
{
    // ✅ 1. GET ALL LEADS
    public function index(Request $request)
    {
        $query = Lead::query();

        if ($request->status === 'draft') {
            $query->where('discussion', 'draft');
        } elseif ($request->status === 'follow_up') {
            if ($request->type === 'call_schedule') {
                $query->where('status', 'call_schedule');
            } elseif ($request->type === 'call_back_required') {
                $query->where('status', 'call_back_required');
            } else {
                $query->whereIn('status', ['call_back_required', 'call_schedule']);
            }
        } elseif ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->where(function($q) {
                $q->where('discussion', 'add')->orWhereNull('discussion');
            });
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name', 'like', '%'.$request->search.'%')
                  ->orWhere('contact_number', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->state) $query->where('state', $request->state);
        if ($request->city)  $query->where('city', 'like', '%'.$request->city.'%');
        if ($request->date)  $query->whereDate('created_at', $request->date);

        $leads = $query->latest()->paginate(10);

        return response()->json(['status' => true, 'data' => $leads]);
    }

    // ✅ 2. SHOW
    public function show($id)
{
    $lead = Lead::with('histories')->find($id);

    if (!$lead) {
        return response()->json(['status' => false], 404);
    }

    return response()->json(['status' => true, 'data' => $lead]);
}

    // ✅ 3. STORE
   public function store(Request $request)
{
    $request->validate([
        'first_name'     => 'required',
        'last_name'      => 'required',
        'contact_number' => 'required|digits:10',
        'status'         => 'required'
    ]);

    $lead = Lead::create($request->all());

    // 🔥 Force history insert (no risky fields)
    LeadHistory::create([
        'lead_id'    => $lead->id,
        'event_type' => 'created',
        'to_status'  => $lead->status,
    ]);

    return response()->json([
        'status' => true,
        'data'   => $lead
    ]);
}
    // ✅ 4. UPDATE
    public function update(Request $request, $id)
    {
        $lead = Lead::find($id);
        if (!$lead) return response()->json(['status' => false], 404);

        $oldStatus = $lead->status;

        $lead->update($request->all());

        // ✅ History save — lead edited
        LeadHistory::create([
            'lead_id'     => $lead->id,
            'event_type'  => 'edited',
            'from_status' => $oldStatus,
            'to_status'   => $lead->status,
            'date'        => $lead->date,
            'time'        => $lead->time,
            'comment'     => $lead->comment,
            'document'    => null,
        ]);

        return response()->json(['status' => true, 'data' => $lead]);
    }

    // ✅ 5. MAIN ACTION LOGIC
    public function action(Request $request, $id)
    {
        $lead = Lead::find($id);
        if (!$lead) return response()->json(['status' => false], 404);

        $request->validate([
            'action_type' => 'required',
            'comment'     => 'required'
        ]);

        $oldStatus    = $lead->status;
        $proposalPath = null;

        switch ($request->action_type) {

            case 'call_schedule':
                $lead->status = 'call_schedule';
                $lead->date   = $request->date;
                $lead->time   = $request->time;
                break;

            case 'call_back_required':
            case 'reschedule':
                $lead->status = 'call_back_required';
                $lead->date   = $request->date;
                $lead->time   = $request->time;
                break;

            case 'lost':
                $lead->status = 'lost';
                $lead->date   = $request->date;
                $lead->time   = $request->time;
                break;

            case 'proposal_sent':
                $lead->status = 'proposal_sent';
                $lead->date   = $request->date;
                $lead->time   = $request->time;
                // ✅ Proposal file upload
                if ($request->hasFile('proposal')) {
                    $proposalPath  = $request->file('proposal')->store('proposals', 'public');
                    $lead->proposal = $proposalPath;
                }
                break;

            default:
                $lead->status = $request->action_type;
                $lead->date   = null;
                $lead->time   = null;
        }

        $lead->comment = $request->comment;
        $lead->save();

        // ✅ History save — status changed
        LeadHistory::create([
            'lead_id'     => $lead->id,
            'event_type'  => 'status_changed',
            'from_status' => $oldStatus,
            'to_status'   => $lead->status,
            'date'        => $request->date,
            'time'        => $request->time,
            'comment'     => $request->comment,
            'document'    => $proposalPath
                                ? asset('storage/' . $proposalPath)
                                : null,
        ]);

        return response()->json(['status' => true, 'data' => $lead]);
    }

    // 🔥 COMMON HANDLER
    private function handleAction($id, $action, Request $request)
    {
        $request->merge(['action_type' => $action]);
        return $this->action($request, $id);
    }

    // ✅ SEPARATE APIs — sab handleAction se jaate hain
    // isliye unme bhi history automatic save hogi
    public function callSchedule(Request $request, $id)
    {
        return $this->handleAction($id, 'call_schedule', $request);
    }

    public function callBackRequired(Request $request, $id)
    {
        return $this->handleAction($id, 'call_back_required', $request);
    }

    public function qualified(Request $request, $id)
    {
        return $this->handleAction($id, 'qualified', $request);
    }

    public function proposalSent(Request $request, $id)
    {
        return $this->handleAction($id, 'proposal_sent', $request);
    }

    public function won(Request $request, $id)
    {
        return $this->handleAction($id, 'won', $request);
    }

    public function lost(Request $request, $id)
    {
        return $this->handleAction($id, 'lost', $request);
    }

    public function draft(Request $request, $id)
    {
        $lead = Lead::find($id);
        if (!$lead) return response()->json(['status' => false], 404);

        $oldStatus        = $lead->status;
        $lead->discussion = 'draft';
        $lead->save();

        // ✅ History save
        LeadHistory::create([
            'lead_id'     => $lead->id,
            'event_type'  => 'status_changed',
            'from_status' => $oldStatus,
            'to_status'   => 'draft',
            'date'        => null,
            'time'        => null,
            'comment'     => 'Saved as draft',
            'document'    => null,
        ]);

        return response()->json(['status' => true, 'data' => $lead]);
    }

    // ✅ Sab leads ke saare follow-ups
    public function getAllFollowUps()
    {
        $followUps = FollowUp::with('lead')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return response()->json([
            'success'    => true,
            'total'      => $followUps->count(),
            'follow_ups' => $followUps,
        ]);
    }

    // ✅ Ek lead ke follow-ups dikhao
    public function getFollowUps($id)
    {
        $lead = Lead::with('followUps')->findOrFail($id);

        return response()->json([
            'success'  => true,
            'lead'     => [
                'id'             => $lead->id,
                'first_name'     => $lead->first_name,
                'last_name'      => $lead->last_name,
                'full_name'      => $lead->first_name . ' ' . $lead->last_name,
                'contact_number' => $lead->contact_number,
                'email'          => $lead->email,
                'city'           => $lead->city,
                'state'          => $lead->state,
                'country'        => $lead->country,
                'requirement'    => $lead->requirement,
                'comment'        => $lead->comment,
                'date'           => $lead->date,
                'time'           => $lead->time,
                'current_status' => $lead->status,
                'discussion'     => $lead->discussion,
                'created_at'     => $lead->created_at->format('d-m-Y'),
            ],
            'total'      => $lead->followUps->count(),
            'follow_ups' => $lead->followUps()
                                ->orderBy('created_at', 'desc')
                                ->get(),
        ]);
    }

    // ✅ Naya follow-up create karo
    public function followUp(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $validated = $request->validate([
            'comment' => 'nullable|string',
            'status'  => 'required|in:call_back_received,call_schedule,not_interested,not_responded,not_in_scope,follow_up,qualified,proposal_sent,lost,won',
            'date'    => 'nullable|date',
            'time'    => 'nullable|date_format:H:i',
        ]);

        $oldStatus = $lead->status;
        $lead->update(['status' => $validated['status']]);
        $followUp = $lead->followUps()->create($validated);

        // ✅ History save
        LeadHistory::create([
            'lead_id'     => $lead->id,
            'event_type'  => 'status_changed',
            'from_status' => $oldStatus,
            'to_status'   => $validated['status'],
            'date'        => $validated['date']    ?? null,
            'time'        => $validated['time']    ?? null,
            'comment'     => $validated['comment'] ?? null,
            'document'    => null,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Follow-up saved successfully!',
            'follow_up' => $followUp,
        ], 201);
    }

    // ✅ UPDATE MULTIPLE
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'integer|exists:leads,id',
            'status' => 'required|string',
        ]);

        Lead::whereIn('id', $request->ids)->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => 'Leads updated successfully']);
    }

    // ✅ DELETE MULTIPLE
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:leads,id',
        ]);

        Lead::whereIn('id', $request->ids)->delete();

        return response()->json(['status' => true, 'message' => 'Leads deleted successfully']);
    }

    // ✅ GET COUNTS
    public function getCounts()
    {
        return response()->json([
            'status' => true,
            'counts' => [
                'all'                => Lead::where(function($q) {
                                            $q->where('discussion', 'add')
                                              ->orWhereNull('discussion');
                                        })->count(),
                'follow_up'          => Lead::whereIn('status', ['call_back_required', 'call_schedule'])->count(),
                'call_back_required' => Lead::where('status', 'call_back_required')->count(),
                'call_schedule'      => Lead::where('status', 'call_schedule')->count(),
                'qualified'          => Lead::where('status', 'qualified')->count(),
                'proposal_sent'      => Lead::where('status', 'proposal_sent')->count(),
                'lost'               => Lead::where('status', 'lost')->count(),
                'won'                => Lead::where('status', 'won')->count(),
                'draft'              => Lead::where('discussion', 'draft')->count(),
            ]
        ]);
    }

    // ✅ SEARCH
    public function search(Request $request)
    {
        $query = Lead::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name', 'like', '%'.$request->search.'%')
                  ->orWhere('contact_number', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('state'))  $query->where('state', $request->state);
        if ($request->filled('city'))   $query->where('city', 'like', '%'.$request->city.'%');
        if ($request->filled('date'))   $query->whereDate('created_at', $request->date);

        $leads = $query->latest()->paginate(10);

        return response()->json(['status' => true, 'data' => $leads]);
    }

    // ✅ GET LEAD DETAIL
    public function getLeadDetail($id)
    {
        $lead = Lead::with(['histories'])->find($id);
        if (!$lead) return response()->json(['status' => false, 'message' => 'Not found'], 404);

        return response()->json(['status' => true, 'data' => $lead]);
    }

    // ─── Sab leads ki history ek saath ───────────────────────────
    public function allHistory(Request $request)
    {
        $query = LeadHistory::with('lead')
                            ->orderBy('created_at', 'desc');

        if ($request->filled('lead_id'))    $query->where('lead_id', $request->lead_id);
        if ($request->filled('event_type')) $query->where('event_type', $request->event_type);
        if ($request->filled('status'))     $query->where('to_status', $request->status);
        if ($request->filled('date'))       $query->whereDate('created_at', $request->date);

        $history = $query->paginate(20);

        return response()->json([
            'status'       => true,
            'history' => $history->getCollection()->map(function($item) {
                return [
                    'id'          => $item->id,
                    'lead_id'     => $item->lead_id,
                    'lead_name'   => $item->lead
                                        ? $item->lead->first_name . ' ' . $item->lead->last_name
                                        : '-',
                    'event_type'  => $item->event_type,
                    'from_status' => $item->from_status,
                    'to_status'   => $item->to_status,
                    'date'        => $item->date,
                    'time'        => $item->time,
                    'comment'     => $item->comment,
                    'document'    => $item->document,
                    'created_at'  => $item->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'total'        => $history->total(),
            'current_page' => $history->currentPage(),
            'last_page'    => $history->lastPage(),
        ]);
    }

    // ─── Ek lead ki history ──────────────────────────────────────
    public function history($id)
    {
        $lead = Lead::findOrFail($id);

        $history = $lead->histories()
     ->orderBy('created_at', 'desc')  // ✅ yahan lagao
     ->get()
                        ->map(function($item) {
                            return [
                                'id'          => $item->id,
                                'event_type'  => $item->event_type,
                                'from_status' => $item->from_status,
                                'to_status'   => $item->to_status,
                                'date'        => $item->date,
                                'time'        => $item->time,
                                'comment'     => $item->comment,
                                'document'    => $item->document,
                                'created_at'  => $item->created_at->format('Y-m-d H:i:s'),
                            ];
                        });

        return response()->json([
            'status'    => true,
            'lead_id'   => $lead->id,
            'lead_name' => $lead->first_name . ' ' . $lead->last_name,
            'history'   => $history,
        ]);
    }
}