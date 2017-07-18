<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\Role;
use Atlantis\Models\Permissions;
use Atlantis\Models\Repositories\ModulesRepository;

class PermissionsRepository {

  public static function getPermissionsByRole($role_id) {

    return Role::find($role_id)->permissions;
  }

  public static function findType($type, $role_id) {

    $model = new Permissions();

    return $model->where("type", "=", $type)
                    ->where("role_id", "=", $role_id)
                    ->get();
  }

  public static function findModule($role_id, $module_namespace) {

    $modules = ModulesRepository::getModule($module_namespace);

    if ($modules->isEmpty()) {
      return $modules;
    } else {
      $model = new Permissions();

      return $model->where("type", "=", \Atlantis\Controllers\Admin\AdminController::$_ID_MODULES)
                      ->where("role_id", "=", $role_id)
                      ->where("value", "=", $modules->first()->namespace)
                      ->get();
    }
  }

  public static function addPermission($role_id, $type, $value = 1) {

    Permissions::create([
        'role_id' => $role_id,
        'type' => $type,
        'value' => $value
    ]);
  }

  public static function deleteAllPermissionsByRoleID($role_id) {
    Permissions::where('role_id', '=', $role_id)->delete();
  }

  /**
   * 
   * Not return admin-login, site-login and admin permissions
   * 
   * @param type $user_id
   * @return type
   */
  public static function getAllPermissionsForUser($user_id) {

    $userRoles = RoleUsersRepository::getRolesByUserID($user_id);

    $roleIDs = array();

    foreach ($userRoles as $user_role) {
      if (!($user_role->role_id == 1 || $user_role->role_id == 2 || $user_role->role_id == 3)) {
        $roleIDs[] = $user_role->role_id;
      }
    }

    $userPermissions = Permissions::whereIn('role_id', $roleIDs)->get();

    return $userPermissions;
  }

  /**
   * 
   * Not return admin-login, site-login and admin permissions
   * 
   * @param type $user_id
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function getAllModulesPermissionsForUser($user_id) {

    $userRoles = RoleUsersRepository::getRolesByUserID($user_id);

    $roleIDs = array();

    foreach ($userRoles as $user_role) {
      if (!($user_role->role_id == 1 || $user_role->role_id == 2 || $user_role->role_id == 3)) {
        $roleIDs[] = $user_role->role_id;
      }
    }

    $userPermissions = Permissions::whereIn('role_id', $roleIDs)
            ->where('type', '=', \Atlantis\Controllers\Admin\AdminController::$_ID_MODULES)
            ->get();

    return $userPermissions;
  }

}
