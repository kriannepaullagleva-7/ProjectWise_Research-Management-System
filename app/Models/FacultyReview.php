<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacultyReview extends Model
{
    protected $fillable = [
        'research_project_id',
        'faculty_id',
        'feedback',
        'recommendation',
        'rating',
    ];

    /**
     * Get the research project
     */
    public function researchProject(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class);
    }

    /**
     * Get the faculty member who reviewed
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
