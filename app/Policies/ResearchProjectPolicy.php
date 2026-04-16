<?php

namespace App\Policies;

use App\Models\ResearchProject;
use App\Models\User;

class ResearchProjectPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ResearchProject $researchProject): bool
    {
        return true; // Public viewing
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Anyone can create
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ResearchProject $researchProject): bool
    {
        return $user->id === $researchProject->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ResearchProject $researchProject): bool
    {
        return $user->id === $researchProject->user_id;
    }

    /**
     * Determine whether the user can review the project
     */
    public function review(User $user, ResearchProject $researchProject): bool
    {
        return $user->isFacultyOrAdmin();
    }

    /**
     * Determine whether user can view review page
     */
    public function viewReview(User $user, ResearchProject $researchProject): bool
    {
        return $user->isFacultyOrAdmin();
    }
}
