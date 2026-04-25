<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LmsController extends Controller
{
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
            $query->where(function($q) use ($request){
                $q->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name', 'like', '%'.$request->search.'%')
                  ->orWhere('contact_number', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->state) {
            $query->where('state', $request->state);
        }

        if ($request->city) {
            $query->where('city', 'like', '%'.$request->city.'%');
        }

        if ($request->status) {
            if ($request->status == 'draft') {
                $query->where('discussion', 'draft');
            } elseif ($request->status == 'follow_up') {
                if ($request->type == 'call_back_required') {
                    $query->where('status', 'call_back_required');
                } elseif ($request->type == 'call_schedule') {
                    $query->where('status', 'call_schedule');
                } else {
                    $query->whereIn('status', ['call_back_required', 'call_schedule']);
                }
            } else {
                $query->where('status', $request->status);
            }
        }

        $leads = $query->latest()->paginate(5);

        $counts = [
            'all' => Lead::where(function($q) {
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

        return view('lms.lmsindex', compact('leads', 'counts'));
    }

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

        Lead::create([
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
            'discussion'     => $request->discussion,
        ]);

        // ✅ admin. prefix add kiya
        return redirect()->route('admin.lms.index')->with('success', 'Lead added successfully!');
    }

    public function show(Lead $lm)
    {
        return view('lms.show', compact('lm'));
    }

    public function edit(Lead $lm)
    {
        // ✅ dd() hataya
        return view('lms.edit', compact('lm'));
    }

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
            'date'           => $request->date,
            'time'           => $request->time,
            'status'         => $request->status,
            'comment'        => $request->comment,
            'discussion'     => $discussion,
        ]);

        // ✅ admin. prefix add kiya
        return redirect()->route('admin.lms.index')->with('success', 'Lead updated successfully!');
    }

    public function destroy(Lead $lm)
    {
        $lm->delete();
        // ✅ admin. prefix add kiya
        return redirect()->route('admin.lms.index')->with('success', 'Lead deleted!');
    }

    public function action(Request $request)
    {
        $request->validate([
            'lead_id'     => 'required|exists:leads,id',
            'action_type' => 'required',
            'comment'     => 'required|string',
            'date'        => 'nullable|date',
            'time'        => 'nullable',
        ]);

        $lead = Lead::findOrFail($request->lead_id);

        if ($request->action_type == 'lost') {
            $lead->status = 'lost';
            $lead->date   = null;
            $lead->time   = null;
        }
        if ($request->action_type == 'qualified') {
            $lead->status = 'qualified';
            $lead->date   = null;
            $lead->time   = null;
        }
        if ($request->action_type == 'reschedule') {
            $lead->status = 'call_back_required';
            $lead->date   = $request->date;
            $lead->time   = $request->time;
        }
        if ($request->action_type == 'call_schedule') {
            $lead->status = 'call_schedule';
            $lead->date   = $request->date;
            $lead->time   = $request->time;
        }
        if ($request->action_type == 'call_back_required') {
            $lead->status = 'call_back_required';
            $lead->date   = $request->date;
            $lead->time   = $request->time;
        }

        $lead->comment = $request->comment;
        $lead->save();

        return redirect()->back()->with('success', 'Action updated successfully!');
    }
}