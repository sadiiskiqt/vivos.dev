<?php

 namespace Module\Accommodations\Traits;

/**
 * Helper trait for extending AccommodationsController
 */
trait AccommodationsTrait {

  public function __call($name, $params) {

    /**
     * create controller in site/src/Module/Site/Controllers/Modules/AccommodationsController.php
     */
    if (class_exists('Module\Site\Controllers\Modules\AccommodationsController')) {

      return \App::make('Module\Site\Controllers\Modules\AccommodationsController')->$name($params);
    }
  }

}

