<?php

/*
 * Config: Accommodations
 * @Atlantis CMS
 * v 1.0
 */

return [
   /*
    * $this->app->bind("Module\Accommodations", "Atlantis\\Accommodations\\Controllers\\AccommodationsController");
    * 
    * 'appBind' => [
    *    'Module\Accommodations' => 'Module\\Accommodations\\Controllers\\AccommodationsController'
    *],
    *[
    *    'Module\Accommodations\CustomController' => 'Module\\Accommodations\\Controllers\\CustomController'
    *]
    */
    'appBind' => [
        'Module\Accommodations' => Module\Accommodations\Controllers\AccommodationsController::class
    ]
   ];
