<?php

namespace Atlantis\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminModulesController extends Controller {

  private $identifier = '';
  private $moduleConfig;

  public function __construct($moduleConfig) {

    if (auth()->user() != NULL) {
      \Lang::setLocale(auth()->user()->language);
    }


    $this->identifier = AdminController::$_ID_MODULES;
    $this->moduleConfig = $moduleConfig;

    request()->attributes->set('_identifier', $this->identifier);

    $this->middleware('Atlantis\Middleware\AdminAuth');
    $this->middleware('Atlantis\Middleware\Permissions:' . $this->moduleConfig['moduleNamespace'] . ','
            . 'Atlantis\Models\Repositories\RoleUsersRepository,'
            . 'Atlantis\Models\Repositories\PermissionsRepository');
  }

  public function getIdentifier() {

    return $this->identifier;
  }

  public function getModuleConfig($key = NULL) {

    if ($key == NULL) {
      return $this->moduleConfig;
    } else {

      if (isset($this->moduleConfig[$key])) {
        return $this->moduleConfig[$key];
      } else {
        return NULL;
      }
    }
  }

}
