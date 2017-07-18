<?php

namespace %mod_namespace%\%capital_name%\Providers;

/*
 * Provider: %capital_name%
 * @Atlantis CMS
 * v 1.0
 */

class %capital_name%ServiceProvider extends \Illuminate\Support\ServiceProvider
{

  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
      \Module\%capital_name%\Commands\%capital_name%Command::class
  ];

  public function register()
  {

    /** Register artisan commands * */
    //$this->commands($this->commands);
  
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Setup.php', "%lower_name%.setup"
    );
    
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Config.php', "%lower_name%.config"
    );
    
    $aConfig = \Config::get('%lower_name%.config');
  
    if (isset($aConfig['appBind'])) {
      foreach ($aConfig['appBind'] as $key => $value) {
        $this->app->bind($key, $value);
      }
    }

    $subscriber = new \%mod_namespace%\%capital_name%\Events\%capital_name%Event();

    \Event::subscribe($subscriber);   

    //routes for modules should be included in the register method to preceed the base routes

    include __DIR__ . '/../../../routes.php';
    
    /**
     * register widgets
     */
    //\Atlantis\Widgets\Register::set(\%mod_namespace%\%capital_name%\Widgets\%capital_name%Widget::class, \Config::get('%lower_name%.setup'));

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
     *  $t->setEventValue("search.providers", [  'search' => '%mod_namespace%\%capital_name%\Models\Search' , 'weight' => 10] );
     *  $t->setEventValue("sitemap.providers", [  'sitemap' => '%mod_namespace%\%capital_name%\Models\Sitemap' , 'weight' => 10] );
     */

     
    $themeModViewPath = \Atlantis\Helpers\Themes\ThemeTools::getFullThemePath() . '/modules/%lower_name%/views/';

    if (is_dir($themeModViewPath)) {
      $this->loadViewsFrom($themeModViewPath, '%lower_name%');
    } else {
      $this->loadViewsFrom(__DIR__ . '/../Views/', '%lower_name%');
    }
    
    $this->loadViewsFrom(__DIR__ . '/../Views/', '%lower_name%-admin');
    
    /**
    *  call this with trans('%lower_name%::file.key');
    */      
    //$this->loadTranslationsFrom(__DIR__ . '/../Languages', "%lower_name%");

  }

}
