<?php

namespace Atlantis\Helpers;

/**
 * Menu navigation for admin only
 */
class MenuNavigation {

  private $is_admin = FALSE;
  
  public function __construct() {
    $this->is_admin = auth()->user()->hasRole('admin');
  }

  public function create() {

    $aData = array();

    $routes = \Route::getRoutes();

    $aMenuItems = array();

    $userPermissions = \Atlantis\Models\Repositories\PermissionsRepository::getAllPermissionsForUser(auth()->user()->id);
    $aUserPerm = array();
    foreach ($userPermissions as $user_perm) {
      $aUserPerm[] = $user_perm->type;
    }

    $aUserPerm = array_unique($aUserPerm);

    foreach ($routes as $route) {

      $action = $route->getAction();

      if (isset($action['identifier']) && isset($action['name']) && $this->isAllowed($aUserPerm, $action['identifier'])) {

        $_identifier = request()->attributes->getAlnum('_identifier');

        if (isset($action['icon'])) {
          $class = $action['icon'];
        } else {
          $class = '';
        }
        
        if (isset($action['tooltip'])) {
          $tooltip = $action['tooltip'];
        } else {
          $tooltip = '';
        }

        if (isset($action['parent'])) {

          if (isset($action['parent-icon'])) {
            $parentKey = !empty($action['parent']) ? $action['parent'] : $action['parent-icon'];
            $parentClass = $action['parent-icon'];
          } else {
            $parentKey = $action['parent'];
            $parentClass = '';
          }
          
          $aMenuItems[$parentKey]['child_items'][$action['identifier']]['name'] = $action['name'];
          $aMenuItems[$parentKey]['child_items'][$action['identifier']]['class'] = $class;
          $aMenuItems[$parentKey]['child_items'][$action['identifier']]['tooltip'] = $tooltip;
          $aMenuItems[$parentKey]['child_items'][$action['identifier']]['url'] = '/' . $action['menu_item_url'];
          
          if ($_identifier == $action['identifier']) {
            $aMenuItems[$parentKey]['child_items'][$action['identifier']]['active'] = ' class="active"';
            $aMenuItems[$parentKey]['active'] = ' class="active"';
          } else {
            $aMenuItems[$parentKey]['child_items'][$action['identifier']]['active'] = '';
          }
          $aMenuItems[$parentKey]['name'] = $action['parent'];
          $aMenuItems[$parentKey]['parent-class'] = $parentClass;
          $aMenuItems[$parentKey]['url'] = '#';
          if (!isset($aMenuItems[$parentKey]['active'])) {
            $aMenuItems[$parentKey]['active'] = '';
          }
          $aMenuItems[$parentKey]['is_parent'] = TRUE;
          
        } else {

          $aMenuItems[$action['identifier']]['name'] = $action['name'];
          $aMenuItems[$action['identifier']]['class'] = $class;
          $aMenuItems[$action['identifier']]['tooltip'] = $tooltip;
          $aMenuItems[$action['identifier']]['url'] = '/' . $action['menu_item_url'];
          if ($_identifier == $action['identifier']) {
            $aMenuItems[$action['identifier']]['active'] = ' class="active"';
          } else {
            $aMenuItems[$action['identifier']]['active'] = '';
          }
          $aMenuItems[$action['identifier']]['is_parent'] = FALSE;
        }
      }
    }
    
    $aData['aMenuItems'] = $aMenuItems;

    return view('atlantis-admin::helpers/menu-navigation', $aData);
  }

  public static function set() {

    $navigation = new self();

    return $navigation->create();
  }

  private function isAllowed($aUserPerm, $identifier) {

    $allow = FALSE;

    if ($this->is_admin || in_array($identifier, $aUserPerm)) {
      $allow = TRUE;
    }

    return $allow;
  }
  
  public static function setShortcutBar() {
    
    $aData['page'] = \Illuminate\Support\Facades\View::shared('_page');
    
    return view('atlantis-admin::helpers/shortcut-bar', $aData);
    
  }

}
