<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        if (request()->expectsJson()) {
            return response()->json([
                'status' => true,
                'data'   => Shift::latest()->get()
            ]);
        }

        $shifts = Shift::latest()->paginate(10);
        return view('admin.master.shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('admin.master.shifts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shift_name' => 'required|string|max:100|unique:shifts,shift_name',
            'shift_from' => 'required',
            'shift_to'   => 'required',
        ]);

        $shift = Shift::create($request->only('shift_name', 'shift_from', 'shift_to'));

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Shift added successfully.',
                'data'    => $shift
            ], 201);
        }

        return redirect()->route('admin.master.shift.index')
                         ->with('success', 'Shift added successfully.');
    }

    public function edit($id)
    {
        return view('admin.master.shifts.edit', [
            'shift' => Shift::findOrFail($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $request->validate([
            'shift_name' => 'required|string|max:100|unique:shifts,shift_name,' . $shift->id,
            'shift_from' => 'required',
            'shift_to'   => 'required',
        ]);

        $shift->update($request->only('shift_name', 'shift_from', 'shift_to'));

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Shift updated successfully.',
                'data'    => $shift
            ]);
        }

        return redirect()->route('admin.master.shift.index')
                         ->with('success', 'Shift updated successfully.');
    }

    public function destroy($id)
    {
        Shift::findOrFail($id)->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Shift deleted successfully.'
            ]);
        }

        return redirect()->route('admin.master.shift.index')
                         ->with('success', 'Shift deleted.');
    }
}