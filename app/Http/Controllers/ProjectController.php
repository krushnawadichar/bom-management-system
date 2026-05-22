<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('creator')->paginate(15);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_code' => 'required|unique:projects',
            'project_name' => 'required',
            'customer_name' => 'nullable|string',
            'work_order_no' => 'nullable|string'
        ]);
        
        try {
            Project::create([
                'project_code' => $request->project_code,
                'project_name' => $request->project_name,
                'customer_name' => $request->customer_name,
                'work_order_no' => $request->work_order_no,
                'status' => 'active',
                'created_by' => auth()->id()
            ]);
            
            Log::info('Project created: ' . $request->project_code . ' by ' . auth()->user()->email);
            
            return redirect()->route('projects.index')->with('success', 'Project created successfully');
        } catch (\Exception $e) {
            Log::error('Project creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create project');
        }
    }

    public function show($id)
    {
        $project = Project::with(['bomHeaders', 'bomHeaders.uploader'])->findOrFail($id);
        return view('projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        $request->validate([
            'project_name' => 'required',
            'status' => 'required|in:draft,active,completed,cancelled'
        ]);
        
        try {
            $project->update($request->only(['project_name', 'customer_name', 'work_order_no', 'status']));
            
            Log::info('Project updated: ' . $project->project_code . ' by ' . auth()->user()->email);
            
            return redirect()->back()->with('success', 'Project updated successfully');
        } catch (\Exception $e) {
            Log::error('Project update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update project');
        }
    }

    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();
            
            Log::info('Project deleted: ' . $project->project_code . ' by ' . auth()->user()->email);
            
            return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
        } catch (\Exception $e) {
            Log::error('Project deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete project');
        }
    }
}