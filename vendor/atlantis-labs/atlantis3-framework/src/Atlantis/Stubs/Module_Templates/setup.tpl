<?php


/*
 * Setup: %capital_name%
 * @Atlantis CMS
 * v 1.0
 */

return [
    'name' => '%real_name%',
    'author' => 'Atlantis CMS',
    'version' => '1.0',
    'adminURL' => NULL, // admin/modules/%lower_name%
    /**
     * ex. icon icon-Files
     * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
     * 
     * ex. fa fa-beer
     * http://fontawesome.io/icons/
     */
    'icon' => 'icon icon-Planet',
    'path' => '%mod_dir%/%lower_name%/src',
    'moduleNamespace' => '%mod_namespace%\%capital_name%',
    'seedNamespace' => '%mod_namespace%\%capital_name%\Seed',    
    'seeder' => '\%mod_namespace%\%capital_name%\Seed\%capital_name%Seeder',
    'provider' => '%mod_namespace%\%capital_name%\Providers\%capital_name%ServiceProvider',
    'migration' => 'modules/%mod_dir%/%lower_name%/src/%mod_namespace%/%capital_name%/Migrations/',
    'extra' => NULL,
    'description' => ''
   ];
