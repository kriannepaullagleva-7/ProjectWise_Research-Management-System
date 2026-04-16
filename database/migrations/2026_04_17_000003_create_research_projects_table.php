<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('research_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->text('reviewer_feedback')->nullable();
            $table->foreignId('assigned_faculty_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('file_path')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamps();
        });

        Schema::create('faculty_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_project_id')->constrained()->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('users')->onDelete('cascade');
            $table->text('feedback')->nullable();
            $table->enum('recommendation', ['approve', 'reject', 'revise'])->nullable();
            $table->integer('rating')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_reviews');
        Schema::dropIfExists('research_projects');
    }
};
