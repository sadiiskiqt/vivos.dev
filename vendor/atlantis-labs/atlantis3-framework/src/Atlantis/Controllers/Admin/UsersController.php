<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Atlantis\Models\Repositories\UserRepository;
use Atlantis\Helpers\Tools;
use Atlantis\Models\Repositories\PermissionsRepository;

class UsersController extends AdminController {

  public function __construct() {

    parent::__construct(self::$_ID_USERS);
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

    return view('atlantis-admin::users', $aData);
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

    $aEditors = \Atlantis\Helpers\Tools::getEditors();
    array_unshift($aEditors, NULL);
    $aData['aEditors'] = $aEditors;
    $aData['oRoles'] = \Atlantis\Models\Repositories\RolesRepository::getAll();

    $aData['aLang'] = Tools::getAdminLanguages();

    return view('atlantis-admin::users-add', $aData);
  }

  public function postAdd(Request $request) {

    $data = $request->all();

    $oUser = new UserRepository();

    $validator = $oUser->validationCreate($data);

    if (!$validator->fails()) {

      $id = $oUser->addUser($data);

      \Session::flash('success', 'User ' . $data['name'] . ' was created');

      if ($request->get('_update')) {
        return redirect('admin/users/edit/' . $id);
      } else {
        return redirect('admin/users');
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

    $user = UserRepository::getUser($id);

    if ($user != NULL) {

      $allRoles = \Atlantis\Models\Repositories\RolesRepository::getAll();
      $userRoles = \Atlantis\Models\Repositories\RoleUsersRepository::getRolesByUserID($user->id);

      $aRoles = array();
      $aUserRoles = array();

      foreach ($allRoles as $k => $role) {
        $aRoles[$k]['id'] = $role->id;
        $aRoles[$k]['name'] = $role->name;
        $aRoles[$k]['description'] = $role->description;

        $checked = FALSE;

        foreach ($userRoles as $user_role) {

          if ($role->id == $user_role->role_id) {
            $checked = TRUE;
            $aUserRoles[] = $role->id;
          }
        }

        $aRoles[$k]['checked'] = $checked;
      }

      if (auth()->user()->hasRole('admin')) {
        $aData['displayNone'] = '';
      } else {
        $aData['displayNone'] = ' style="display:none"';
        foreach ($aUserRoles as $r_id) {
          if (PermissionsRepository::findType(AdminController::$_ID_USERS, $r_id)->first() != NULL) {
            $aData['displayNone'] = '';
          }
        }
      }

      $aData['user'] = $user;

      $aData['canDeleteUser'] = self::canDeleteUser($id);
      $aData['aLang'] = Tools::getAdminLanguages();

      $aEditors = \Atlantis\Helpers\Tools::getEditors();
      array_unshift($aEditors, NULL);
      $aData['aEditors'] = $aEditors;
      $aData['aRoles'] = $aRoles;

      $widgets = new \Atlantis\Widgets\Builder();
      $aData['widgets'] = $this->excludedWidgets($widgets->getAllWidgets());
    } else {
      $aData['invalid_item'] = 'This user is not valid';
    }

    return view('atlantis-admin::users-edit', $aData);
  }

  public function postEdit($id = NULL, Request $request) {

    if ($id != NULL) {

      $model = new UserRepository();

      $data = $request->all();

      $validator = $model->validationUpdate($data, $id);

      if (!$validator->fails()) {

        $model->updateUser($id, $data);

        \Session::flash('success', 'User ' . $data['name'] . ' was edited');

        if ($request->get('_update')) {
          return redirect('admin/users/edit/' . $id);
        } else {
          return redirect('admin/users');
        }
      } else {

        return redirect()->back()->withErrors($validator)->withInput();
      }
    }
  }

  public function getDelete($id = NULL) {

    if ($id != NULL) {

      if (self::canDeleteUser($id)) {

        UserRepository::deleteUser($id);

        \Session::flash('success', 'User was deleted');
      }
      return redirect('admin/users');
    }
  }

  public function postBulkAction(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {
          if (self::canDeleteUser($id)) {
            UserRepository::deleteUser($id);
          }
        }
        \Session::flash('success', 'Users was deleted');
      }
    }

    return redirect()->back();
  }

  public static function canDeleteUser($id) {
    if (auth()->user()->id == $id || $id == 1) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  private function excludedWidgets($widgets) {

    $allowedMods = PermissionsRepository::getAllModulesPermissionsForUser(auth()->user()->id);

    if (auth()->user()->hasRole('admin')) {
      return $widgets;
    }

    foreach ($widgets as $k => $widget) {

      if ($allowedMods->where('value', $widget['moduleSetup']['moduleNamespace'])->isEmpty()) {
        unset($widgets[$k]);
      }
    }
    return $widgets;
  }

}
