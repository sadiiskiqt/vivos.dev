<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Atlantis\Models\Repositories\RolesRepository;

class RolesController extends AdminController {

  public function __construct() {

    parent::__construct(self::$_ID_ROLES);
  }

  public function getIndex() {

    $aData = array();

    if (\Session::get('info') != NULL) {
      $aData['msgInfo'] = \Session::get('info');
    }

    if (\Session::get('success') != NULL) {
      $aData['msgSuccess'] = \Session::get('success');
    }

    if (\Session::get('error') != NULL) {
      $aData['msgError'] = \Session::get('error');
    }

    return view('atlantis-admin::roles', $aData);
  }

  public function getAdd() {

    $aData = array();

    if (\Session::get('info') != NULL) {
      $aData['msgInfo'] = \Session::get('info');
    }

    if (\Session::get('success') != NULL) {
      $aData['msgSuccess'] = \Session::get('success');
    }

    if (\Session::get('error') != NULL) {
      $aData['msgError'] = \Session::get('error');
    }

    $aData['aAdminItems'] = $this->getAdminItems();
    $aData['aModules'] = $this->getModules();

    return view('atlantis-admin::roles-add', $aData);
  }

  public function postAdd(Request $request) {

    $model = new RolesRepository();

    $data = $request->all();

    $validator = $model->validationCreate($data);

    if (!$validator->fails()) {

      $id = $model->createRole($data);

      \Session::flash('success', 'Role ' . $data['name'] . ' was created');

      if ($request->get('_update')) {
        return redirect('admin/roles/edit/' . $id);
      } else {
        return redirect('admin/roles');
      }
    } else {

      return redirect()->back()->withErrors($validator)->withInput();
    }
  }

  public function getEdit($id = NULL) {

    $aData = array();

    if (\Session::get('info') != NULL) {
      $aData['msgInfo'] = \Session::get('info');
    }

    if (\Session::get('success') != NULL) {
      $aData['msgSuccess'] = \Session::get('success');
    }

    if (\Session::get('error') != NULL) {
      $aData['msgError'] = \Session::get('error');
    }

    $role = RolesRepository::getRole($id);

    if ($role != NULL) {

      $permissions = \Atlantis\Models\Repositories\PermissionsRepository::getPermissionsByRole($role->id);
      $aAdminItems = $this->getAdminItems();
      $aModules = $this->getModules();

      if ($role->id == 1 || $role->id == 2 || $role->id == 3) {
        $aData['editable'] = FALSE;
        $aData['disabled'] = 'disabled';

        if ($role->id == 3) {
          foreach ($aAdminItems as $key => $value) {
            $aAdminItems[$key]['checked'] = TRUE;
          }

          foreach ($aModules as $key => $value) {
            $aModules[$key]['checked'] = TRUE;
          }
        }
      } else {
        $aData['editable'] = TRUE;
        $aData['disabled'] = '';

        foreach ($permissions as $per) {

          if (array_key_exists($per->type, $aAdminItems)) {
            $aAdminItems[$per->type]['checked'] = TRUE;
          }

          if ($per->type == AdminController::$_ID_MODULES && array_key_exists($per->value, $aModules)) {
            $aModules[$per->value]['checked'] = TRUE;
          }
        }
      }

      $aData['oRole'] = $role;
      $aData['aAdminItems'] = $aAdminItems;
      $aData['aModules'] = $aModules;
    } else {
      $aData['invalid_item'] = 'This role is not valid';
    }
    return view('atlantis-admin::roles-edit', $aData);
  }
  
  public function postEdit($id = NULL, Request $request) {
    
    if ($id != NULL) {

      $model = new RolesRepository();

      $data = $request->all();

      $validator = $model->validationCreate($data, $id);

      if (!$validator->fails()) {

        $model->updateRole($id, $data);

        \Session::flash('success', 'Role ' . $data['name'] . ' was edited');

        if ($request->get('_update')) {
          return redirect('admin/roles/edit/' . $id);
        } else {
          return redirect('admin/roles');
        }
      } else {

        return redirect()->back()->withErrors($validator)->withInput();
      }
    }
  }
  
  public function getDelete($id = NULL) {
    
    if ($id != NULL) {
      
      RolesRepository::deleteRole($id);
      
      \Session::flash('success', 'Role was deleted');
      
      return redirect('admin/roles');
    }    
  }
  
  public function postBulkAction(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {
         RolesRepository::deleteRole($id);
        }
        \Session::flash('success', 'Roles was deleted');
      }
    }

    return redirect()->back();
  }

  private function getAdminItems() {

    return [
        AdminController::$_ID_DASHBOARD => ['name' => 'Dashboard', 'checked' => FALSE],
        AdminController::$_ID_PAGES => ['name' => 'Pages', 'checked' => FALSE],
        AdminController::$_ID_PATTERNS => ['name' => 'Patterns', 'checked' => FALSE],
        //AdminController::$_ID_MODULES => ['name' => 'Modules', 'checked' => FALSE],
        AdminController::$_ID_MEDIA => ['name' => 'Media', 'checked' => FALSE],
        //AdminController::$_ID_CATEGORIES => ['name' => 'Categories (Settings)', 'checked' => FALSE],
        AdminController::$_ID_MENUS => ['name' => 'Menus (Settings)', 'checked' => FALSE],
        AdminController::$_ID_USERS => ['name' => 'Users (System)', 'checked' => FALSE],
        AdminController::$_ID_ROLES => ['name' => 'Roles (System)', 'checked' => FALSE],
        AdminController::$_ID_DEFAULTS => ['name' => 'Defaults (System)', 'checked' => FALSE],
        AdminController::$_ID_SEARCH => ['name' => 'Advanced Search (System)', 'checked' => FALSE],
        AdminController::$_ID_CONFIG => ['name' => 'Config (System)', 'checked' => FALSE],
        AdminController::$_ID_TRASH => ['name' => 'Trash (System)', 'checked' => FALSE]
    ];
  }

  private function getModules() {
    $modules = \Atlantis\Models\Repositories\ModulesRepository::getAllModules();

    $aModules = array();

    foreach ($modules as $module) {
      $aModules[$module->namespace]['name'] = $module->name;
      $aModules[$module->namespace]['checked'] = FALSE;
    }

    return $aModules;
  }

}
