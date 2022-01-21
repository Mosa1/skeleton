<?php

namespace BetterFly\Skeleton\Database\Seeds;

use Illuminate\Database\Seeder;
use BetterFly\Skeleton\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = env("SUPERUSER_EMAIL", "admin@test.com");
        $password = env("SUPERUSER_PASS", "123123");

        if (User::where('email', '=', $email)->count() == 0) {
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => $email,
                'password' => bcrypt($password),
                'remember_token' => Str::random(60)
            ]);
        }
    }
}
