<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ResearchProject;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function users(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('full_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->latest()->paginate(20)->appends($request->query());

        return view('admin.users', ['users' => $users]);
    }

    public function createUser(): View
    {
        return view('admin.create', ['roles' => ['student', 'faculty', 'admin']]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => 'required|in:student,faculty,admin',
            'department' => 'nullable|string|max:255',
        ]);

        User::create([
            'name'              => $validated['name'],
            'full_name'         => $validated['name'],
            'email'             => $validated['email'],
            'password'          => bcrypt($validated['password']),
            'role'              => $validated['role'],
            'department'        => $validated['department'] ?? null,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully.');
    }

    public function toggleUser(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot modify your own account.');
        }

        // Use email_verified_at as active/inactive flag
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $msg = 'User account deactivated.';
        } else {
            $user->update(['email_verified_at' => now()]);
            $msg = 'User account activated.';
        }

        return redirect()->route('admin.users')->with('success', $msg);
    }

    public function deleteUser(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->researchProjects()->delete();
        $user->facultyReviews()->delete();
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }

    public function reports(): View
    {
        $stats = [
            'total_users'      => User::count(),
            'students'         => User::where('role', 'student')->count(),
            'faculty'          => User::where('role', 'faculty')->count(),
            'admins'           => User::where('role', 'admin')->count(),
            'total_projects'   => ResearchProject::count(),
            'pending_reviews'  => ResearchProject::where('status', 'pending')->count(),
            'under_review'     => ResearchProject::where('status', 'under_review')->count(),
            'approved'         => ResearchProject::where('status', 'approved')->count(),
            'rejected_projects'=> ResearchProject::where('status', 'rejected')->count(),
            'total_downloads'  => (int) ResearchProject::sum('downloads_count'),
            'total_views'      => (int) ResearchProject::sum('views_count'),
        ];

        $topProjects = ResearchProject::with('user')
            ->orderBy('views_count', 'desc')
            ->limit(10)
            ->get();

        $categoryBreakdown = ResearchProject::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        $monthlySubmissions = ResearchProject::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                $months = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                return ['month' => $months[$item->month] ?? '?', 'count' => $item->count];
            });

        $projects = ResearchProject::with('user', 'assignedFaculty')
            ->latest()
            ->paginate(15);

        return view('admin.report', [
            'stats'              => $stats,
            'projects'           => $projects,
            'topProjects'        => $topProjects,
            'categoryBreakdown'  => $categoryBreakdown,
            'monthlySubmissions' => collect($monthlySubmissions),
        ]);
    }

    public function exportReport()
    {
        // Generate a simple CSV on the fly
        $projects = ResearchProject::with('user')->get();

        $csv = "Title,Author,Category,Status,Views,Downloads,Submitted\n";
        foreach ($projects as $p) {
            $csv .= implode(',', [
                '"' . str_replace('"', '""', $p->title) . '"',
                '"' . str_replace('"', '""', $p->user->full_name ?? $p->user->name) . '"',
                '"' . $p->category . '"',
                $p->status,
                $p->views_count,
                $p->downloads_count,
                $p->created_at->format('Y-m-d'),
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="projects-report-' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function activityLog(): View
    {
        $recentUsers = User::latest()->take(20)->get();
        $recentProjects = ResearchProject::with('user')->latest()->take(20)->get();

        return view('admin.activity', [
            'recentUsers'    => $recentUsers,
            'recentProjects' => $recentProjects,
        ]);
    }
}
