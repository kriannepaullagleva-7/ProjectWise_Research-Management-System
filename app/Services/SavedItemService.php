<?php

namespace App\Services;

use App\Models\SavedItem;
use App\Models\ResearchProject;
use Illuminate\Pagination\Paginator;

/**
 * Saved Items Service - Business logic for managing user's saved projects
 */
class SavedItemService
{
    /**
     * Get user's saved items with pagination
     */
    public function getUserSavedItems(int $userId, int $perPage = 12): Paginator
    {
        return SavedItem::where('user_id', $userId)
            ->with('researchProject', 'researchProject.user')
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Check if user has saved a project
     */
    public function isSaved(int $userId, int $projectId): bool
    {
        return SavedItem::where('user_id', $userId)
            ->where('research_project_id', $projectId)
            ->exists();
    }

    /**
     * Save a project
     */
    public function saveProject(int $userId, int $projectId): SavedItem
    {
        return SavedItem::firstOrCreate([
            'user_id' => $userId,
            'research_project_id' => $projectId,
        ]);
    }

    /**
     * Remove saved project
     */
    public function removeSaved(int $userId, int $projectId): bool
    {
        return SavedItem::where('user_id', $userId)
            ->where('research_project_id', $projectId)
            ->delete() > 0;
    }

    /**
     * Toggle save status
     */
    public function toggleSaved(int $userId, int $projectId): array
    {
        if ($this->isSaved($userId, $projectId)) {
            $this->removeSaved($userId, $projectId);
            return [
                'saved' => false,
                'message' => 'Project removed from library',
            ];
        } else {
            $this->saveProject($userId, $projectId);
            return [
                'saved' => true,
                'message' => 'Project added to library',
            ];
        }
    }

    /**
     * Get count of saved items for user
     */
    public function getSavedCount(int $userId): int
    {
        return SavedItem::where('user_id', $userId)->count();
    }

    /**
     * Get most saved projects
     */
    public function getMostSavedProjects(int $limit = 10)
    {
        return ResearchProject::withCount('savedItems')
            ->where('status', 'approved')
            ->orderByDesc('saved_items_count')
            ->with('user')
            ->limit($limit)
            ->get();
    }

    /**
     * Get saved projects count by category
     */
    public function getSavedCountByCategory(int $userId)
    {
        return SavedItem::where('user_id', $userId)
            ->join('research_projects', 'saved_items.research_project_id', '=', 'research_projects.id')
            ->select('research_projects.category')
            ->groupBy('research_projects.category')
            ->selectRaw('count(*) as count')
            ->get();
    }

    /**
     * Clear all saved items for user
     */
    public function clearAllSaved(int $userId): int
    {
        return SavedItem::where('user_id', $userId)->delete();
    }

    /**
     * Export saved projects
     */
    public function exportSavedProjects(int $userId, string $format = 'json')
    {
        $items = SavedItem::where('user_id', $userId)
            ->with('researchProject')
            ->get();

        return $format === 'json' 
            ? json_encode($items)
            : $items;
    }
}
