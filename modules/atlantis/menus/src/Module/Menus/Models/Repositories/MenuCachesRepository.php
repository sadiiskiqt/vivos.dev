<?php

namespace Module\Menus\Models\Repositories;

use Module\Menus\Models\MenuCaches;

class MenuCachesRepository {

  public static function saveCache($menu_id, $compiled) {

    $model = MenuCaches::firstOrNew(['menu_id' => $menu_id]);

    if ($model->id == NULL) {
      //create
      $model->create([
          'menu_id' => $menu_id,
          'compiled' => $compiled
      ]);
    } else {
      //update
      $model->update([
          'menu_id' => $menu_id,
          'compiled' => $compiled
      ]);
    }
  }

  public static function getMenu($menu_id) {
    $model = MenuCaches::where('menu_id', '=', $menu_id)->get()->first();

    if ($model == NULL) {
      //create cache
      \Module\Menus\Helpers\MenuBuilder::makeCache($menu_id);
      return MenuCaches::where('menu_id', '=', $menu_id)->get()->first();
    } else {
      return $model;
    }
  }
  
  public static function deleteCache($menu_id) {
    MenuCaches::where('menu_id', '=', $menu_id)->delete();
  }

}
