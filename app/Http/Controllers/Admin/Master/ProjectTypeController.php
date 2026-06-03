<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\ProjectType;

class ProjectTypeController extends Controller
{
    public function index()
    {
        if (request()->expectsJson()) {
            return response()->json([
                'status' => true,
                'data'   => ProjectType::latest()->get()
            ]);
        }

        $items = ProjectType::latest()->paginate(20);
        return view('admin.master.project_type', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:project_types,name'
        ]);

        $projectType = ProjectType::create(['name' => $request->name]);

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Project Type added successfully.',
                'data'    => $projectType
            ], 201);
        }

        return back()->with('success', 'Project Type added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:project_types,name,' . $id
        ]);

        $projectType = ProjectType::findOrFail($id);
        $projectType->update(['name' => $request->name]);

        if ($request->expectsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Project Type updated successfully.',
                'data'    => $projectType
            ]);
        }

        return back()->with('success', 'Project Type updated successfully.');
    }

    public function destroy($id)
    {
        ProjectType::findOrFail($id)->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Project Type deleted successfully.'
            ]);
        }

        return back()->with('success', 'Project Type deleted successfully.');
    }
}