<?php

namespace Module\Menus\Models\Repositories;

use Module\Menus\Models\Menu;
use Module\Menus\Models\MenuItems;
use Illuminate\Support\Facades\Validator;
use Module\Menus\Helpers\MenuBuilder;

class MenuRepository {

  public static function getAll() {
    return Menu::all();
  }

  public static function getMenu($id) {
    return Menu::find($id);
  }

  public function validationCreate($data, $id = NULL) {

    /**
     *  Validation rules for create
     * 
     * @var array
     */
    $rules_create = [
        'name' => 'required|unique:menus,name,' . $id
    ];

    $messages = [
        'required' => trans('admin::validation.required'),
        'unique' => trans('admin::validation.unique')
    ];

    $validator = Validator::make($data, $rules_create, $messages);

    return $validator;
  }

  public function createMenu($data) {

    $model = Menu::create($data);

    foreach ($data['label'] as $k => $item) {

      if ($data['weight'][$k] < 1) {
        $weight = 1;
      } else {
        $weight = $data['weight'][$k];
      }

      $saved_item = MenuItems::create([
                  'menu_id' => $model->id,
                  'label' => $data['label'][$k],
                  'url' => $data['url'][$k],
                  'weight' => $weight,
                  'attributes' => $data['attributes'][$k],
                  'class' => $data['class'][$k],
                  'onclick' => $data['onclick'][$k],
                  'child_id' => $data['child_id'][$k]
      ]);

      $items = MenuItems::where('menu_id', '=', $data['child_id'][$k])->get();

      foreach ($items as $mitem) {
        $mitem->parent_id = $saved_item->id;
        $mitem->update();
      }
    }
    
    MenuBuilder::makeCache($model->id);

    return $model->id;
  }

  public function editMenu($id, $data) {

    $model = Menu::find($id);

    if ($model != NULL) {
      $model->update($data);

      $existItems = MenuItemsRepository::getItems($id);

      $aExistItemsIDs = array();

      foreach ($existItems as $ex_item) {
        $aExistItemsIDs[] = $ex_item->id;
      }

      foreach ($data['label'] as $k => $item) {

        if ($data['weight'][$k] < 1) {
          $weight = 1;
        } else {
          $weight = $data['weight'][$k];
        }

        if (strval($k)[0] != '_') {
          //update
          $menuItem = MenuItems::find($k);

          if ($menuItem != NUll) {
            $menuItem->update([
                'menu_id' => $model->id,
                'label' => $data['label'][$k],
                'url' => $data['url'][$k],
                'weight' => $weight,
                'attributes' => $data['attributes'][$k],
                'class' => $data['class'][$k],
                'onclick' => $data['onclick'][$k],
                'child_id' => $data['child_id'][$k]
            ]);
          }

          //remove updated item id
          if (($key = array_search($k, $aExistItemsIDs)) !== false) {
            unset($aExistItemsIDs[$key]);
          }
        } else {
          //create
          $menuItem = MenuItems::create([
                      'menu_id' => $model->id,
                      'label' => $data['label'][$k],
                      'url' => $data['url'][$k],
                      'weight' => $weight,
                      'attributes' => $data['attributes'][$k],
                      'class' => $data['class'][$k],
                      'onclick' => $data['onclick'][$k],
                      'child_id' => $data['child_id'][$k]
          ]);
        }

        $items = MenuItems::where('menu_id', '=', $data['child_id'][$k])->get();

        foreach ($items as $mitem) {
          $mitem->parent_id = $menuItem->id;
          $mitem->update();
        }

        if ($data['child_id'][$k] == 0 && strval($k)[0] != '_') {

          $oItem = MenuItemsRepository::getAllItemsByParentID($k);

          foreach ($oItem as $a) {
            $a->parent_id = 0;
            $a->update();
          }
        }
      }
      
      //delete removed items
      foreach ($aExistItemsIDs as $ex_item) {
        MenuItemsRepository::deleteItem($ex_item);
      }

      //Add the same parent_id for all items with equals mid.
      $oItemsByMID = MenuItemsRepository::getItems($id);

      foreach ($oItemsByMID as $i) {

        if ($i->parent_id != '0') {

          $mid = $i->parent_id;
        }
      }

      if (isset($mid)) {

        foreach ($oItemsByMID as $c) {

          if ($c->parent_id == '0') {
            $c->parent_id = $mid;
            $c->update();
          }
        }
      }
    }

    MenuBuilder::makeCache($id);
  }

  public static function deleteMenu($id) {

    MenuCachesRepository::deleteCache($id);

    MenuItemsRepository::deleteItemsByMenu($id);

    Menu::find($id)->delete();
    
    $menus = MenuRepository::getAll();
    
    foreach ($menus as $menu) {
      MenuBuilder::makeCache($menu->id);
    }
  }

}
