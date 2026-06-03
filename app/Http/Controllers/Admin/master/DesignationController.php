<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Designation;

class DesignationController extends Controller
{
    public function index()
    {
        // Web request → view, API request → JSON
        if (request()->expectsJson()) {
            return response()->json([
                'status' => true,
                'data'   => Designation::latest()->get()
            ]);
        }

        $items = Designation::latest()->paginate(20);
        return view('admin.master.designation', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:designations,name'
        ]);

        $designation = Designation::create(['name' => $request->name]);

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Designation added successfully.',
                'data'    => $designation
            ], 201);
        }

        return back()->with('success', 'Designation added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:designations,name,' . $id
        ]);

        $designation = Designation::findOrFail($id);
        $designation->update(['name' => $request->name]);

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Designation updated successfully.',
                'data'    => $designation
            ]);
        }

        return back()->with('success', 'Designation updated successfully.');
    }

    public function destroy($id)
    {
        Designation::findOrFail($id)->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Designation deleted successfully.'
            ]);
        }

        return back()->with('success', 'Designation deleted successfully.');
    }
}