<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadHistory;
use Illuminate\Http\Request;

class LmsController extends Controller
{
    // ─── INDEX ───────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Lead::query();

        if (!$request->status) {
            $query->where(function($q) {
                $q->where('discussion', 'add')
                  ->orWhereNull('discussion');
            });
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name', 'like', '%'.$request->search.'%')
                  ->orWhere('contact_number', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->date)  $query->whereDate('created_at', $request->date);
        if ($request->state) $query->where('state', $request->state);
        if ($request->city)  $query->where('city', 'like', '%'.$request->city.'%');

        if ($request->status) {
            if ($request->status == 'draft') {
                $query->where('discussion', 'draft');
            } elseif ($request->status == 'follow_up') {
                if ($request->type == 'call_back_required') {
                    $query->whereIn('status', ['call_back_required', 'not_responded']);
                } elseif ($request->type == 'call_schedule') {
                    $query->where('status', 'call_schedule');
                } else {
                    $query->whereIn('status', ['call_back_required', 'call_schedule', 'not_responded']);
                }
            } elseif ($request->status == 'lost') {
                $query->whereIn('status', ['lost', 'not_interested', 'not_in_scope']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $leads = $query->latest()->paginate(5);

        $counts = [
            'all'                => Lead::where(function($q) {
                                        $q->where('discussion', 'add')->orWhereNull('discussion');
                                    })->count(),
            'follow_up'          => Lead::whereIn('status', ['call_back_required', 'call_schedule', 'not_responded'])->count(),
            'qualified'          => Lead::where('status', 'qualified')->count(),
            'proposal_sent'      => Lead::where('status', 'proposal_sent')->count(),
            'lost'               => Lead::whereIn('status', ['lost', 'not_interested', 'not_in_scope'])->count(),
            'won'                => Lead::where('status', 'won')->count(),
            'call_back_required' => Lead::whereIn('status', ['call_back_required', 'not_responded'])->count(),
            'call_schedule'      => Lead::where('status', 'call_schedule')->count(),
            'draft'              => Lead::where('discussion', 'draft')->count(),
        ];

        return view('lms.lmsindex', compact('leads', 'counts'));
    }

    // ─── STORE ───────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'contact_number' => 'required|numeric|digits:10',
            'email'          => 'nullable|email|unique:leads,email',
            'state'          => 'nullable|string|max:100',
            'city'           => 'nullable|string|max:100',
            'country'        => 'nullable|string|max:100',
            'Requirement'    => 'nullable|string',
            'date'           => 'nullable|date',
            'time'           => 'nullable',
            'status'         => 'required',
            'comment'        => 'nullable|string',
        ]);

        $lead = Lead::create([
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'contact_number' => $request->contact_number,
            'email'          => $request->email,
            'state'          => $request->state,
            'city'           => $request->city,
            'country'        => $request->country,
            'Requirement'    => $request->requirement,
            'date'           => $request->date   ?? null,
            'time'           => $request->time   ?? null,
            'status'         => $request->status,
            'comment'        => $request->comment,
            'discussion'     => $request->discussion,
        ]);

        // ✅ History: lead created — user ka comment save karo, koi hardcode nahi
        LeadHistory::create([
            'lead_id'     => $lead->id,
            'event_type'  => 'created',
            'from_status' => null,
            'to_status'   => $lead->status,
            'date'        => $request->date   ?? null,
            'time'        => $request->time   ?? null,
            'comment'     => $request->comment ?? null,  // ✅ user ka comment, 'Lead added' nahi
            'document'    => null,
        ]);

        return redirect()->route('admin.lms.show', $lead->id)->with('success', 'Lead added successfully!');
    }

    // ─── SHOW ────────────────────────────────────────────────────────
    public function show(Lead $lm)
    {
        return view('lms.show', compact('lm'));
    }

    // ─── EDIT ────────────────────────────────────────────────────────
    public function edit(Lead $lm)
    {
        return view('lms.edit', compact('lm'));
    }

