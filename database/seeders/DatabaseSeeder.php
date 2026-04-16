<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ResearchProject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'full_name' => 'Administrator',
            'department' => 'Administration',
            'email_verified_at' => now(),
        ]);

        $faculty1 = User::create([
            'name' => 'Dr. John Smith',
            'email' => 'john.smith@example.com',
            'password' => Hash::make('password'),
            'role' => 'faculty',
            'full_name' => 'Dr. John Smith',
            'department' => 'Computer Science',
            'email_verified_at' => now(),
        ]);

        $faculty2 = User::create([
            'name' => 'Dr. Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => Hash::make('password'),
            'role' => 'faculty',
            'full_name' => 'Dr. Jane Doe',
            'department' => 'Biology',
            'email_verified_at' => now(),
        ]);

        $student1 = User::create([
            'name' => 'Alice Johnson',
            'email' => 'alice.johnson@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'full_name' => 'Alice Johnson',
            'department' => 'Computer Science',
            'email_verified_at' => now(),
        ]);

        $student2 = User::create([
            'name' => 'Bob Williams',
            'email' => 'bob.williams@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'full_name' => 'Bob Williams',
            'department' => 'Biology',
            'email_verified_at' => now(),
        ]);

        $student3 = User::create([
            'name' => 'Carol Davis',
            'email' => 'carol.davis@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'full_name' => 'Carol Davis',
            'department' => 'Chemistry',
            'email_verified_at' => now(),
        ]);

        // Create sample research projects
        ResearchProject::create([
            'user_id' => $student1->id,
            'assigned_faculty_id' => $faculty1->id,
            'title' => 'Machine Learning Applications in Healthcare',
            'description' => 'This research explores the use of machine learning algorithms in medical diagnosis and treatment planning. The study focuses on developing predictive models for early disease detection.',
            'category' => 'Computer Science',
            'status' => 'approved',
        ]);

        ResearchProject::create([
            'user_id' => $student2->id,
            'assigned_faculty_id' => $faculty2->id,
            'title' => 'Genetic Diversity in Endangered Species',
            'description' => 'An investigation into the genetic makeup and diversity of endangered wildlife populations. This project aims to develop conservation strategies based on genetic analysis.',
            'category' => 'Biology',
            'status' => 'under_review',
        ]);

        ResearchProject::create([
            'user_id' => $student3->id,
            'title' => 'Novel Catalysts for Green Chemistry',
            'description' => 'Development and testing of sustainable catalysts for chemical reactions. This research promotes environmentally friendly manufacturing processes.',
            'category' => 'Chemistry',
            'status' => 'pending',
        ]);

        ResearchProject::create([
            'user_id' => $student1->id,
            'title' => 'Quantum Computing Fundamentals',
            'description' => 'An exploration of quantum computing principles and practical applications. This study examines quantum algorithms and their superiority over classical methods.',
            'category' => 'Computer Science',
            'status' => 'pending',
        ]);

        ResearchProject::create([
            'user_id' => $student2->id,
            'assigned_faculty_id' => $faculty2->id,
            'title' => 'Biodiversity in Tropical Rainforests',
            'description' => 'A comprehensive study of species diversity and ecological relationships in tropical rainforest ecosystems.',
            'category' => 'Biology',
            'status' => 'approved',
        ]);
    }
}
