<?php

namespace Module\Site\Providers;

/*
 * Provider: Site
 * @Atlantis CMS
 * v 1.0
 */

class SiteServiceProvider extends \Illuminate\Support\ServiceProvider {

  public function register() {


    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Config.php', "site.config"
    );

    $aConfig = \Config::get('site.config');

    if (isset($aConfig['appBind'])) {
      foreach ($aConfig['appBind'] as $key => $value) {
        $this->app->bind($key, $value);
      }
    }

    //$subscriber = new \Module\Site\Events\SiteEvent();

    //\Event::subscribe($subscriber);

    
    include __DIR__ . '/../../../routes.php';
  }

  public function boot() {
    
    $themeModViewPath = \Atlantis\Helpers\Themes\ThemeTools::getFullThemePath() . '/modules/site/views/';

    if (is_dir($themeModViewPath)) {
      $this->loadViewsFrom($themeModViewPath, 'site');
    } else {
      $this->loadViewsFrom(__DIR__ . '/../Views/', 'site');
    }
    
  }

}
