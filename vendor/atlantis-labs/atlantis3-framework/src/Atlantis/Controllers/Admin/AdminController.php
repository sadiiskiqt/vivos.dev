<?php

namespace Atlantis\Controllers\Admin;

use App\Http\Controllers\Controller;
use Atlantis\Helpers\LockedItems;

class AdminController extends Controller {

  public static $_ID_DASHBOARD = 'dashboard';
  public static $_ID_PAGES = 'pages';
  public static $_ID_PATTERNS = 'patterns';
  public static $_ID_MODULES = 'modules';
  public static $_ID_MEDIA = 'media';
  public static $_ID_THEMES = 'themes';
  public static $_ID_USERS = 'users';
  public static $_ID_ROLES = 'roles';
  public static $_ID_DEFAULTS = 'defaults';
  public static $_ID_SEARCH = 'search';
  public static $_ID_TRASH = 'trash';
  //public static $_ID_CATEGORIES = 'categories';
  public static $_ID_MENUS = 'menus';
  public static $_ID_CONFIG = 'config';
  
  private $identifier = '';
  private $lockedItems;

  public function __construct($identifier) {    
    
    if (auth()->user() != NULL) {
      \Lang::setLocale(auth()->user()->language);
    }
    
    $this->identifier = $identifier;
    
    request()->attributes->set('_identifier', $this->identifier);
    
    $this->middleware('Atlantis\Middleware\AdminAuth');    

    $this->middleware('Atlantis\Middleware\Permissions:' . $this->identifier . ','
            . 'Atlantis\Models\Repositories\RoleUsersRepository,'
            . 'Atlantis\Models\Repositories\PermissionsRepository');    
    
    if (!\Atlantis\Helpers\Themes\ThemeTools::haveActiveTheme()) {
      \Session::flash('error', "System can't find active theme");
    }

    $this->lockedItems = new LockedItems($this->identifier);
  }

  public function getIdentifier() {

    return $this->identifier;
  }

  /*
   * Lock item
   * 
   * @return BOOLEAN
   */
  
  public function lockItem($item_id, $item_identifier = NULL) {
    return $this->lockedItems->lockItem($item_id, $item_identifier);
  }
  
  /*
   * Unlock item
   * 
   * @return BOOLEAN
   */
  public function unlockItem($item_id, $item_identifier = NULL) {
    return $this->lockedItems->unlockItem($item_id, $item_identifier);
  }
  
  /*
   * If item is locked
   * 
   * @return BOOLEAN
   */
  public function isLockedItem($item_id, $item_identifier = NULL) {
    return $this->lockedItems->isLockedItem($item_id, $item_identifier);
  }

}
