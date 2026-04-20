<?php

namespace App\Services;

use App\Models\ResearchProject;
use Illuminate\Pagination\Paginator;

/**
 * Research Project Service - Business logic for research projects
 */
class ResearchProjectService
{
    /**
     * Get all approved projects with pagination
     */
    public function getApprovedProjects(int $perPage = 12): Paginator
    {
        return ResearchProject::where('status', 'approved')
            ->with('user', 'assignedFaculty')
            ->latest('submission_date')
            ->paginate($perPage);
    }

    /**
     * Get user's projects with their status
     */
    public function getUserProjects(int $userId, int $perPage = 10): Paginator
    {
        return ResearchProject::where('user_id', $userId)
            ->with('assignedFaculty', 'reviews')
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get projects by category
     */
    public function getProjectsByCategory(string $category, int $perPage = 12): Paginator
    {
        return ResearchProject::where('status', 'approved')
            ->where('category', $category)
            ->with('user', 'assignedFaculty')
            ->latest('submission_date')
            ->paginate($perPage);
    }

    /**
     * Get projects by field of study
     */
    public function getProjectsByField(string $field, int $perPage = 12): Paginator
    {
        return ResearchProject::where('status', 'approved')
            ->where('field_of_study', $field)
            ->with('user', 'assignedFaculty')
            ->latest('submission_date')
            ->paginate($perPage);
    }

    /**
     * Get projects pending review
     */
    public function getPendingProjects(int $perPage = 15): Paginator
    {
        return ResearchProject::where('status', 'pending')
            ->orWhere('status', 'under_review')
            ->with('user', 'reviews')
            ->latest('submission_date')
            ->paginate($perPage);
    }

    /**
     * Get most viewed projects
     */
    public function getMostViewedProjects(int $limit = 10)
    {
        return ResearchProject::where('status', 'approved')
            ->orderByDesc('views_count')
            ->with('user')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recently published projects
     */
    public function getRecentProjects(int $days = 30, int $limit = 10)
    {
        return ResearchProject::where('status', 'approved')
            ->where('approval_date', '>=', now()->subDays($days))
            ->orderByDesc('approval_date')
            ->with('user')
            ->limit($limit)
            ->get();
    }

    /**
     * Search projects by keywords
     */
    public function searchProjects(string $query, int $perPage = 12): Paginator
    {
        return ResearchProject::where('status', 'approved')
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('keywords', 'LIKE', "%{$query}%")
                  ->orWhere('abstract', 'LIKE', "%{$query}%");
            })
            ->with('user', 'assignedFaculty')
            ->latest('submission_date')
            ->paginate($perPage);
    }

    /**
     * Get all unique categories
     */
    public function getAllCategories()
    {
        return ResearchProject::where('status', 'approved')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();
    }

    /**
     * Get all unique fields of study
     */
    public function getAllFields()
    {
        return ResearchProject::where('status', 'approved')
            ->distinct()
            ->pluck('field_of_study')
            ->filter()
            ->sort()
            ->values();
    }

    /**
     * Get project statistics
     */
    public function getProjectStatistics()
    {
        return [
            'total_projects' => ResearchProject::count(),
            'approved_projects' => ResearchProject::where('status', 'approved')->count(),
            'pending_projects' => ResearchProject::where('status', 'pending')->count(),
            'under_review_projects' => ResearchProject::where('status', 'under_review')->count(),
            'rejected_projects' => ResearchProject::where('status', 'rejected')->count(),
            'total_views' => ResearchProject::sum('views_count'),
            'total_downloads' => ResearchProject::sum('downloads_count'),
            'average_views' => (int) ResearchProject::avg('views_count'),
        ];
    }

    /**
     * Get trending projects
     */
    public function getTrendingProjects(int $limit = 5)
    {
        return ResearchProject::where('status', 'approved')
            ->orderByDesc('views_count')
            ->orderByDesc('downloads_count')
            ->with('user')
            ->limit($limit)
            ->get();
    }

    /**
     * Increment project views
     */
    public function incrementViews(ResearchProject $project): void
    {
        $project->increment('view_count');
        $project->increment('views_count');
    }

    /**
     * Increment project downloads
     */
    public function incrementDownloads(ResearchProject $project): void
    {
        $project->increment('downloads_count');
    }
}
