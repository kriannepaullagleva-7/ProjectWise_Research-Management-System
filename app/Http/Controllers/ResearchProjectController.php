<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ResearchProjectController extends Controller
{
    /**
     * Display a listing of research projects
     */
    public function index(): View
    {
        $projects = ResearchProject::where('status', 'approved')
            ->with('user', 'assignedFaculty')
            ->latest()
            ->paginate(12);

        return view('research.index', ['projects' => $projects]);
    }

    /**
     * Show user's own projects
     */
    public function myProjects(): View
    {
        $projects = ResearchProject::where('user_id', auth()->id())
            ->with('assignedFaculty', 'reviews')
            ->latest()
            ->paginate(10);

        return view('research.my-projects', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new project
     */
    public function create(): View
    {
        return view('research.create');
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category' => 'required|string|max:100',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $project = ResearchProject::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'status' => 'pending',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('research-projects', 'public');
            $project->update(['file_path' => $path]);
        }

        return redirect()->route('research.show', $project)
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified project
     */
    public function show(ResearchProject $researchProject): View
    {
        $researchProject->increment('view_count');
        $researchProject->load('user', 'assignedFaculty', 'reviews');

        return view('research.show', ['project' => $researchProject]);
    }

    /**
     * Show the form for editing the project
     */
    public function edit(ResearchProject $researchProject): View
    {
        $this->authorize('update', $researchProject);

        return view('research.edit', ['project' => $researchProject]);
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, ResearchProject $researchProject): RedirectResponse
    {
        $this->authorize('update', $researchProject);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category' => 'required|string|max:100',
        ]);

        $researchProject->update($validated);

        return redirect()->route('research.show', $researchProject)
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified project
     */
    public function destroy(ResearchProject $researchProject): RedirectResponse
    {
        $this->authorize('delete', $researchProject);

        $researchProject->delete();

        return redirect()->route('research.my-projects')
            ->with('success', 'Project deleted successfully!');
    }

    /**
     * Download a project file
     */
    public function download(ResearchProject $researchProject)
    {
        if (!$researchProject->file_path) {
            abort(404, 'File not found');
        }

        return response()->download(storage_path('app/public/' . $researchProject->file_path));
    }
}
