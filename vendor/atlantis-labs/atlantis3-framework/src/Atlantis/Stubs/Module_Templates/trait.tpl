<?php

 namespace %mod_namespace%\%capital_name%\Traits;

/**
 * Helper trait for extending %capital_name%Controller
 */
trait %capital_name%Trait {

  public function __call($name, $params) {

    /**
     * create controller in site/src/Module/Site/Controllers/Modules/%capital_name%Controller.php
     */
    if (class_exists('Module\Site\Controllers\Modules\%capital_name%Controller')) {

      return \App::make('Module\Site\Controllers\Modules\%capital_name%Controller')->$name($params);
    }
  }

}

