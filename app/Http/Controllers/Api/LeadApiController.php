<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadApiController extends Controller
{
    // ✅ 1. GET ALL LEADS (filters + search + pagination)
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

        $counts = [
            'all'                => Lead::where(function($q) {
                                        $q->where('discussion', 'add')->orWhereNull('discussion');
                                    })->count(),
            'follow_up'          => Lead::whereIn('status', ['call_back_required', 'call_schedule'])->count(),
            'qualified'          => Lead::where('status', 'qualified')->count(),
            'proposal_sent'      => Lead::where('status', 'proposal_sent')->count(),
            'lost'               => Lead::where('status', 'lost')->count(),
            'won'                => Lead::where('status', 'won')->count(),
            'call_back_required' => Lead::where('status', 'call_back_required')->count(),
            'call_schedule'      => Lead::where('status', 'call_schedule')->count(),
            'draft'              => Lead::where('discussion', 'draft')->count(),
        ];

        return response()->json(['status' => true, 'counts' => $counts, 'data' => $leads]);
    }

    // ✅ 2. GET SINGLE LEAD (V button)
    public function show($id)
    {
        $lead = Lead::find($id);
        if (!$lead) {
            return response()->json(['status' => false, 'message' => 'Lead not found'], 404);
        }
        return response()->json(['status' => true, 'data' => $lead]);
    }

    // ✅ 3. ADD LEAD
    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'contact_number' => 'required|numeric|digits:10',
            'email'          => 'nullable|email|unique:leads,email',
            'status'         => 'required|string',
            'date'           => 'nullable|date',
            'time'           => 'nullable',
        ]);

        $discussion = $request->input('discussion') === 'draft' ? 'draft' : 'add';

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
            'date'           => $request->date,
            'time'           => $request->time,
            'status'         => $request->status,
            'comment'        => $request->comment,
            'discussion'     => $discussion,
        ]);

        return response()->json(['status' => true, 'message' => 'Lead added successfully', 'data' => $lead], 201);
    }

    // ✅ 4. UPDATE LEAD (E button)
    public function update(Request $request, $id)
    {
        $lead = Lead::find($id);
        if (!$lead) {
            return response()->json(['status' => false, 'message' => 'Lead not found'], 404);
        }

        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'contact_number' => 'required|numeric|digits:10',
            'email'          => 'nullable|email|unique:leads,email,' . $id,
            'status'         => 'required|string',
            'date'           => 'nullable|date',
            'time'           => 'nullable',
        ]);

        $discussion = $request->input('discussion') === 'draft' ? 'draft' : 'add';

        $lead->update([
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'contact_number' => $request->contact_number,
            'email'          => $request->email,
            'state'          => $request->state,
            'city'           => $request->city,
            'country'        => $request->country,
            'Requirement'    => $request->requirement,
            'date'           => $request->date,
            'time'           => $request->time,
            'status'         => $request->status,
            'comment'        => $request->comment,
            'discussion'     => $discussion,
        ]);

        return response()->json(['status' => true, 'message' => 'Lead updated successfully', 'data' => $lead]);
    }

    // ✅ 5. ACTION + STATUS UPDATE (A button — saare statuses ek jagah)
    public function action(Request $request, $id)
{
    try {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found',
                'data' => null
            ], 404);
        }

        $validated = $request->validate([
            'action_type' => 'required|in:lost,qualified,reschedule,call_schedule,call_back_required,not_interested,not_responded,not_in_scope,proposal_sent,won',
            'comment'     => 'required|string',
            'date'        => 'nullable|date|required_if:action_type,reschedule,call_schedule,call_back_required',
            'time'        => 'nullable|required_if:action_type,reschedule,call_schedule,call_back_required',
        ]);

        switch ($validated['action_type']) {

            case 'reschedule':
            case 'call_back_required':
                $lead->status = 'call_back_required';
                $lead->date   = $validated['date'];
                $lead->time   = $validated['time'];
                break;

            case 'call_schedule':
                $lead->status = 'call_schedule';
                $lead->date   = $validated['date'];
                $lead->time   = $validated['time'];
                break;

            default:
                $lead->status = $validated['action_type'];
                $lead->date   = null;
                $lead->time   = null;
                break;
        }

        $lead->comment = $validated['comment'];
        $lead->save();

        return response()->json([
            'success' => true,
            'message' => 'Action updated successfully',
            'data' => [
                'id'      => $lead->id,
                'status'  => $lead->status,
                'date'    => $lead->date,
                'time'    => $lead->time,
                'comment' => $lead->comment,
            ]
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {

        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors'  => $e->errors()
        ], 422);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'error'   => $e->getMessage()
        ], 500);
    }
}

    // ✅ 6. SINGLE DELETE (D button)
    public function destroy($id)
    {
        $lead = Lead::find($id);
        if (!$lead) {
            return response()->json(['status' => false, 'message' => 'Lead not found'], 404);
        }
        $lead->delete();
        return response()->json(['status' => true, 'message' => 'Lead deleted successfully']);
    }

    // ✅ 7. BULK DELETE
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:leads,id',
        ]);

        $deleted = Lead::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => true, 'message' => $deleted . ' lead(s) deleted successfully']);
    }

    // ✅ 8. BULK UPDATE
    public function updateMultiple(Request $request)
    {
        if (!$request->leads || !is_array($request->leads)) {
            return response()->json(['status' => false, 'message' => 'leads array required'], 422);
        }

        foreach ($request->leads as $leadData) {
            $lead = Lead::find($leadData['id'] ?? null);
            if ($lead) $lead->update($leadData);
        }

        return response()->json(['status' => true, 'message' => 'Leads updated successfully']);
    }
}