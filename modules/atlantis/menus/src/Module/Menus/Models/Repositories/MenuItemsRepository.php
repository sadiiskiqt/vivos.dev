<?php

namespace Module\Menus\Models\Repositories;

use Module\Menus\Models\MenuItems;

class MenuItemsRepository {
  
  public static function deleteItemsByMenu($menu_id) {
    
    $items = MenuItems::where('menu_id', '=', $menu_id)
            ->where('child_id', '!=', 0)
            ->get();
    
    foreach ($items as $item) {
      $childItems = MenuItems::where('menu_id', '=', $item->child_id)->get();
      
      foreach ($childItems as $child_item) {
        $child_item->parent_id = 0;
        $child_item->update();
      }
    }
    
    MenuItems::where('menu_id', '=', $menu_id)->delete();
  }
  
  public static function deleteItem($item_id) {
    
    $items = MenuItems::where('parent_id', '=', $item_id)->get();
    
    foreach ($items as $item) {
      $item->parent_id = 0;
      $item->update();
    }
    
    MenuItems::find($item_id)->delete();
  }


  public static function getItems($menu_id) {
    return MenuItems::where('menu_id', '=', $menu_id)->orderBy('weight', 'asc')->get();
  }
  
  public static function getAll() {
    return MenuItems::all();
  }
  
  public static function getAllItemsByParentID($parent_id) {
    return MenuItems::where('parent_id', '=', $parent_id)->get();
  }
  
  public static function getItem($item_id) {
    return MenuItems::find($item_id);
  }
  
}
