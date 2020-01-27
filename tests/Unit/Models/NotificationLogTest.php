<?php

namespace Tests\Unit\Models;

use App\Models\EmailNotificationLog;
use App\Models\Notification\NotificationLog;
use App\Models\PushNotificationLog;
use App\Models\SmsNotificationLog;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NotificationLogTest extends TestCase
{
    private $table = 'notification_logs';

    public function test_if_projects_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn($this->table, 'id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'credential_id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'type'));
        $this->assertTrue(Schema::hasColumn($this->table, 'job_id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'status'));
        $this->assertTrue(Schema::hasColumn($this->table, 'payload'));
        $this->assertTrue(Schema::hasColumn($this->table, 'exception'));
        $this->assertTrue(Schema::hasColumn($this->table, 'additional'));
        $this->assertTrue(Schema::hasColumn($this->table, 'created_at'));
        $this->assertTrue(Schema::hasColumn($this->table, 'updated_at'));
    }

    public function test_it_can_list_all_of_the_settings()
    {
        $times = rand(1, 5);
        $pushLog = factory(PushNotificationLog::class, $times)->create();
        $emailLog = factory(EmailNotificationLog::class, $times)->create();
        $smsLog = factory(SmsNotificationLog::class, $times)->create();

        $this->assertCount($times, $pushLog->all());
        $this->assertCount($times, $emailLog->all());
        $this->assertCount($times, $smsLog->all());

        $pushLog->each(function (NotificationLog $log) {
            self::assertInstanceOf(PushNotificationLog::class, $log);
        });
        $emailLog->each(function (NotificationLog $log) {
            self::assertInstanceOf(EmailNotificationLog::class, $log);
        });
        $smsLog->each(function (NotificationLog $log) {
            self::assertInstanceOf(SmsNotificationLog::class, $log);
        });
    }
}
