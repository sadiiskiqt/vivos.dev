<?php

namespace Module\Menus\Helpers;

use Illuminate\Support\Facades\DB;
use Module\Menus\Models\Repositories\MenuItemsRepository;
use Module\Menus\Models\Repositories\MenuCachesRepository;

class MenuBuilder {

  public function __construct() {
    
  }

  /**
   * Function getList
   * 
   * Recursive function
   * Find and return all menu IDs.
   * 
   * @param string $aCurrentIDs
   * @param array $aAllIDs
   * @return array
   */
  private function getList($aCurrentIDs, $aAllIDs) {

    foreach ($aCurrentIDs as $currentID) {

      $oCurrentItems = MenuItemsRepository::getItems($currentID);

      foreach ($oCurrentItems as $curentItem) {
        if ($curentItem->child_id != 0) {
          $aAllIDs[] = $curentItem->child_id;
          $aHelper[] = $curentItem->child_id;
        }
      }
    }

    if (isset($aHelper)) {
      return $this->getList($aHelper, $aAllIDs);
    } else {
      return $aAllIDs;
    }
  }

  public function getMenu($nMenuID, $forCache = FALSE) {

    $aMenuItems = array();
    $aID = array();

    $aID = $this->getList([$nMenuID], array());

    array_push($aID, intval($nMenuID));

    $list_menu_id = array_unique($aID);

    $oMenu = DB::table('menus')
            ->select('menus.*', 'menu_items.*', 'menu_items.id as item_id', 'menus.id as id')
            ->leftJoin('menu_items', 'menus.id', '=', 'menu_items.menu_id')
            ->whereIN('menus.id', $list_menu_id)
            ->orderBy('menu_items.weight')
            ->orderBy('menu_items.parent_id')
            ->get();

    //generate array with all items
    foreach ($oMenu as $key => $menu) {
      $aMenuItems[$menu->item_id]['mid'] = $menu->menu_id;
      $aMenuItems[$menu->item_id]['menu_name'] = $menu->name;
      $aMenuItems[$menu->item_id]['menu_css'] = $menu->css;
      $aMenuItems[$menu->item_id]['menu_attributes'] = $menu->menu_attributes;
      $aMenuItems[$menu->item_id]['menu_element_id'] = $menu->element_id;

      $aMenuItems[$menu->item_id]['id'] = $menu->item_id;

      if ($menu->menu_id == $nMenuID && $menu->parent_id != '0') {

        $aMenuItems[$menu->item_id]['parent_id'] = 0;
      } else {

        $aMenuItems[$menu->item_id]['parent_id'] = intval($menu->parent_id);
      }
      $aMenuItems[$menu->item_id]['item_label'] = $menu->label;
      $aMenuItems[$menu->item_id]['item_url'] = $menu->url;
      $aMenuItems[$menu->item_id]['onclick'] = $menu->onclick;
      $aMenuItems[$menu->item_id]['class'] = $menu->class;
      $aMenuItems[$menu->item_id]['attributes'] = $menu->attributes;
    }


    // Each node starts with 0 children
    foreach ($aMenuItems as &$menuItem) {
      $menuItem['children'] = array();
    }

    // If menu item has parent_id, add it to parent's Children array
    foreach ($aMenuItems as $key => &$menuItem) {

      if ($menuItem['parent_id'] != null) {
        $aMenuItems[$menuItem['parent_id']]['children'][$key] = &$menuItem;
      }
    }

    // Remove children from $aMenuItems so only top level items remain
    foreach (array_keys($aMenuItems) as $key) {
      if ($aMenuItems[$key]['parent_id'] != null) {
        unset($aMenuItems[$key]);
      }
    }
    reset($aMenuItems);
    
    $menu = $this->listMenu($aMenuItems, "0", key($aMenuItems));

    if ($forCache) {
      return $menu;
    }

    return $this->responsiveMenu($menu);
  }

