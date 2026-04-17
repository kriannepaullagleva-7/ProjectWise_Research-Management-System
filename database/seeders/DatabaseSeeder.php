<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ResearchProject;
use App\Models\SavedItem;
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
            'abstract' => 'A comprehensive study on ML in healthcare showing 95% accuracy in diagnosis.',
            'category' => 'Computer Science',
            'field_of_study' => 'Artificial Intelligence',
            'keywords' => 'machine learning, healthcare, diagnosis, deep learning, neural networks',
            'status' => 'approved',
            'view_count' => 0,
            'views_count' => 0,
            'downloads_count' => 0,
            'submission_date' => now(),
            'approval_date' => now(),
        ]);

        ResearchProject::create([
            'user_id' => $student2->id,
            'assigned_faculty_id' => $faculty2->id,
            'title' => 'Genetic Diversity in Endangered Species',
            'description' => 'An investigation into the genetic makeup and diversity of endangered wildlife populations. This project aims to develop conservation strategies based on genetic analysis.',
            'abstract' => 'Analysis of genetic diversity in 50 endangered species across 3 continents.',
            'category' => 'Biology',
            'field_of_study' => 'Conservation Biology',
            'keywords' => 'genetics, endangered species, biodiversity, conservation',
            'status' => 'under_review',
            'view_count' => 0,
            'views_count' => 0,
            'downloads_count' => 0,
            'submission_date' => now(),
        ]);

        ResearchProject::create([
            'user_id' => $student3->id,
            'title' => 'Novel Catalysts for Green Chemistry',
            'description' => 'Development and testing of sustainable catalysts for chemical reactions. This research promotes environmentally friendly manufacturing processes.',
            'abstract' => 'Synthesis and evaluation of 15 novel catalysts for sustainable manufacturing.',
            'category' => 'Chemistry',
            'field_of_study' => 'Green Chemistry',
            'keywords' => 'catalysts, green chemistry, sustainability, manufacturing',
            'status' => 'pending',
            'view_count' => 0,
            'views_count' => 0,
            'downloads_count' => 0,
            'submission_date' => now(),
        ]);

        ResearchProject::create([
            'user_id' => $student1->id,
            'title' => 'Quantum Computing Fundamentals',
            'description' => 'An exploration of quantum computing principles and practical applications. This study examines quantum algorithms and their superiority over classical methods.',
            'abstract' => 'Comparative analysis of quantum vs classical algorithms in various applications.',
            'category' => 'Computer Science',
            'field_of_study' => 'Quantum Computing',
            'keywords' => 'quantum computing, algorithms, quantum mechanics, computing',
            'status' => 'pending',
            'view_count' => 0,
            'views_count' => 0,
            'downloads_count' => 0,
            'submission_date' => now(),
        ]);

        ResearchProject::create([
            'user_id' => $student2->id,
            'assigned_faculty_id' => $faculty2->id,
            'title' => 'Biodiversity in Tropical Rainforests',
            'description' => 'A comprehensive study of species diversity and ecological relationships in tropical rainforest ecosystems.',
            'abstract' => 'Cataloging and analysis of 500+ species in Amazon rainforest.',
            'category' => 'Biology',
            'field_of_study' => 'Ecology',
            'keywords' => 'biodiversity, rainforest, ecology, species, conservation',
            'status' => 'approved',
            'view_count' => 0,
            'views_count' => 0,
            'downloads_count' => 0,
            'submission_date' => now(),
            'approval_date' => now(),
        ]);

        // Get all projects for testing saved items
        $projects = ResearchProject::all();

        // Create saved items - students saving research projects
        SavedItem::create([
            'user_id' => $student1->id,
            'research_project_id' => $projects->where('user_id', '!=', $student1->id)->first()->id ?? $projects[1]->id,
        ]);

        SavedItem::create([
            'user_id' => $student1->id,
            'research_project_id' => $projects->where('user_id', '!=', $student1->id)->skip(1)->first()->id ?? $projects[2]->id,
        ]);

        SavedItem::create([
            'user_id' => $student2->id,
            'research_project_id' => $projects->where('user_id', '!=', $student2->id)->first()->id ?? $projects[0]->id,
        ]);
    }
}
