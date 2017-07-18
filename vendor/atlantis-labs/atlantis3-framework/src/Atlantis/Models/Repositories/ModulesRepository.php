<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\Modules;

class ModulesRepository {

  public static function getInstalledModuleByID($module_id) {
    return Modules::find($module_id);
  }

    public static function getModule($namespace) {

    $mod = new Modules();

    return $mod->where("namespace", "=", $namespace)
                    ->get();
  }

  public static function getModulesWithExtra() {

    return Modules::where('extra', '!=', serialize(NULL))
                    ->where('active', '=', 1)
                    ->get();
  }
  
  public static function getAllModules() {
    
    return Modules::where('name', '!=', 'Site')->get();
    
  }

}
