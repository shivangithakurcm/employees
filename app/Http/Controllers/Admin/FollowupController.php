<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FollowUpController extends Controller
{
    // Aaj ke + overdue followups page
public function today()
{
    $today = Carbon::today();
    $isEmployee = auth()->user()->hasRole('employee');
    $userId = auth()->id();

    // ✅ Sirf apne leads ke followups overdue mark ho
    $overdueQuery = FollowUp::whereDate('date', '<', $today)->where('status', 'pending');
    if ($isEmployee) {
        $overdueQuery->whereHas('lead', fn($q) => $q->where('assigned_to', $userId));
    }
    $overdueQuery->update(['status' => 'overdue']);

    // ✅ Aaj ke followups — employee ke liye filtered
    $todayList = FollowUp::with('lead')
        ->whereDate('date', $today)
        ->where('status', 'pending')
        ->when($isEmployee, fn($q) => $q->whereHas('lead', fn($l) => $l->where('assigned_to', $userId)))
        ->orderBy('time')
        ->get();

    // ✅ Overdue — employee ke liye filtered
    $overdueList = FollowUp::with('lead')
        ->where('status', 'overdue')
        ->when($isEmployee, fn($q) => $q->whereHas('lead', fn($l) => $l->where('assigned_to', $userId)))
        ->orderBy('date')
        ->orderBy('time')
        ->get();

    // ✅ Done today — employee ke liye filtered
    $doneToday = FollowUp::whereDate('date', $today)
        ->where('status', 'done')
        ->when($isEmployee, fn($q) => $q->whereHas('lead', fn($l) => $l->where('assigned_to', $userId)))
        ->count();

    $employees = !$isEmployee
        ? \App\Models\User::role('employee')
            ->withCount([
                'assignedLeads as total_leads',
                'assignedLeads as followup_leads' => fn($q) => $q->whereIn('status', ['call_back_required','call_schedule','not_responded']),
                'assignedLeads as won_leads'      => fn($q) => $q->where('status','won'),
                'assignedLeads as lost_leads'     => fn($q) => $q->whereIn('status', ['lost','not_interested','not_in_scope']),
            ])->get()
        : collect();

    return view('admin.followups.today', compact(
        'todayList', 'overdueList', 'doneToday', 'employees'
    ));
}
    // Naya followup schedule karo (lead detail page se)
    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'date'    => 'required|date|after_or_equal:today',
            'time'    => 'required',
            'comment' => 'nullable|string|max:500',
        ]);

        FollowUp::create([
            'lead_id' => $request->lead_id,
            'date'    => $request->date,
            'time'    => $request->time,
            'comment' => $request->comment,
            'status'  => 'pending',
        ]);

        return back()->with('success', '✅ Follow-up scheduled successfully!');
    }

    // Mark as done
    public function markDone($id)
    {
        FollowUp::findOrFail($id)->update(['status' => 'done']);

        return back()->with('success', '✅ Marked as done!');
    }
}