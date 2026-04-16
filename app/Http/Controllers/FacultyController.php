<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use App\Models\FacultyReview;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FacultyController extends Controller
{
    /**
     * Show the faculty explorer (review queue)
     */
    public function explorer(): View
    {
        $projects = ResearchProject::where('status', '!=', 'rejected')
            ->where(function ($query) {
                $query->whereNull('assigned_faculty_id')
                    ->orWhere('assigned_faculty_id', auth()->id());
            })
            ->with('user', 'reviews')
            ->paginate(15);

        return view('faculty.explorer', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the review form for a specific project
     */
    public function review(ResearchProject $researchProject): View
    {
        $this->authorize('viewReview', $researchProject);

        // Assign to current faculty if not already assigned
        if (!$researchProject->assigned_faculty_id) {
            $researchProject->update(['assigned_faculty_id' => auth()->id()]);
        }

        $existingReview = FacultyReview::where('research_project_id', $researchProject->id)
            ->where('faculty_id', auth()->id())
            ->first();

        return view('faculty.review', [
            'project' => $researchProject,
            'review' => $existingReview,
        ]);
    }

    /**
     * Submit feedback for a project
     */
    public function submitFeedback(ResearchProject $researchProject, Request $request): RedirectResponse
    {
        $this->authorize('review', $researchProject);

        $validated = $request->validate([
            'feedback' => 'required|string|min:10',
            'recommendation' => 'required|in:approve,reject,revise',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $review = FacultyReview::updateOrCreate(
            [
                'research_project_id' => $researchProject->id,
                'faculty_id' => auth()->id(),
            ],
            $validated
        );

        // Update project status based on recommendation
        if ($validated['recommendation'] === 'approve') {
            $researchProject->update(['status' => 'approved']);
        } elseif ($validated['recommendation'] === 'reject') {
            $researchProject->update(['status' => 'rejected']);
        } else {
            $researchProject->update(['status' => 'under_review']);
        }

        $researchProject->update(['reviewer_feedback' => $validated['feedback']]);

        return redirect()->route('faculty.explorer')
            ->with('success', 'Review submitted successfully!');
    }
}
