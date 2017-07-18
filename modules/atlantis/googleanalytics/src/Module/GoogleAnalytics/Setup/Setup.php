<?php

/*
 * Setup: GoogleAnalytics
 * @Atlantis CMS
 * v 1.3
 */

return [
    'name' => 'Google Analytics',
    'author' => 'Atlantis CMS',
    'version' => '1.3',
    'adminURL' => 'admin/modules/googleanalytics', // admin/modules/googleanalytics
    /**
     * ex. icon icon-Files
     * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
     * 
     * ex. fa fa-beer
     * http://fontawesome.io/icons/
     */
    'icon' => 'icon icon-ChartUp',
    'path' => 'atlantis/googleanalytics/src',
    'moduleNamespace' => 'Module\GoogleAnalytics',
    'seedNamespace' => 'Module\GoogleAnalytics\Seed',
    'seeder' => '\Module\GoogleAnalytics\Seed\GoogleAnalyticsSeeder',
    'provider' => 'Module\GoogleAnalytics\Providers\GoogleAnalyticsServiceProvider',
    'migration' => 'modules/atlantis/googleanalytics/src/Module/GoogleAnalytics/Migrations/',
    'extra' => NULL,
    'description' => 'Embeds Google Analytics or Google Tag Manager tracking code'
];
