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
        // Add fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url')->nullable()->after('bio');
        });

        // Add fields to research_projects table
        Schema::table('research_projects', function (Blueprint $table) {
            $table->text('abstract')->nullable()->after('description');
            $table->string('field_of_study')->nullable()->after('category');
            $table->text('keywords')->nullable()->after('field_of_study');
            $table->integer('views_count')->default(0)->after('view_count');
            $table->integer('downloads_count')->default(0)->after('views_count');
            $table->timestamp('submission_date')->nullable()->after('created_at');
            $table->timestamp('approval_date')->nullable()->after('submission_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_url']);
        });

        Schema::table('research_projects', function (Blueprint $table) {
            $table->dropColumn(['abstract', 'field_of_study', 'keywords', 'views_count', 'downloads_count', 'submission_date', 'approval_date']);
        });
    }
};
