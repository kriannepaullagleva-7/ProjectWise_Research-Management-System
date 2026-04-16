<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ResendVerificationEmail;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResearchProjectController;
use App\Http\Controllers\SavedItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\AdminController;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Email verification (Laravel built-in)
Route::get('/email/verify', fn() => view('auth.verify-email'))->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', EmailVerificationController::class)->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/resend', ResendVerificationEmail::class)->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Research
    Route::get('/research', [ResearchProjectController::class, 'index'])->name('research.index');
    Route::get('/research/my', [ResearchProjectController::class, 'myProjects'])->name('research.my-projects');
    Route::get('/research/create', [ResearchProjectController::class, 'create'])->name('research.create');
    Route::post('/research', [ResearchProjectController::class, 'store'])->name('research.store');
    Route::get('/research/{researchProject}', [ResearchProjectController::class, 'show'])->name('research.show');
    Route::get('/research/{researchProject}/edit', [ResearchProjectController::class, 'edit'])->name('research.edit');
    Route::put('/research/{researchProject}', [ResearchProjectController::class, 'update'])->name('research.update');
    Route::delete('/research/{researchProject}', [ResearchProjectController::class, 'destroy'])->name('research.destroy');
    Route::get('/research/{researchProject}/download', [ResearchProjectController::class, 'download'])->name('research.download');

    // Saved library
    Route::get('/library', [SavedItemController::class, 'index'])->name('saved.index');
    Route::post('/library/{researchProject}/toggle', [SavedItemController::class, 'toggle'])->name('saved.toggle');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Faculty
    Route::middleware('role:faculty,admin')->group(function () {
        Route::get('/faculty/explorer', [FacultyController::class, 'explorer'])->name('faculty.explorer');
        Route::get('/faculty/review/{researchProject}', [FacultyController::class, 'review'])->name('faculty.review');
        Route::post('/faculty/review/{researchProject}', [FacultyController::class, 'submitFeedback'])->name('faculty.feedback');
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [AdminController::class, 'exportReport'])->name('reports.export');
        Route::get('/activity', [AdminController::class, 'activityLog'])->name('activity');
    });
});