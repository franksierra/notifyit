<?php

namespace Tests\Unit\Models;

use App\Models\Project;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTest extends TestCase
{
    private $table = 'users';

    public function test_if_users_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn($this->table, 'id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'name'));
        $this->assertTrue(Schema::hasColumn($this->table, 'username'));
        $this->assertTrue(Schema::hasColumn($this->table, 'email'));
        $this->assertTrue(Schema::hasColumn($this->table, 'email_verified_at'));
        $this->assertTrue(Schema::hasColumn($this->table, 'password'));
        $this->assertTrue(Schema::hasColumn($this->table, 'remember_token'));
        $this->assertTrue(Schema::hasColumn($this->table, 'created_at'));
        $this->assertTrue(Schema::hasColumn($this->table, 'updated_at'));
    }

    public function test_it_can_list_all_of_the_user_projects()
    {
        $times = rand(1, 10);
        $user = $this->user();
        factory(Project::class)->times($times)->create(['user_id' => $user->id]);

        $this->assertCount($times, $user->projects->all());
        $user->projects->each(function ($project) {
            self::assertInstanceOf(Project::class, $project);
        });
    }
}
