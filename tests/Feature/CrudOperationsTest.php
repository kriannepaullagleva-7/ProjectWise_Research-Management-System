<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ResearchProject;
use App\Models\SavedItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudOperationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test User CRUD operations
     */
    public function test_user_crud_operations()
    {
        // CREATE
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'full_name' => 'Test User Full',
            'department' => 'Test Department',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'student',
        ]);

        // READ
        $retrieved = User::find($user->id);
        $this->assertEquals('Test User', $retrieved->name);

        // UPDATE
        $user->update(['department' => 'New Department']);
        $this->assertEquals('New Department', $user->fresh()->department);

        // DELETE
        $user->delete();
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    /**
     * Test ResearchProject CRUD operations
     */
    public function test_research_project_crud_operations()
    {
        $user = User::factory()->create();

        // CREATE
        $project = ResearchProject::create([
            'user_id' => $user->id,
            'title' => 'Test Research Project',
            'description' => 'This is a test description for research project',
            'abstract' => 'This is an abstract',
            'category' => 'Computer Science',
            'field_of_study' => 'AI',
            'keywords' => 'test, ai, research',
            'status' => 'pending',
            'view_count' => 0,
            'views_count' => 0,
            'downloads_count' => 0,
        ]);

        $this->assertDatabaseHas('research_projects', [
            'title' => 'Test Research Project',
            'status' => 'pending',
        ]);

        // READ
        $retrieved = ResearchProject::find($project->id);
        $this->assertEquals('Test Research Project', $retrieved->title);

        // UPDATE
        $project->update(['status' => 'approved']);
        $this->assertEquals('approved', $project->fresh()->status);

        // Test incrementing counters
        $project->increment('view_count');
        $project->increment('views_count');
        $project->increment('downloads_count');

        $refreshed = $project->fresh();
        $this->assertEquals(1, $refreshed->view_count);
        $this->assertEquals(1, $refreshed->views_count);
        $this->assertEquals(1, $refreshed->downloads_count);

        // DELETE
        $project->delete();
        $this->assertDatabaseMissing('research_projects', [
            'title' => 'Test Research Project',
        ]);
    }

    /**
     * Test SavedItem CRUD operations
     */
    public function test_saved_item_crud_operations()
    {
        $user = User::factory()->create();
        $student = User::factory()->create(['role' => 'student']);
        $project = ResearchProject::factory()->create(['user_id' => $user->id]);

        // CREATE
        $saved = SavedItem::create([
            'user_id' => $student->id,
            'research_project_id' => $project->id,
        ]);

        $this->assertDatabaseHas('saved_items', [
            'user_id' => $student->id,
            'research_project_id' => $project->id,
        ]);

        // READ
        $retrieved = SavedItem::where('user_id', $student->id)->first();
        $this->assertNotNull($retrieved);
        $this->assertEquals($project->id, $retrieved->research_project_id);

        // DELETE
        $saved->delete();
        $this->assertDatabaseMissing('saved_items', [
            'user_id' => $student->id,
            'research_project_id' => $project->id,
        ]);
    }

    /**
     * Test relationships
     */
    public function test_model_relationships()
    {
        $user = User::factory()->create();
        $project = ResearchProject::factory()->create(['user_id' => $user->id]);
        $student = User::factory()->create(['role' => 'student']);
        $saved = SavedItem::create([
            'user_id' => $student->id,
            'research_project_id' => $project->id,
        ]);

        // Test User -> ResearchProject relationship
        $this->assertTrue($user->researchProjects()->exists());
        $this->assertEquals($project->id, $user->researchProjects()->first()->id);

        // Test ResearchProject -> User relationship
        $this->assertEquals($user->id, $project->user()->first()->id);

        // Test SavedItem relationships
        $this->assertEquals($student->id, $saved->user()->first()->id);
        $this->assertEquals($project->id, $saved->researchProject()->first()->id);
    }
}
