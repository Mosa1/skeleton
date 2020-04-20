<?php

namespace BetterFly\Skeleton\Database\Seeds;

use Illuminate\Database\Seeder;
use BetterFly\Skeleton\Models\User;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = env("SUPERUSER_EMAIL", "admin@betterfly.ge");
        $password = env("SUPERUSER_PASS", "betterflyPass");

        if (User::where('email', '=', $email)->count() == 0) {
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => $email,
                'password' => bcrypt($password),
                'remember_token' => Str::random(60),
                'is_super' => 1
            ]);
        }
    }
}
