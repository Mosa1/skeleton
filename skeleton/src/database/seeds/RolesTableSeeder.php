<?php

namespace BetterFly\Skeleton\Database\Seeds;

use Illuminate\Database\Seeder;
use BetterFly\Skeleton\Services\UserRoleService;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(UserRoleService $userRoleService)
    {
        $userRoleService->updateStandardRoles();
    }
}
