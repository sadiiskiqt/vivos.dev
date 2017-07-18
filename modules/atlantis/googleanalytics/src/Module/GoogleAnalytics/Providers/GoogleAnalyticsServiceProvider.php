<?php

namespace Module\GoogleAnalytics\Providers;

/*
 * Provider: GoogleAnalytics
 * @Atlantis CMS
 * v 1.0
 */

class GoogleAnalyticsServiceProvider extends \Illuminate\Support\ServiceProvider
{

  public function register()
  {

    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Setup.php', "googleanalytics.setup"
    );
    
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Config.php', "googleanalytics.config"
    );
    
    $aConfig = \Config::get('googleanalytics.config');
  
    if (isset($aConfig['appBind'])) {
      foreach ($aConfig['appBind'] as $key => $value) {
        $this->app->bind($key, $value);
      }
    }

    $subscriber = new \Module\GoogleAnalytics\Events\GoogleAnalyticsEvent();

    \Event::subscribe($subscriber);

    //routes for modules should be included in the register method to preceed the base routes

    include __DIR__ . '/../../../routes.php';

  }

  public function boot()
  {

    //$a = \App::make('Assets');

    //  load assests if any
    //$a->registerScripts(['jquery' => ['src' => \Html::script('https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'), 'weight' => 2]]);

    /**
     * To register search provider
     *
     * $t = \App::make('Transport');
     *
     *  $t->setEventValue("search.providers", [  'search' => 'Module\GoogleAnalytics\Models\Search' , 'weight' => 10] );
     */

     
    $themeModViewPath = \Atlantis\Helpers\Themes\ThemeTools::getFullThemePath() . '/modules/googleanalytics/views/';

    if (is_dir($themeModViewPath)) {
      $this->loadViewsFrom($themeModViewPath, 'googleanalytics');
    } else {
      $this->loadViewsFrom(__DIR__ . '/../Views/', 'googleanalytics');
    }
    
    $this->loadViewsFrom(__DIR__ . '/../Views/', 'googleanalytics-admin');
    
    /**
    *  call this with trans('googleanalytics::file.key');
    */      
    //$this->loadTranslationsFrom(__DIR__ . '/../Languages', "googleanalytics");

  }

}
