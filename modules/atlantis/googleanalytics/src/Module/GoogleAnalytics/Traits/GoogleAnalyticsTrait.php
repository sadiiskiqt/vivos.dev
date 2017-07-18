<?php

namespace Module\GoogleAnalytics\Traits;

/**
 * Helper trait for extending GoogleAnalyticsController
 */
trait GoogleAnalyticsTrait {

  public function __call($name, $params) {
   
    /**
     * create controller in site/src/Module/Site/Controllers/Modules/GoogleAnalyticsController.php
     */
    if (class_exists('Module\Site\Controllers\Modules\GoogleAnalyticsController')) {

      return \App::make('Module\Site\Controllers\Modules\GoogleAnalyticsController')->$name($params);
    }
  }

}
