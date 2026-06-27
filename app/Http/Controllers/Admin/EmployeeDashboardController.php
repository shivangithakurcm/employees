<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\FollowUp;
use Carbon\Carbon;

class EmployeeDashboardController extends Controller
{
    public function index()
    { 
       
        $userId = auth()->id();

        // ✅ Only this employee's leads — no global/employee counts
        $stats = [
            'total_leads'  => Lead::where('assigned_to', $userId)->count(),
            'follow_up'    => Lead::where('assigned_to', $userId)
                                  ->whereIn('status', ['call_back_required', 'call_schedule', 'not_responded'])
                                  ->count(),
            'qualified'    => Lead::where('assigned_to', $userId)->where('status', 'qualified')->count(),
            'proposal'     => Lead::where('assigned_to', $userId)->where('status', 'proposal_sent')->count(),
            'won'          => Lead::where('assigned_to', $userId)->where('status', 'won')->count(),
            'lost'         => Lead::where('assigned_to', $userId)
                                  ->whereIn('status', ['lost', 'not_interested', 'not_in_scope'])
                                  ->count(),
            // ❌ DO NOT add total_employees or any global stat here
        ];

        // Daily lead counts for current month (chart)
        $daysInMonth = now()->daysInMonth;
        $dailyLabels = [];
        $dailyData   = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dailyLabels[] = $d;
            $dailyData[]   = Lead::where('assigned_to', $userId)
                                 ->whereYear('created_at', now()->year)
                                 ->whereMonth('created_at', now()->month)
                                 ->whereDay('created_at', $d)
                                 ->count();
        }

        // ✅ Only this employee's recent leads
        $recentLeads = Lead::where('assigned_to', $userId)
                           ->latest()
                           ->limit(5)
                           ->get();

        // ✅ Only follow-ups linked to this employee's leads
        $todayFollowups = FollowUp::with('lead')
            ->whereHas('lead', fn($q) => $q->where('assigned_to', $userId))
            ->whereDate('date', Carbon::today())
            ->where('status', 'pending')
            ->orderBy('time')
            ->get();

        $totalLeads     = $stats['total_leads'];
        $conversionRate = $totalLeads > 0
            ? round(($stats['won'] / $totalLeads) * 100, 1)
            : 0;

        return view('admin.dashboard.employee', compact(
            'stats',
            'dailyLabels',
            'dailyData',
            'recentLeads',
            'todayFollowups',
            'conversionRate'
        ));
    }
}