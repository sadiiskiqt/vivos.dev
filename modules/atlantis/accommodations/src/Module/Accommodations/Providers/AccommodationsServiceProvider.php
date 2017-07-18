<?php

namespace Module\Accommodations\Providers;

/*
 * Provider: Accommodations
 * @Atlantis CMS
 * v 1.0
 */

class AccommodationsServiceProvider extends \Illuminate\Support\ServiceProvider
{

  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
      \Module\Accommodations\Commands\AccommodationsCommand::class
  ];

  public function register()
  {

    /** Register artisan commands * */
    //$this->commands($this->commands);
  
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Setup.php', "accommodations.setup"
    );
    
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Config.php', "accommodations.config"
    );
    
    $aConfig = \Config::get('accommodations.config');
  
    if (isset($aConfig['appBind'])) {
      foreach ($aConfig['appBind'] as $key => $value) {
        $this->app->bind($key, $value);
      }
    }

    $subscriber = new \Module\Accommodations\Events\AccommodationsEvent();

    \Event::subscribe($subscriber);   

    //routes for modules should be included in the register method to preceed the base routes

    include __DIR__ . '/../../../routes.php';
    
    /**
     * register widgets
     */
    //\Atlantis\Widgets\Register::set(\Module\Accommodations\Widgets\AccommodationsWidget::class, \Config::get('accommodations.setup'));

  }

  public function boot()
  {

    //  load assests if any
    // \Atlantis\Helpers\Assets::registerScript('https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js', 10);

    /**
     * To register search and sitemap provider
     *
     * $t = \App::make('Transport');
     *
     *  $t->setEventValue("search.providers", [  'search' => 'Module\Accommodations\Models\Search' , 'weight' => 10] );
     *  $t->setEventValue("sitemap.providers", [  'sitemap' => 'Module\Accommodations\Models\Sitemap' , 'weight' => 10] );
     */

     
    $themeModViewPath = \Atlantis\Helpers\Themes\ThemeTools::getFullThemePath() . '/modules/accommodations/views/';

    if (is_dir($themeModViewPath)) {
      $this->loadViewsFrom($themeModViewPath, 'accommodations');
    } else {
      $this->loadViewsFrom(__DIR__ . '/../Views/', 'accommodations');
    }
    
    $this->loadViewsFrom(__DIR__ . '/../Views/', 'accommodations-admin');
    
    /**
    *  call this with trans('accommodations::file.key');
    */      
    //$this->loadTranslationsFrom(__DIR__ . '/../Languages', "accommodations");

  }

}
