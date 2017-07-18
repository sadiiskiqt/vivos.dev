<?php

/*
 * Config: Site
 * @Atlantis CMS
 * v 1.0
 */

return [
   /*
    * $this->app->bind("Module\Site", "Atlantis\\Site\\Controllers\\SiteController");
    * 
    * 'appBind' => [
    *    'Module\Site' => 'Module\\Site\\Controllers\\SiteController'
    *],
    *[
    *    'Module\Site\CustomController' => 'Module\\Site\\Controllers\\CustomController'
    *]
    */  
    'appBind' => [
        'Module\Site' => 'Module\\Site\\Controllers\\SiteController'
    ]  
   ];
