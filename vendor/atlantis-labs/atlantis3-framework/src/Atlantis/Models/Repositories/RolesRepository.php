<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\Role;
use Illuminate\Support\Facades\Validator;

class RolesRepository {

  public static function getRole($id) {
    return Role::find($id);
  }

  public static function getAll() {
    return Role::all();
  }

  public function validationCreate($data, $user_id = NULL) {

    /**
     *  Validation rules for create
     * 
     * @var array
     */
    $rules_create = [
        'name' => 'required|unique:roles,name,' . $user_id,
        'description' => 'required'
    ];

    $messages = [
        'required' => trans('admin::validation.required'),
        'unique' => trans('admin::validation.unique')
    ];

    $validator = Validator::make($data, $rules_create, $messages);

    //$validator = $this->addReplacers($validator);

    return $validator;
  }

  public function createRole($data) {

    $model = Role::create($data);

    if (isset($data['admin_items'])) {

      foreach ($data['admin_items'] as $admin_item) {
        PermissionsRepository::addPermission($model->id, $admin_item);
      }
    }

    if (isset($data['modules_items'])) {

      foreach ($data['modules_items'] as $module_item) {
        PermissionsRepository::addPermission($model->id, \Atlantis\Controllers\Admin\AdminController::$_ID_MODULES, $module_item);
      }
    }

    return $model->id;
  }

  public function updateRole($id, $data) {

    $model = Role::find($id);

    if ($model != NULL) {
      $model->update($data);

      PermissionsRepository::deleteAllPermissionsByRoleID($id);
      if (isset($data['admin_items'])) {

        foreach ($data['admin_items'] as $admin_item) {
          PermissionsRepository::addPermission($model->id, $admin_item);
        }
      }

      if (isset($data['modules_items'])) {

        foreach ($data['modules_items'] as $module_item) {
          PermissionsRepository::addPermission($model->id, \Atlantis\Controllers\Admin\AdminController::$_ID_MODULES, $module_item);
        }
      }
    }
  }

  public static function deleteRole($id) {

    if ($id == 1 || $id == 2 || $id == 3) {
      return FALSE;
    } else {
      $model = Role::find($id);

      if ($model != NULL) {
        $model->delete();
        PermissionsRepository::deleteAllPermissionsByRoleID($id);
        return TRUE;
      }
      return FALSE;
    }
  }

}
