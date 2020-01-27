<?php

namespace Tests\Unit\Models;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PushDeviceTest extends TestCase
{
    private $table = 'push_devices';

    public function test_if_projects_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn($this->table, 'id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'credential_id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'platform'));
        $this->assertTrue(Schema::hasColumn($this->table, 'uuid'));
        $this->assertTrue(Schema::hasColumn($this->table, 'identity'));
        $this->assertTrue(Schema::hasColumn($this->table, 'regid'));
        $this->assertTrue(Schema::hasColumn($this->table, 'created_at'));
        $this->assertTrue(Schema::hasColumn($this->table, 'updated_at'));
    }
}
