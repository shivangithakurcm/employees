<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\ProjectType;

class ProjectTypeController extends Controller
{
    public function index()
    {
        $items = ProjectType::latest()->paginate(20);
        return view('admin.master.project_type', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:project_types,name'
        ]);
        ProjectType::create(['name' => $request->name]);
        return back()->with('success', 'Project Type added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:project_types,name,' . $id
        ]);
        ProjectType::findOrFail($id)->update(['name' => $request->name]);
        return back()->with('success', 'Project Type updated successfully.');
    }

    public function destroy($id)
    {
        ProjectType::findOrFail($id)->delete();
        return back()->with('success', 'Project Type deleted successfully.');
    }
}