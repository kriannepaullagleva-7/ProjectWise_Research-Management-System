<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ResearchProjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of research projects
     */
    public function index(\Illuminate\Http\Request $request): View
    {
        $query = ResearchProject::where('status', 'approved')
            ->with('user', 'assignedFaculty');

        // Handle search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('abstract', 'like', '%' . $search . '%')
                  ->orWhere('keywords', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('full_name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Handle category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Handle sort
        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'views') {
            $query->orderBy('views_count', 'desc');
        } elseif ($sort === 'downloads') {
            $query->orderBy('downloads_count', 'desc');
        } else {
            $query->latest();
        }

        $projects = $query->paginate(12)
            ->appends($request->query());

        // Get list of all categories for filter dropdown
        $categories = [
            'Computer Science & IT',
            'Engineering',
            'Natural Sciences',
            'Social Sciences',
            'Humanities',
            'Business & Economics',
            'Health & Medicine',
            'Education',
            'Environmental Science',
            'Mathematics',
            'Other'
        ];

        return view('research.index', [
            'projects' => $projects,
            'categories' => $categories,
        ]);
    }

    /**
     * Show user's own projects
     */
    public function myProjects(): View
    {
        $projects = ResearchProject::where('user_id', Auth::id())
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
            'abstract' => 'required|string|min:10|max:2000',
            'category' => 'required|string|max:100',
            'field_of_study' => 'nullable|string|max:100',
            'keywords' => 'nullable|string|max:500',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
        ]);

        $project = ResearchProject::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['abstract'],
            'abstract' => $validated['abstract'],
            'category' => $validated['category'],
            'field_of_study' => $validated['field_of_study'] ?? null,
            'keywords' => $validated['keywords'] ?? null,
            'status' => 'pending',
            'submission_date' => now(),
            'approval_date' => null,
            'views_count' => 0,
            'downloads_count' => 0,
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
        $researchProject->increment('views_count');
        
        $researchProject->load('user', 'assignedFaculty', 'reviews');
        
        // Check if user has saved this project
        $isSaved = false;
        if (Auth::check()) {
            $isSaved = \App\Models\SavedItem::where('user_id', Auth::id())
                ->where('research_project_id', $researchProject->id)
                ->exists();
        }

        return view('research.show', compact('researchProject', 'isSaved'));
    }

    /**
     * Show the form for editing the project
     */
    public function edit(ResearchProject $researchProject): View
    {
        $this->authorize('update', $researchProject);

        return view('research.create', ['researchProject' => $researchProject]);
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, ResearchProject $researchProject): RedirectResponse
    {
        $this->authorize('update', $researchProject);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string|min:10|max:2000',
            'category' => 'required|string|max:100',
            'field_of_study' => 'nullable|string|max:100',
            'keywords' => 'nullable|string|max:500',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
        ]);

        $researchProject->update([
            'title' => $validated['title'],
            'description' => $validated['abstract'],
            'abstract' => $validated['abstract'],
            'category' => $validated['category'],
            'field_of_study' => $validated['field_of_study'] ?? null,
            'keywords' => $validated['keywords'] ?? null,
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('research-projects', 'public');
            $researchProject->update(['file_path' => $path]);
        }

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

        // Increment download count
        $researchProject->increment('downloads_count');

        $filePath = storage_path('app/public/' . $researchProject->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found on server');
        }

        return response()->download($filePath, basename($researchProject->file_path));
    }
}
