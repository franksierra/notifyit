<?php

use App\Models\App;
use App\Models\AppKey;
use App\Models\PushDevice;
use App\Models\User;
use App\Models\EmailSetting;
use App\Models\PushSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Users
        $user = User::where('username', 'admin')->first();
        $app = App::create([
            'name' => 'TestKey',
            'user_id' => $user->id,
            'alias' => 'testkey',
            'created_at' => Carbon::now()
        ]);
        $app_key = AppKey::create([
            'app_id' => $user->id,
            'platform' => 'other',
            'key' => 'TARS' . 'testkey',
            'active' => 1,
            'created_at' => Carbon::now()
        ]);
        $email_settings = EmailSetting::create([
            'app_id' => $app->id,
            'driver' => 'sendmail',
            'mail_type' => 'html',
            'subject_prefix' => 'dev',
        ]);
        $push_settings = PushSetting::create([
            'app_id' => $app->id,
            'endpoint' => 'https://fcm.googleapis.com/fcm/send',
            'api_key' => 'AlZa' . Str::random(128),
        ]);
        $push_device = PushDevice::create([
            'app_id' => $app->id,
            'platform' => 'android',
            'uuid' => 'UID:' . Str::random(36),
            'identity' => Str::random(15),
            'regid' => Str::random(128)
        ]);
    }
}
