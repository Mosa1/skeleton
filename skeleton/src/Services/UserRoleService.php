<?php

namespace BetterFly\Skeleton\Services;

use BetterFly\Skeleton\Models\Permission;
use Illuminate\Support\Facades\Route;

class UserRoleService
{
  public function updateStandardRoles()
  {
    //Delete Standart Route Permissions
    Permission::where('is_standard', '1')->delete();

    $newRoles = [];
    $routeCollection = Route::getRoutes();
    foreach ($routeCollection as $value) {

      $actionName = $value->getActionName();
      $actionNameExp = explode("\\", $actionName);
      $displayName = last($actionNameExp);

      if (strpos($actionName, "App\\Http\\Controllers\\API\\") === false)
        continue;

      $newPermission = [
        "method" => $value->methods()[0],
        "uri" => $value->uri(),
        "name" => $value->getName(),
        "displayName" => $displayName,
        "actionName" => $actionName
      ];

      $permission = new Permission();
      $permission->name = $value->getActionName();
      $permission->display_name = $displayName;
      $permission->is_standard = 1;
      $permission->json_desc = json_encode($newPermission);
      $permission->save();
    }
  }
}