    // ─── UPDATE ──────────────────────────────────────────────────────
    public function update(Request $request, Lead $lm)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'contact_number' => 'required|numeric|digits:10',
            'email'          => 'nullable|email|unique:leads,email,' . $lm->id,
            'state'          => 'nullable|string|max:100',
            'city'           => 'nullable|string|max:100',
            'country'        => 'nullable|string|max:100',
            'Requirement'    => 'nullable|string',
            'date'           => 'nullable|date',
            'time'           => 'nullable',
            'status'         => 'required',
            'comment'        => 'nullable|string',
        ]);

        $oldStatus = $lm->status;
        $discussion = $request->input('discussion') === 'draft' ? 'draft' : 'add';

        $lm->update([
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'contact_number' => $request->contact_number,
            'email'          => $request->email,
            'state'          => $request->state,
            'city'           => $request->city,
            'country'        => $request->country,
            'Requirement'    => $request->requirement,
            'date'           => $request->date   ?? null,
            'time'           => $request->time   ?? null,
            'status'         => $request->status,
            'comment'        => $request->comment,
            'discussion'     => $discussion,
        ]);

        // ✅ Sirf status change hone pe history banao
        // Same status pe edit ki toh history nahi banegi
        LeadHistory::create([
    'lead_id'     => $lm->id,
    'event_type'  => $oldStatus !== $request->status ? 'status_changed' : 'edited',
    'from_status' => $oldStatus,
    'to_status'   => $request->status,
    'date'        => $request->date   ?? null,
    'time'        => $request->time   ?? null,
    'comment'     => $request->comment ?? null,
    'document'    => null,
]);
        

        return redirect()->route('admin.lms.index')->with('success', 'Lead updated successfully!');
    }

    // ─── DESTROY ─────────────────────────────────────────────────────
    public function destroy(Lead $lm)
    {
        $lm->delete();
        return redirect()->route('admin.lms.index')->with('success', 'Lead deleted!');
    }

    // ─── ACTION ──────────────────────────────────────────────────────
    public function action(Request $request)
{
    $lead      = Lead::findOrFail($request->lead_id);
    $oldStatus = $lead->status;

    // ✅ Document upload handle karo
    $documentPath = null;
    if ($request->hasFile('proposal')) {
        $documentPath = $request->file('proposal')->store('proposals', 'public');
        $lead->document = $documentPath;  // ✅ lead table mein bhi save
    }

    $lead->status  = $request->action_type;
    $lead->date    = $request->date    ?? null;
    $lead->time    = $request->time    ?? null;
    $lead->comment = $request->comment ?? null;
    $lead->save();

    LeadHistory::create([
        'lead_id'     => $lead->id,
        'event_type'  => 'status_changed',
        'from_status' => $oldStatus,
        'to_status'   => $lead->status,
        'date'        => $request->date    ?? null,
        'time'        => $request->time    ?? null,
        'comment'     => $request->comment ?? null,
        'document'    => $documentPath,    // ✅ history mein bhi save
    ]);

    return redirect()->back()->with('success', 'Action saved!');
}
    // ─── HISTORY API ─────────────────────────────────────────────────
    public function history($id)
{
    $records = LeadHistory::where('lead_id', $id)
                ->orderBy('created_at', 'asc')
                ->get();

    $history = $records->map(function($h) {
        return [
            'event_type'  => $h->event_type,
            'from_status' => $h->from_status,
            'to_status'   => $h->to_status,
            'comment'     => $h->comment,
            'document'    => $h->document ? asset('storage/' . $h->document) : null,  // ✅ full URL
            'date'        => $h->date,
            'time'        => $h->time,
            'created_at'  => $h->created_at->format('d-m-Y H:i:s'),
        ];
    });

    return response()->json([
        'status'  => true,
        'history' => $history,
    ]);
}
}