<?php

namespace Tests\Unit\Models;

use App\Models\EmailNotificationSetting;
use App\Models\Notification\NotificationSetting;
use App\Models\PushNotificationSetting;
use App\Models\SmsNotificationSetting;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NotificationSettingTest extends TestCase
{
    private $table = 'notification_settings';

    public function test_if_projects_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn($this->table, 'id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'credential_id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'type'));
        $this->assertTrue(Schema::hasColumn($this->table, 'driver'));
        $this->assertTrue(Schema::hasColumn($this->table, 'config'));
        $this->assertTrue(Schema::hasColumn($this->table, 'created_at'));
        $this->assertTrue(Schema::hasColumn($this->table, 'updated_at'));
    }

    public function test_it_can_list_all_of_the_settings()
    {
        $times = rand(1, 5);
        $pushSettings = factory(PushNotificationSetting::class, $times)->create();
        $emailSettings = factory(EmailNotificationSetting::class, $times)->create();
        $smsSettings = factory(SmsNotificationSetting::class, $times)->create();

        $this->assertCount($times, $pushSettings->all());
        $this->assertCount($times, $emailSettings->all());
        $this->assertCount($times, $smsSettings->all());

        $pushSettings->each(function (NotificationSetting $setting) {
            self::assertInstanceOf(PushNotificationSetting::class, $setting);
        });
        $emailSettings->each(function (NotificationSetting $setting) {
            self::assertInstanceOf(EmailNotificationSetting::class, $setting);
        });
        $smsSettings->each(function (NotificationSetting $setting) {
            self::assertInstanceOf(SmsNotificationSetting::class, $setting);
        });
    }
}
