<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResearchProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'abstract',
        'category',
        'field_of_study',
        'keywords',
        'status',
        'reviewer_feedback',
        'assigned_faculty_id',
        'file_path',
        'submission_date',
        'approval_date',
    ];

    protected $appends = ['status_label'];

    /**
     * Get the user that owns this project
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assigned faculty member
     */
    public function assignedFaculty(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_faculty_id');
    }

    /**
     * Get all reviews for this project
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(FacultyReview::class);
    }

    /**
     * Get human-readable status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending Review',
            'under_review' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get the full name of the user
     */
    public function getUserFullNameAttribute(): string
    {
        return $this->user->full_name ?? $this->user->name;
    }
}
