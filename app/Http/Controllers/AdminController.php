<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ResearchProject;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show users management page
     */
    public function users(): View
    {
        $users = User::paginate(20);
        return view('admin.users', ['users' => $users]);
    }

    /**
     * Show create user form
     */
    public function createUser(): View
    {
        return view('admin.create', ['roles' => ['student', 'faculty', 'admin']]);
    }

    /**
     * Store a new user
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,faculty,admin',
            'department' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $validated['name'],
            'full_name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'department' => $validated['department'] ?? 'N/A',
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully!');
    }

    /**
     * Toggle user status
     */
    public function toggleUser(User $user): RedirectResponse
    {
        return redirect()->route('admin.users')
            ->with('success', 'User status updated!');
    }

    /**
     * Show dashboard
     */
    public function reports(): View
    {
        $stats = [
            'total_users' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'faculty' => User::where('role', 'faculty')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'total_projects' => ResearchProject::count(),
            'pending_reviews' => ResearchProject::where('status', 'pending')->count(),
            'under_review' => ResearchProject::where('status', 'under_review')->count(),
            'approved_projects' => ResearchProject::where('status', 'approved')->count(),
            'rejected_projects' => ResearchProject::where('status', 'rejected')->count(),
        ];

        $projects = ResearchProject::with('user', 'assignedFaculty')
            ->latest()
            ->paginate(15);

        return view('admin.report', ['stats' => $stats, 'projects' => $projects]);
    }

    /**
     * Export report
     */
    public function exportReport()
    {
        return response()->download(storage_path('app/report.csv'));
    }

    /**
     * Activity log
     */
    public function activityLog(): View
    {
        return view('admin.activity', []);
    }
}
