<?php

namespace Module\Menus\Providers;

/*
 * Provider: Menus
 * @Atlantis CMS
 * v 1.0
 */

class MenusServiceProvider extends \Illuminate\Support\ServiceProvider
{

  public function register()
  {

    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Setup.php', "menus.setup"
    );
    
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Config.php', "menus.config"
    );
    
    $aConfig = \Config::get('menus.config');
  
    if (isset($aConfig['appBind'])) {
      foreach ($aConfig['appBind'] as $key => $value) {
        $this->app->bind($key, $value);
      }
    }

    $subscriber = new \Module\Menus\Events\MenusEvent();

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
     *  $t->setEventValue("search.providers", [  'search' => 'Module\Menus\Models\Search' , 'weight' => 10] );
     */

     
    $themeModViewPath = \Atlantis\Helpers\Themes\ThemeTools::getFullThemePath() . '/modules/menus/views/';

    if (is_dir($themeModViewPath)) {
      $this->loadViewsFrom($themeModViewPath, 'menus');
    } else {
      $this->loadViewsFrom(__DIR__ . '/../Views/', 'menus');
    }
    
    $this->loadViewsFrom(__DIR__ . '/../Views/', 'menus-admin');

  }

}
