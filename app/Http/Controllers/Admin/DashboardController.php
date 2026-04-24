<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Lead;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_employees' => Employee::count(),
            'total_leads'     => Lead::count(),
            'total_qualified' => Lead::where('status', 'qualified')->count(),
            'total_prospect'  => Lead::where('status', 'proposal_sent')->count(),
            'total_lost'      => Lead::where('status', 'lost')->count(),
            'total_won'       => Lead::where('status', 'won')->count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}