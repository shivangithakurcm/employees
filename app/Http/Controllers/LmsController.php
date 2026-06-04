<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadHistory;
use App\Models\Master\ProjectType;
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
                // ─── Won Project Type Filter ─────────────────────────
                if ($request->status == 'won' && $request->project_type) {
                    $query->where('won_project_type', $request->project_type);
                }
            }
        }

        $leads        = $query->latest()->paginate(5);
        $projectTypes = ProjectType::all();

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

        // ─── Won counts per project type ─────────────────────────────
        foreach ($projectTypes as $pt) {
            $counts['won_pt_' . $pt->id] = Lead::where('status', 'won')
                                                ->where('won_project_type', $pt->id)
                                                ->count();
        }

        return view('lms.lmsindex', compact('leads', 'counts', 'projectTypes'));
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

        LeadHistory::create([
            'lead_id'     => $lead->id,
            'event_type'  => 'created',
            'from_status' => null,
            'to_status'   => $lead->status,
            'date'        => $request->date   ?? null,
            'time'        => $request->time   ?? null,
            'comment'     => $request->comment ?? null,
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
        $projectTypes = ProjectType::all();
        return view('lms.edit', compact('lm', 'projectTypes'));
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

        $oldStatus  = $lm->status;
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

        // ─── File Uploads ───────────────────────────────────────────
        if ($request->hasFile('proposal_document')) {
            $lm->proposal_document = $request->file('proposal_document')->store('proposals', 'public');
        }
        if ($request->hasFile('revised_proposal')) {
            $lm->revised_proposal = $request->file('revised_proposal')->store('proposals', 'public');
        }

        // ─── Proposal Fields ────────────────────────────────────────
        $lm->amount             = $request->amount;
        $lm->timeline           = $request->timeline;
        $lm->negotiation_amount = $request->negotiation_amount;

        // ─── Won Fields ─────────────────────────────────────────────
        $lm->won_name           = $request->won_name;
        $lm->won_contact        = $request->won_contact;
        $lm->won_email          = $request->won_email;
        $lm->won_designation    = $request->won_designation;
        $lm->won_business_name  = $request->won_business_name;
        $lm->won_gst_no         = $request->won_gst_no;
        $lm->won_location       = $request->won_location;
        $lm->won_country        = $request->won_country;
        $lm->won_state          = $request->won_state;
        $lm->won_city           = $request->won_city;
        $lm->won_project_type   = $request->won_project_type;
        $lm->won_project_detail = $request->won_project_detail;
        $lm->won_final_cost     = $request->won_final_cost;
        $lm->won_milestone      = $request->won_milestone;
        $lm->won_timeline       = $request->won_timeline;
        $lm->won_token_received = $request->won_token_received;

        if ($request->won_token_received === 'yes') {
            $lm->won_token_amount  = $request->won_token_amount;
            $lm->won_amount_type   = $request->won_amount_type;
            $lm->won_received_date = $request->won_received_date;
            $lm->won_gst_type      = $request->won_gst_type;
        }

        $lm->save();

        if ($oldStatus !== $request->status) {
            LeadHistory::create([
                'lead_id'     => $lm->id,
                'event_type'  => 'status_changed',
                'from_status' => $oldStatus,
                'to_status'   => $request->status,
                'date'        => $request->date    ?? null,
                'time'        => $request->time    ?? null,
                'comment'     => $request->comment ?? null,
                'document'    => null,
            ]);
        }

        $status = $request->status;

        if (in_array($status, ['call_back_required', 'call_schedule', 'not_responded'])) {
            return redirect()->route('admin.lms.index', ['status' => 'follow_up'])->with('success', 'Lead updated successfully!');
        } elseif (in_array($status, ['lost', 'not_interested', 'not_in_scope'])) {
            return redirect()->route('admin.lms.index', ['status' => 'lost'])->with('success', 'Lead updated successfully!');
        } elseif ($status === 'draft') {
            return redirect()->route('admin.lms.index', ['status' => 'draft'])->with('success', 'Lead updated successfully!');
        } elseif ($status === 'won') {
            return redirect()->route('admin.lms.index', ['status' => 'won'])->with('success', 'Lead marked as Won!');
        } else {
            return redirect()->route('admin.lms.index', ['status' => $status])->with('success', 'Lead updated successfully!');
        }
    }

    // ─── DESTROY ─────────────────────────────────────────────────────
   // ─── DESTROY ─────────────────────────────────────────────────────
public function destroy(Lead $lm)
{
    $lm->delete();

    $params = array_filter([
        'status' => request('redirect_status'),
        'type'   => request('redirect_type'),
    ]);

    return redirect()->route('admin.lms.index', $params)
                     ->with('success', 'Lead deleted successfully.');
}

    // ─── ACTION ──────────────────────────────────────────────────────
    public function action(Request $request)
    {
        $lead      = Lead::findOrFail($request->lead_id);
        $oldStatus = $lead->status;

        $documentPath        = null;
        $revisedDocumentPath = null;

        if ($request->action_type === 'negotiation') {
            $lead->status = 'proposal_sent';
        } else {
            $lead->status = $request->action_type;
        }

        $lead->date    = $request->date    ?? null;
        $lead->time    = $request->time    ?? null;
        $lead->comment = $request->comment ?? null;

        // ─── Proposal Sent ──────────────────────────────────────────
        if ($request->action_type === 'proposal_sent') {
            if ($request->hasFile('proposal')) {
                $path = $request->file('proposal')->store('proposals', 'public');
                $lead->proposal_document = $path;
                $documentPath = $path;
            }
            $lead->amount   = $request->amount;
            $lead->timeline = $request->timeline;
        }

        // ─── Negotiation ────────────────────────────────────────────
        if ($request->action_type === 'negotiation') {
            if ($request->hasFile('revised_proposal')) {
                $path = $request->file('revised_proposal')->store('proposals', 'public');
                $lead->revised_proposal = $path;
                $revisedDocumentPath = $path;
            }
            $lead->negotiation_amount = $request->negotiation_amount;
        }

        // ─── Won ────────────────────────────────────────────────────
        if ($request->action_type === 'won') {
            $lead->won_name           = $request->won_name;
            $lead->won_contact        = $request->won_contact;
            $lead->won_email          = $request->won_email;
            $lead->won_designation    = $request->won_designation;
            $lead->won_business_name  = $request->won_business_name;
            $lead->won_gst_no         = $request->won_gst_no;
            $lead->won_location       = $request->won_location;
            $lead->won_country        = $request->won_country;
            $lead->won_state          = $request->won_state;
            $lead->won_city           = $request->won_city;
            $lead->won_project_type   = $request->won_project_type;
            $lead->won_project_detail = $request->won_project_detail;
            $lead->won_final_cost     = $request->won_final_cost;
            $lead->won_milestone      = $request->won_milestone;
            $lead->won_timeline       = $request->won_timeline;
            $lead->won_token_received = $request->won_token_received;

            if ($request->won_token_received === 'yes') {
                $lead->won_token_amount  = $request->won_token_amount;
                $lead->won_amount_type   = $request->won_amount_type;
                $lead->won_received_date = $request->won_received_date;
                $lead->won_gst_type      = $request->won_gst_type;
            }
        }

        $lead->save();

        // ─── History Save ────────────────────────────────────────────
        LeadHistory::create([
            'lead_id'            => $lead->id,
            'event_type'         => 'status_changed',
            'from_status'        => $oldStatus,
            'to_status'          => $lead->status,
            'date'               => $request->date    ?? null,
            'time'               => $request->time    ?? null,
            'comment'            => $request->comment ?? null,
            'document'           => $documentPath,
            'revised_document'   => $revisedDocumentPath,
            'negotiation_amount' => $request->action_type === 'negotiation'
                                        ? $request->negotiation_amount
                                        : null,
        ]);

        if ($request->action_type === 'won') {
            return redirect()->route('admin.lms.index', ['status' => 'won'])
                             ->with('success', '🎉 Lead Won!');
        }

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
                'event_type'         => $h->event_type,
                'from_status'        => $h->from_status,
                'to_status'          => $h->to_status,
                'comment'            => $h->comment,
                'document'           => $h->document         ? asset('storage/' . $h->document)         : null,
                'revised_document'   => $h->revised_document ? asset('storage/' . $h->revised_document) : null,
                'negotiation_amount' => $h->negotiation_amount ?? null,
                'date'               => $h->date,
                'time'               => $h->time,
                'created_at'         => $h->created_at->format('d-m-Y H:i:s'),
            ];
        });

        return response()->json([
            'status'  => true,
            'history' => $history,
        ]);
    }
}