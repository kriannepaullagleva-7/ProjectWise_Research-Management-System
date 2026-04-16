<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\ResearchProject;

class DashboardController extends Controller
{
    /**
     * Show the dashboard based on user role
     */
    public function index(): View
    {
        $user = auth()->user();

        if ($user->hasRole('faculty') || $user->hasRole('admin')) {
            return $this->facultyDashboard();
        }

        return $this->studentDashboard();
    }

    /**
     * Faculty dashboard with stats and pending reviews
     */
    private function facultyDashboard(): View
    {
        $assignedProjects = ResearchProject::where('assigned_faculty_id', auth()->id())
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        // Calculate statistics
        $stats = [
            'pending_reviews' => ResearchProject::where('status', 'pending')
                ->whereNull('assigned_faculty_id')
                ->count(),
            'under_review' => ResearchProject::where('assigned_faculty_id', auth()->id())
                ->where('status', 'under_review')
                ->count(),
            'reviewed_total' => $assignedProjects->filter(fn($p) => 
                in_array($p->status, ['approved', 'rejected', 'under_review'])
            )->count(),
            'approved' => ResearchProject::where('assigned_faculty_id', auth()->id())
                ->where('status', 'approved')
                ->count(),
            'total_submissions' => ResearchProject::where('assigned_faculty_id', auth()->id())->count(),
        ];

        return view('dashboard.faculty', [
            'stats' => $stats,
            'assignedProjects' => $assignedProjects,
        ]);
    }

    /**
     * Student dashboard
     */
    private function studentDashboard(): View
    {
        $myProjects = ResearchProject::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_projects' => $myProjects->count(),
            'pending' => $myProjects->where('status', 'pending')->count(),
            'under_review' => $myProjects->where('status', 'under_review')->count(),
            'approved' => $myProjects->where('status', 'approved')->count(),
            'rejected' => $myProjects->where('status', 'rejected')->count(),
        ];

        return view('dashboard.student', [
            'stats' => $stats,
            'myProjects' => $myProjects,
        ]);
    }
}
