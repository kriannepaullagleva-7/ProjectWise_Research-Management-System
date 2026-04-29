<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\ResearchProject;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }

        if ($user->hasRole('faculty')) {
            return $this->facultyDashboard();
        }

        return $this->studentDashboard();
    }

    private function adminDashboard(): View
    {
        $stats = [
            'total_users'    => User::count(),
            'students'       => User::where('role', 'student')->count(),
            'faculty'        => User::where('role', 'faculty')->count(),
            'total_projects' => ResearchProject::count(),
            'approved'       => ResearchProject::where('status', 'approved')->count(),
            'pending_reviews'=> ResearchProject::where('status', 'pending')->count(),
            'under_review'   => ResearchProject::where('status', 'under_review')->count(),
        ];

        // Monthly submissions for last 6 months
        $submissionsChart = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = ResearchProject::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $submissionsChart->put($month->format('M'), $count);
        }

        $statusBreakdown = ResearchProject::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $recentProjects = ResearchProject::with('user')->latest()->take(8)->get();
        $recentUsers    = User::latest()->take(8)->get();

        return view('dashboard.admin', compact(
            'stats', 'submissionsChart', 'statusBreakdown',
            'recentProjects', 'recentUsers'
        ));
    }

    private function facultyDashboard(): View
    {
        $assignedProjects = ResearchProject::where('assigned_faculty_id', auth()->id())
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'pending_reviews'  => ResearchProject::where('status', 'pending')->whereNull('assigned_faculty_id')->count(),
            'under_review'     => ResearchProject::where('assigned_faculty_id', auth()->id())->where('status', 'under_review')->count(),
            'reviewed_total'   => ResearchProject::where('assigned_faculty_id', auth()->id())->whereIn('status', ['approved','rejected'])->count(),
            'approved'         => ResearchProject::where('assigned_faculty_id', auth()->id())->where('status', 'approved')->count(),
            'total_submissions'=> ResearchProject::where('assigned_faculty_id', auth()->id())->count(),
        ];

        return view('dashboard.faculty', compact('stats', 'assignedProjects'));
    }

    private function studentDashboard(): View
    {
        $myProjects = ResearchProject::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $allProjects = ResearchProject::where('user_id', auth()->id())->get();

        $stats = [
            'total_projects' => $allProjects->count(),
            'pending'        => $allProjects->where('status', 'pending')->count(),
            'under_review'   => $allProjects->where('status', 'under_review')->count(),
            'approved'       => $allProjects->where('status', 'approved')->count(),
            'rejected'       => $allProjects->where('status', 'rejected')->count(),
        ];

        return view('dashboard.student', compact('stats', 'myProjects'));
    }
}
