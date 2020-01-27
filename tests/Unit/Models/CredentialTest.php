<?php

namespace Tests\Unit\Models;

use App\Models\Credential;
use App\Models\EmailNotificationLog;
use App\Models\EmailNotificationSetting;
use App\Models\Notification\NotificationLog;
use App\Models\Notification\NotificationSetting;
use App\Models\Project;
use App\Models\PushNotificationLog;
use App\Models\PushNotificationSetting;
use App\Models\SmsNotificationLog;
use App\Models\SmsNotificationSetting;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CredentialTest extends TestCase
{
    private $table = 'credentials';

    public function test_if_projects_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn($this->table, 'id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'project_id'));
        $this->assertTrue(Schema::hasColumn($this->table, 'production'));
        $this->assertTrue(Schema::hasColumn($this->table, 'prefix'));
        $this->assertTrue(Schema::hasColumn($this->table, 'prefix_value'));
        $this->assertTrue(Schema::hasColumn($this->table, 'api_key'));
        $this->assertTrue(Schema::hasColumn($this->table, 'created_at'));
        $this->assertTrue(Schema::hasColumn($this->table, 'updated_at'));
    }

    public function test_if_credentials_belongs_to_a_project()
    {
        $times = rand(1, 5);
        $credentials = factory(Credential::class, $times)->create();

        $credentials->each(function (Credential $credential) {
            $this->assertInstanceOf(Project::class, $credential->project);
        });
    }

    public function test_that_a_credential_has_many_notification_settings()
    {
        /** @var Credential $credential */
        $credential = factory(Credential::class)->create();
        $times = rand(1, 5);
        factory(EmailNotificationSetting::class, $times)->create([
            'credential_id' => $credential->id
        ]);
        factory(PushNotificationSetting::class, $times)->create([
            'credential_id' => $credential->id
        ]);
        factory(SmsNotificationSetting::class, $times)->create([
            'credential_id' => $credential->id
        ]);

        $credential->emailSetting()->each(function (NotificationSetting $setting) {
            $this->assertInstanceOf(EmailNotificationSetting::class, $setting);
        });
        $credential->pushSetting()->each(function (NotificationSetting $setting) {
            $this->assertInstanceOf(PushNotificationSetting::class, $setting);
        });
        $credential->smsSetting()->each(function (NotificationSetting $setting) {
            $this->assertInstanceOf(SmsNotificationSetting::class, $setting);
        });
    }

    public function test_that_a_credential_has_many_notification_logs()
    {
        /** @var Credential $credential */
        $credential = factory(Credential::class)->create();
        $times = rand(1, 5);
        factory(EmailNotificationLog::class, $times)->create([
            'credential_id' => $credential->id
        ]);
        factory(PushNotificationLog::class, $times)->create([
            'credential_id' => $credential->id
        ]);
        factory(SmsNotificationLog::class, $times)->create([
            'credential_id' => $credential->id
        ]);

        $credential->emailLog()->each(function (NotificationLog $setting) {
            $this->assertInstanceOf(EmailNotificationLog::class, $setting);
        });
        $credential->pushLog()->each(function (NotificationLog $setting) {
            $this->assertInstanceOf(PushNotificationLog::class, $setting);
        });
        $credential->smsLog()->each(function (NotificationLog $setting) {
            $this->assertInstanceOf(SmsNotificationLog::class, $setting);
        });
    }

}
