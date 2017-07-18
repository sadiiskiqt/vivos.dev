<?php

/*
 * Config: %capital_name%
 * @Atlantis CMS
 * v 1.0
 */

return [
   /*
    * $this->app->bind("%mod_namespace%\%capital_name%", "Atlantis\\%capital_name%\\Controllers\\%capital_name%Controller");
    * 
    * 'appBind' => [
    *    '%mod_namespace%\%capital_name%' => '%mod_namespace%\\%capital_name%\\Controllers\\%capital_name%Controller'
    *],
    *[
    *    '%mod_namespace%\%capital_name%\CustomController' => '%mod_namespace%\\%capital_name%\\Controllers\\CustomController'
    *]
    */
    'appBind' => [
        '%mod_namespace%\%capital_name%' => '%mod_namespace%\\%capital_name%\\Controllers\\%capital_name%Controller'
    ]
   ];
