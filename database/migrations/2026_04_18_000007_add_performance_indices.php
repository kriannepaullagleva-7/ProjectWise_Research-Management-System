<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Optimize database schema with proper indices, constraints, and design improvements
     */
    public function up(): void
    {
        // Add indices to users table for better query performance
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');                    // Index for role-based queries
            $table->index('department');              // Index for department filtering
            $table->index('created_at');              // Index for date-based queries
        });

        // Add indices to research_projects table for better query performance
        Schema::table('research_projects', function (Blueprint $table) {
            $table->index(['user_id', 'status']);     // Composite index for owner status queries
            $table->index('status');                   // Index for status filtering
            $table->index('assigned_faculty_id');     // Index for faculty assignment queries
            $table->index('category');                 // Index for category filtering
            $table->index('created_at');              // Index for date-based queries
            $table->index('submission_date');         // Index for submission date queries
        });

        // Add indices to faculty_reviews table for better query performance
        Schema::table('faculty_reviews', function (Blueprint $table) {
            $table->index('research_project_id');     // Index for project reviews
            $table->index('faculty_id');              // Index for faculty reviews
            $table->index(['research_project_id', 'faculty_id']); // Composite index
            $table->index('created_at');              // Index for date-based queries
        });

        // Add indices to saved_items table for better query performance
        Schema::table('saved_items', function (Blueprint $table) {
            $table->index('user_id');                 // Index for user's saved items
            $table->index('created_at');              // Index for date-based queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indices from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['department']);
            $table->dropIndex(['created_at']);
        });

        // Drop indices from research_projects table
        Schema::table('research_projects', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['status']);
            $table->dropIndex(['assigned_faculty_id']);
            $table->dropIndex(['category']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['submission_date']);
        });

        // Drop indices from faculty_reviews table
        Schema::table('faculty_reviews', function (Blueprint $table) {
            $table->dropIndex(['research_project_id']);
            $table->dropIndex(['faculty_id']);
            $table->dropIndex(['research_project_id', 'faculty_id']);
            $table->dropIndex(['created_at']);
        });

        // Drop indices from saved_items table
        Schema::table('saved_items', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });
    }
};
