<?php

namespace Module\Menus\Controllers\Admin;

use Illuminate\Http\Request;
use Atlantis\Controllers\Admin\AdminModulesController;
use Module\Menus\Models\Repositories\MenuRepository;
use Module\Menus\Models\Repositories\MenuItemsRepository;

class MenusAdminController extends AdminModulesController {

  public function __construct() {
    parent::__construct(\Config::get('menus.setup'));
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

    return view('menus-admin::admin/menus', $aData);
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

    $aData['menus'][0] = '---';

    $menus = MenuRepository::getAll();

    foreach ($menus as $menu) {
      $aData['menus'][$menu->id] = $menu->name;
    }

    return view('menus-admin::admin/menus-add', $aData);
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

    $menu = MenuRepository::getMenu($id);

    if ($menu != NULL) {

      $aData['menu'] = $menu;

      $aData['menu_items'] = MenuItemsRepository::getItems($id);

      $aData['menus'][0] = '---';

      $menus = MenuRepository::getAll();

      foreach ($menus as $menu) {
        $aData['menus'][$menu->id] = $menu->name;
      }
    } else {
      $aData['invalid_item'] = 'This menu is not valid';
    }

    return view('menus-admin::admin/menus-edit', $aData);
  }

  public function postAdd(Request $request) {

    $model = new MenuRepository();

    $data = $request->all();

    $validator = $model->validationCreate($data);

    if (!$validator->fails()) {
      //
      $id = $model->createMenu($data);

      \Session::flash('success', 'Menu ' . $data['name'] . ' was created');

      if ($request->get('_update')) {
        return redirect('admin/modules/menus/edit/' . $id);
      } else {
        return redirect('admin/modules/menus');
      }
    } else {

      return redirect()->back()->withErrors($validator)->withInput();
    }
  }

  public function postEdit($id = NULL, Request $request) {

    if ($id != NULL) {

      $model = new MenuRepository();

      $data = $request->all();

      $validator = $model->validationCreate($data, $id);

      if (!$validator->fails()) {

        $model->editMenu($id, $data);

        \Session::flash('success', 'Menu ' . $data['name'] . ' was edited');

        if ($request->get('_update')) {
          return redirect('admin/modules/menus/edit/' . $id);
        } else {
          return redirect('admin/modules/menus');
        }
      } else {

        return redirect()->back()->withErrors($validator)->withInput();
      }
    }
  }

  public function getDelete($id = NULL) {

    if ($id != NULL) {

      MenuRepository::deleteMenu($id);

      \Session::flash('success', 'Menu was deleted');

      return redirect('admin/modules/menus');
    }
  }

  public function postBulkAction(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {
          MenuRepository::deleteMenu($id);
        }
        \Session::flash('success', 'Menus was deleted');
      }
    }

    return redirect()->back();
  } 

}
