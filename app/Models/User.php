<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'full_name', 'department', 'bio', 'avatar_url', 'student_id', 'email_verified_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get research projects created by this user
     */
    public function researchProjects(): HasMany
    {
        return $this->hasMany(ResearchProject::class);
    }

    /**
     * Get research projects assigned to this faculty member
     */
    public function assignedProjects(): HasMany
    {
        return $this->hasMany(ResearchProject::class, 'assigned_faculty_id');
    }

    /**
     * Get reviews written by this faculty member
     */
    public function facultyReviews(): HasMany
    {
        return $this->hasMany(FacultyReview::class, 'faculty_id');
    }

    /**
     * Get saved research projects
     */
    public function savedItems()
    {
        return $this->hasManyThrough(
            ResearchProject::class,
            'App\Models\SavedItem',
            'user_id',
            'id',
            'id',
            'research_project_id'
        );
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles): bool
    {
        return in_array($this->role, (array)$roles);
    }

    /**
     * Check if user is faculty or admin
     */
    public function isFacultyOrAdmin(): bool
    {
        return in_array($this->role, ['faculty', 'admin']);
    }

    /**
     * Get full name or name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?? $this->name;
    }
}
