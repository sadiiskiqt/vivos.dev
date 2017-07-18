<?php

/*
 * Config: Menus
 * @Atlantis CMS
 * v 1.0
 */

return [
   /*
    * $this->app->bind("Module\Menus", "Atlantis\\Menus\\Controllers\\MenusController");
    * 
    * 'appBind' => [
    *    'Module\Menus' => 'Module\\Menus\\Controllers\\MenusController'
    *],
    *[
    *    'Module\Menus\CustomController' => 'Module\\Menus\\Controllers\\CustomController'
    *]
    */
    
    'appBind' => [
        'Module\Menu' => \Module\Menus\Helpers\MenuBuilder::class
    ]
   ];
