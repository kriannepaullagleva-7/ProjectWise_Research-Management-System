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
        'view_count',
        'views_count',
        'downloads_count',
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
     * Get the latest review for this project
     */
    public function latestReview()
    {
        return $this->reviews()->latest()->first();
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

    /**
     * Get the original file name from file path
     */
    public function getFileOriginalNameAttribute(): string
    {
        if (!$this->file_path) {
            return '';
        }
        return basename($this->file_path);
    }

    /**
     * Get the formatted file size
     */
    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_path) {
            return '';
        }
        
        $filePath = storage_path('app/public/' . $this->file_path);
        if (!file_exists($filePath)) {
            return '';
        }
        
        $size = filesize($filePath);
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $size / 1024;
        $unitIndex = 1;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 2) . ' ' . $units[$unitIndex];
    }
}
