<?php

namespace Tests\Unit\Models;

use App\Models\Credential;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    private $table = 'projects';

    public function test_if_projects_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn($this->table, 'id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'user_id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'name'));
        $this->assertTrue(Schema::hasColumn($this->table, 'created_at'));
        $this->assertTrue(Schema::hasColumn($this->table, 'updated_at'));
    }

    public function test_that_projects_belongs_to_a_user()
    {
        $times = rand(1, 5);
        factory(Project::class)->times($times)->create();

        $project = Project::all();

        $this->assertCount($times, $project);
        $project->each(function (Project $project) {
            $this->assertInstanceOf(User::class, $project->user);
        });
    }

    public function test_that_a_project_can_have_many_credentials()
    {
        $times = rand(1, 5);
        $project = factory(Project::class)->create();
        factory(Credential::class, $times)->create([
            'project_id' => $project->id
        ]);

        $this->assertCount($times, $project->credentials->all());
        $project->credentials->each(function ($credential) {
            $this->assertInstanceOf(Credential::class, $credential);
        });
    }

}
