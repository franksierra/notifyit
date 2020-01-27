<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ((new User())->where('email', 'admin@notifyit.io')->doesntExist()) {
            User::create([
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@notifyit.io',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('admin'),
            ]);
        }
    }
}