  /**
   * Function listMenu
   * 
   * Recursive function.
   * Create all menu whith parrents and children elements from array.
   * 
   * @param array $aMenuItems
   * @param string $parent_id
   * @param string $menuID
   * @return string
   */
  private function listMenu($aMenuItems, $parent_id, $menuID) {

    $i = 1;
    $sClass = "";
    $sCssID = "";
    $menuAttr = "";

    if ($menuID != "") {

      $sClass = "class='" . $aMenuItems[$menuID]["menu_css"] . " id" . $aMenuItems[$menuID]["mid"] . "'";
      
      if (!empty($aMenuItems[$menuID]["menu_attributes"])) {
        $menuAttr = ' ' . $aMenuItems[$menuID]["menu_attributes"];
      }
      
      if (!empty($aMenuItems[$menuID]["menu_element_id"])) {
        $sCssID = "id='" . $aMenuItems[$menuID]["menu_element_id"] . "'";
      } else {
        $sCssID = "";
      }
    }

    if ($aMenuItems[$menuID]["menu_css"] == "" && $aMenuItems[$menuID]["menu_element_id"] == "") {

      $output = "\n" . '<ul' . $menuAttr . '>' . "\n";
    } else {
      $output = "\n" . '<ul ' . $sClass . ' ' . $sCssID . '' . $menuAttr . '>' . "\n";
    }

    foreach ($aMenuItems as $MenuItem) {

      if ($MenuItem['parent_id'] == $parent_id) {

        if ($MenuItem["onclick"] != "") {

          if (strstr($MenuItem['item_url'], "http") || strstr($MenuItem['item_url'], "javascript") || strstr($MenuItem['item_url'], "#")) {

            $output .= '<li class="' . $MenuItem['class'] . '" id="' . str_replace(" ", "-", strtolower($MenuItem['menu_name'])) . '-item' . $i . '" ><a href="' . $MenuItem['item_url'] . '" onclick="' . $MenuItem["onclick"] . '" ' . $MenuItem['attributes'] . 'target="_blank">' . htmlentities($MenuItem['item_label']) . '</a>';
          } else {

            $output .= '<li class="' . $MenuItem['class'] . '" id="' . str_replace(" ", "-", strtolower($MenuItem['menu_name'])) . '-item' . $i . '" ><a href="/' . $MenuItem['item_url'] . '" onclick="' . $MenuItem["onclick"] . '" ' . $MenuItem['attributes'] . '>' . htmlentities($MenuItem['item_label']) . '</a>';
          }
        } else {

          if (strstr($MenuItem['item_url'], "http") || strstr($MenuItem['item_url'], "javascript") || strstr($MenuItem['item_url'], "#")) {

            $output .= '<li class="' . $MenuItem['class'] . '" id="' . str_replace(" ", "-", strtolower($MenuItem['menu_name'])) . '-item' . $i . '" ><a href="' . $MenuItem['item_url'] . '" ' . $MenuItem['attributes'] . 'target="_blank">' . htmlentities($MenuItem['item_label']) . '</a>';
          } else {

            $output .= '<li class="' . $MenuItem['class'] . '" id="' . str_replace(" ", "-", strtolower($MenuItem['menu_name'])) . '-item' . $i . '" ><a href="/' . $MenuItem['item_url'] . '" ' . $MenuItem['attributes'] . '>' . htmlentities($MenuItem['item_label']) . '</a>';
          }
        }

        $i++;
        if (count($MenuItem['children']) > 0) {

          $output .= $this->listMenu($MenuItem["children"], $MenuItem['id'], key($MenuItem["children"]));
        } else {

          $output .= "";
        }

        $output .= '</li>' . "\n";
      }
    }

    $output .= '</ul>' . "\n";

    return $output;
  }

  private function responsiveMenu($sMenu) {

    $dom = new \DomDocument();

    //libxml_use_internal_errors(true);

    $menu = mb_convert_encoding($sMenu, 'HTML-ENTITIES', "UTF-8");

    @$dom->loadXML($menu);

    //libxml_clear_errors();

    $xpath = new \DOMXpath($dom);

    $path = request()->path();

    if ($path == '/') {
      $elements = $xpath->query("//a[@href='" . $path . "']");
    } else {
      $elements = $xpath->query("//a[@href='/" . $path . "']");
    }

    $elements1 = $xpath->query("//a[@href='" . request()->url() . "']");

    if ($elements->length > 0) {

      $li = $elements->item(0)->parentNode;

      $liClass = $li->getAttribute('class');
      if (empty($liClass)) {
        $li->setAttribute("class", "active");
      } else {
        $li->setAttribute('class', $liClass . ' active');
      }

      return $dom->saveXML();
    } elseif ($elements1->length > 0) {

      $li = $elements1->item(0)->parentNode;

      $liClass = $li->getAttribute('class');
      if (empty($liClass)) {
        $li->setAttribute("class", "active");
      } else {
        $li->setAttribute('class', $liClass . ' active');
      }

      return $dom->saveXML();
    } else {

      return $sMenu;
    }
  }

  /**
   * Function menuCache
   * 
   * After create or edit menu
   * This function create new cache 
   * 
   * @param string $nMenuID
   * @param string $sMenuName
   */
  public function menuCache($nMenuID) {

    $rStmt = MenuItemsRepository::getItems($nMenuID)->first();

    $menu = $this->getMenu($nMenuID, TRUE);
    MenuCachesRepository::saveCache($nMenuID, $menu);

    if ($rStmt->parent_id != 0) {

      $oMenuItem = MenuItemsRepository::getItem($rStmt->parent_id);

      $this->menuCache($oMenuItem->menu_id);
    }
  }

  public static function buildByID($params) {

    $menu_id = $params[0];

    if (isset($params[1])) {
      $withCache = filter_var($params[1], FILTER_VALIDATE_BOOLEAN);
    } else {
      $withCache = FALSE;
    }

    $menuBuilder = new MenuBuilder();

    if ($withCache) {
      $oMenuCache = MenuCachesRepository::getMenu($menu_id);
      return $menuBuilder->responsiveMenu($oMenuCache->compiled);
    } else {
      return $menuBuilder->getMenu($menu_id);
    }
  }

  public static function makeCache($menu_id) {

    $menuBuilder = new MenuBuilder();
    $menuBuilder->menuCache($menu_id);
  }

}
