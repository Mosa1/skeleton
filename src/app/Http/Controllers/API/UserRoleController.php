<?php

namespace BetterFly\Skeleton\App\Http\Controllers\API;

use BetterFly\Skeleton\App\Http\Controllers\Controller;
use BetterFly\Skeleton\Services\UserRoleService;
use Illuminate\Http\Request;
use BetterFly\Skeleton\Models\Role;
use BetterFly\Skeleton\Models\Permission;
use BetterFly\Skeleton\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRoleController extends Controller
{
    public function createRole(Request $request){
        $role = new Role();
        $role->name = $request->input('name');
        $role->save();

        return response()->json("created");
    }

    public function createPermission(Request $request){
        $viewUsers = new Permission();
        $viewUsers->name = $request->input('name');
        $viewUsers->save();

        return response()->json("created");
    }

    public function assignRole(Request $request){
        $user = User::where('email', '=', $request->input('email'))->first();

        $role = Role::where('name', '=', $request->input('role'))->first();
        //$user->attachRole($request->input('role'));
        $user->roles()->attach($role->id);

        return response()->json("created");
    }

    public function attachPermission(Request $request){
        $role = Role::where('name', '=', $request->input('role'))->first();
        $permission = Permission::where('name', '=', $request->input('name'))->first();
        $role->attachPermission($permission);

        return response()->json("created");
    }

    public function updateStandardRoles(UserRoleService $userRoleService){
        $userRoleService->updateStandardRoles();

        if(!Auth::user()->hasRole('super-admin'))
            return $this->responseWithError('Permission denied!');

        return response()->json('updated');
    }
}
