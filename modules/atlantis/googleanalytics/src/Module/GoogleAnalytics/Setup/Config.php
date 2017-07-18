<?php

/*
 * Config: GoogleAnalytics
 * @Atlantis CMS
 * v 1.0
 */

return [
   /*
    * $this->app->bind("Module\GoogleAnalytics", "Atlantis\\GoogleAnalytics\\Controllers\\GoogleAnalyticsController");
    * 
    * 'appBind' => [
    *    'Module\GoogleAnalytics' => 'Module\\GoogleAnalytics\\Controllers\\GoogleAnalyticsController'
    *],
    *[
    *    'Module\GoogleAnalytics\CustomController' => 'Module\\GoogleAnalytics\\Controllers\\CustomController'
    *]
    */
    'appBind' => [
        'Module\GoogleAnalytics' => 'Module\\GoogleAnalytics\\Controllers\\GoogleAnalyticsController'
    ]
   ];
