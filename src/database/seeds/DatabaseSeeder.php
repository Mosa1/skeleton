<?php

namespace BetterFly\Skeleton\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        if (App::environment(['local', 'staging'])) {
            // If env is not on production
//            $this->call(ArticlesTableSeeder::class);
        }
    }
}
