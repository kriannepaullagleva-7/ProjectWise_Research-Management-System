<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;

/**
 * User Service - Business logic for user management
 */
class UserService
{
    /**
     * Get all users with pagination
     */
    public function getAllUsers(int $perPage = 20): Paginator
    {
        return User::latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $role, int $perPage = 20): Paginator
    {
        return User::where('role', $role)
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get users by department
     */
    public function getUsersByDepartment(string $department, int $perPage = 20): Paginator
    {
        return User::where('department', $department)
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get faculty members
     */
    public function getFacultyMembers(int $perPage = 20): Paginator
    {
        return User::where('role', 'faculty')
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get student users
     */
    public function getStudents(int $perPage = 20): Paginator
    {
        return User::where('role', 'student')
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get admin users
     */
    public function getAdmins(int $perPage = 20): Paginator
    {
        return User::where('role', 'admin')
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Search users
     */
    public function searchUsers(string $query, int $perPage = 20): Paginator
    {
        return User::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('full_name', 'LIKE', "%{$query}%")
              ->orWhere('department', 'LIKE', "%{$query}%");
        })
        ->latest('created_at')
        ->paginate($perPage);
    }

    /**
     * Get user statistics
     */
    public function getUserStatistics()
    {
        return [
            'total_users' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'faculty' => User::where('role', 'faculty')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
        ];
    }

    /**
     * Get all unique departments
     */
    public function getAllDepartments()
    {
        return User::distinct()
            ->pluck('department')
            ->filter()
            ->sort()
            ->values();
    }

    /**
     * Create user with validation
     */
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'student',
            'full_name' => $data['full_name'] ?? $data['name'],
            'department' => $data['department'] ?? 'Not specified',
        ]);
    }

    /**
     * Update user profile
     */
    public function updateUserProfile(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'] ?? $user->name,
            'full_name' => $data['full_name'] ?? $user->full_name,
            'department' => $data['department'] ?? $user->department,
            'bio' => $data['bio'] ?? $user->bio,
            'avatar_url' => $data['avatar_url'] ?? $user->avatar_url,
        ]);

        return $user;
    }

    /**
     * Update user password
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Verify user email
     */
    public function verifyEmail(User $user): void
    {
        $user->update([
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Get user with related data
     */
    public function getUserWithRelations(int $userId)
    {
        return User::with([
            'researchProjects',
            'assignedProjects',
            'facultyReviews',
            'savedItems',
        ])->find($userId);
    }

    /**
     * Get faculty with assigned projects
     */
    public function getFacultyWithProjects(int $facultyId)
    {
        return User::with([
            'assignedProjects' => function ($query) {
                $query->orderByDesc('created_at');
            },
        ])->find($facultyId);
    }

    /**
     * Get student with projects
     */
    public function getStudentWithProjects(int $studentId)
    {
        return User::with([
            'researchProjects' => function ($query) {
                $query->orderByDesc('created_at');
            },
        ])->find($studentId);
    }
}
