<?php

namespace App\Services;

use App\Models\FacultyReview;
use App\Models\ResearchProject;
use Illuminate\Pagination\Paginator;

/**
 * Faculty Review Service - Business logic for research project reviews
 */
class FacultyReviewService
{
    /**
     * Get all reviews for a project
     */
    public function getProjectReviews(int $projectId, int $perPage = 10): Paginator
    {
        return FacultyReview::where('research_project_id', $projectId)
            ->with('faculty')
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get reviews by faculty member
     */
    public function getFacultyReviews(int $facultyId, int $perPage = 15): Paginator
    {
        return FacultyReview::where('faculty_id', $facultyId)
            ->with('researchProject', 'researchProject.user')
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get pending reviews for faculty
     */
    public function getPendingReviews(int $facultyId, int $perPage = 15): Paginator
    {
        return ResearchProject::whereNull('assigned_faculty_id')
            ->orWhere('assigned_faculty_id', $facultyId)
            ->where('status', '!=', 'approved')
            ->where('status', '!=', 'rejected')
            ->with('user', 'reviews')
            ->latest('submission_date')
            ->paginate($perPage);
    }

    /**
     * Submit a review
     */
    public function submitReview(int $projectId, int $facultyId, array $data): FacultyReview
    {
        $review = FacultyReview::updateOrCreate(
            [
                'research_project_id' => $projectId,
                'faculty_id' => $facultyId,
            ],
            [
                'feedback' => $data['feedback'] ?? null,
                'recommendation' => $data['recommendation'] ?? null,
                'rating' => $data['rating'] ?? null,
            ]
        );

        // Update project status based on recommendation
        $this->updateProjectStatus($projectId, $data['recommendation'] ?? null);

        return $review;
    }

    /**
     * Get faculty member's review for a project
     */
    public function getFacultyReviewForProject(int $projectId, int $facultyId): ?FacultyReview
    {
        return FacultyReview::where('research_project_id', $projectId)
            ->where('faculty_id', $facultyId)
            ->first();
    }

    /**
     * Get average rating for project
     */
    public function getAverageRating(int $projectId): ?float
    {
        return FacultyReview::where('research_project_id', $projectId)
            ->whereNotNull('rating')
            ->avg('rating');
    }

    /**
     * Get review statistics
     */
    public function getReviewStatistics()
    {
        return [
            'total_reviews' => FacultyReview::count(),
            'approved_projects' => ResearchProject::where('status', 'approved')->count(),
            'rejected_projects' => ResearchProject::where('status', 'rejected')->count(),
            'under_review_projects' => ResearchProject::where('status', 'under_review')->count(),
            'pending_projects' => ResearchProject::where('status', 'pending')->count(),
        ];
    }

    /**
     * Get projects by review recommendation
     */
    public function getProjectsByRecommendation(string $recommendation, int $perPage = 15): Paginator
    {
        return ResearchProject::whereHas('reviews', function ($query) use ($recommendation) {
            $query->where('recommendation', $recommendation);
        })
        ->with('user', 'reviews')
        ->latest('created_at')
        ->paginate($perPage);
    }

    /**
     * Get high-rated projects
     */
    public function getHighRatedProjects(float $minRating = 4.0, int $limit = 10)
    {
        return ResearchProject::where('status', 'approved')
            ->withAvg('reviews', 'rating')
            ->having('reviews_avg_rating', '>=', $minRating)
            ->orderByDesc('reviews_avg_rating')
            ->with('user')
            ->limit($limit)
            ->get();
    }

    /**
     * Get faculty workload
     */
    public function getFacultyWorkload()
    {
        return ResearchProject::whereNotNull('assigned_faculty_id')
            ->groupBy('assigned_faculty_id')
            ->selectRaw('assigned_faculty_id, count(*) as review_count')
            ->with('assignedFaculty')
            ->get();
    }

    /**
     * Update project status based on review recommendation
     */
    private function updateProjectStatus(int $projectId, ?string $recommendation): void
    {
        $project = ResearchProject::find($projectId);

        if (!$project) {
            return;
        }

        switch ($recommendation) {
            case 'approve':
                $project->update([
                    'status' => 'approved',
                    'approval_date' => now(),
                ]);
                break;
            case 'reject':
                $project->update([
                    'status' => 'rejected',
                ]);
                break;
            case 'revise':
                $project->update([
                    'status' => 'under_review',
                ]);
                break;
        }
    }

    /**
     * Delete review
     */
    public function deleteReview(int $reviewId): bool
    {
        return FacultyReview::find($reviewId)->delete();
    }

    /**
     * Get recent reviews
     */
    public function getRecentReviews(int $days = 7, int $limit = 10)
    {
        return FacultyReview::where('created_at', '>=', now()->subDays($days))
            ->with('faculty', 'researchProject')
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }
}
