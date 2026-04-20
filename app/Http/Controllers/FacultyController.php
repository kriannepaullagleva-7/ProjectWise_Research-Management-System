<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use App\Models\FacultyReview;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FacultyController extends Controller
{
    use AuthorizesRequests;

    public function explorer(): View
    {
        $projects = ResearchProject::whereIn('status', ['pending', 'under_review'])
            ->where(function ($query) {
                $query->whereNull('assigned_faculty_id')
                    ->orWhere('assigned_faculty_id', Auth::id());
            })
            ->with('user', 'reviews')
            ->latest()
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
            $researchProject->update(['assigned_faculty_id' => Auth::id()]);
        }

        $existingReview = FacultyReview::where('research_project_id', $researchProject->id)
            ->where('faculty_id', Auth::id())
            ->first();

        return view('faculty.review', [
            'project' => $researchProject,
            'review' => $existingReview,
        ]);
    }

    /**
     * Submit feedback for a project - saves review and updates project status in database
     */
    public function submitFeedback(ResearchProject $researchProject, Request $request): RedirectResponse
    {
        $this->authorize('review', $researchProject);

        $validated = $request->validate([
            'feedback' => 'required|string|min:10|max:2000',
            'recommendation' => 'required|in:approve,reject,revise',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        // Save or update review in database
        $review = FacultyReview::updateOrCreate(
            [
                'research_project_id' => $researchProject->id,
                'faculty_id' => Auth::id(),
            ],
            [
                'feedback' => $validated['feedback'],
                'recommendation' => $validated['recommendation'],
                'rating' => $validated['rating'],
            ]
        );

        // Update project status and feedback in database
        $statusMap = [
            'approve' => 'approved',
            'reject' => 'rejected',
            'revise' => 'under_review',
        ];
        
        $researchProject->update([
            'status' => $statusMap[$validated['recommendation']] ?? 'under_review',
            'reviewer_feedback' => $validated['feedback'],
            'assigned_faculty_id' => Auth::id(),
            'approval_date' => $validated['recommendation'] === 'approve' ? now() : null,
        ]);

        return redirect()->route('faculty.explorer')
            ->with('success', 'Review submitted successfully! Feedback and project status saved to database.');
    }
}
