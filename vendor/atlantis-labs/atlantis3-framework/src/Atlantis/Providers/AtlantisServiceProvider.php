<?php

namespace Atlantis\Providers;

use Atlantis\Helpers\Tools;
use Illuminate\Support\ServiceProvider;
use Atlantis\Models\Repositories\ConfigRepository as AtlantisConfig;
use Illuminate\Foundation\AliasLoader;
use Atlantis\Helpers\Themes\ThemeTools;

class AtlantisServiceProvider extends ServiceProvider
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Atlantis\Commands\CreateModuleCommand::class,
        \Atlantis\Commands\MigrateModuleCommand::class,
        \Atlantis\Commands\AtlantisInstallCommand::class,
        \Atlantis\Commands\SetDatabaseCommand::class,
        \Atlantis\Commands\AtlantisMigrateCommand::class,
        \Atlantis\Commands\AtlantisSeedsCommand::class,
        \Atlantis\Commands\UnlockExpiredItemsCommand::class,
        \Atlantis\Commands\InstallModulesCommand::class,
        \Atlantis\Commands\GetEvents::class,
        \Atlantis\Commands\KeyGenerateCommand::class,
        \Atlantis\Commands\PublishModuleCommand::class,
        \Atlantis\Commands\CreateThemeCommand::class,

    ];

    public function register()
    {

        $aProviders = require_once(__DIR__ . '/../../Atlantis/Providers.php');

        /** register service providers * */
        foreach ($aProviders['providers'] as $provider)
        {
            if (!empty($provider))
            {
                $this->app->register($provider);
            }
        }

        /** register aliases providers * */
        foreach ($aProviders['aliases'] as $class => $alias)
        {
            AliasLoader::getInstance()->alias($class, $alias);
        }

        /** Register artisan commands * */
        $this->commands($this->commands);

        /** if atlantis is installed * */
        if (getenv('ATLANTIS_INSTALL') == 'true')
        {

            $this->app->bind('Atlantis\\Models\\Interfaces\\IPageInterface', 'Atlantis\\Models\\Repositories\\PageRepository');
            $this->app->bind('Atlantis\\Models\\Interfaces\\IPatternInterface', 'Atlantis\\Models\\Repositories\\PatternRepository');

            /*
              * Maat Excel Laravel 5.3 fix, bind the contract to the implementation as Maat Excel uses the Contract in:
              *
              * line 124, ExcelServiceProvider.php
              */
            $this->app->bind(
                'Illuminate\Contracts\Bus\Dispatcher', 'Illuminate\Bus\Dispatcher'
            );

            $this->app->singleton('Transport', function ($app)
            {
                return new \Atlantis\Helpers\Transport();
            });

            $this->app->singleton('WidgetRegister', function ($app)
            {
                return new \Atlantis\Widgets\Register;
            });

            $this->app->singleton('Assets', function ($app)
            {
                return new \Atlantis\Helpers\Assets;
            });

            $this->app->singleton('AtlantisRedirect', function ($app)
            {
                return new \Atlantis\Helpers\AtlantisRedirect;
            });

            $this->app->singleton('MobileDetect', function ($app)
            {
                return new \Atlantis\Helpers\Lib\MobileDetect();
            });

            /**
             * register custom validation for pages
             */
            \Illuminate\Support\Facades\Validator::extend('valid_url', 'Atlantis\Models\Repositories\PageRepository@validUrl');
            \Illuminate\Support\Facades\Validator::extend('valid_path', 'Atlantis\Models\Repositories\PageRepository@validPath');

            /**
             *  Lets register all the events in the Transport here
             */
            $t = \App::make('Transport');

            /** set loaded page object */
            $t->registerEvent('page.loaded');

            /** Fired before the page is resolved * */
            $t->registerEvent('page.prediscovery');

            /** Fired when page is resolved, sets page title * */
            $t->registerEvent('page.title');

            /** Removes everything that is between [nomobile] tags * */
            //$t->registerEvent('page.nomobile');

            /** Override page body content * */
            $t->registerEvent('page.body');

            /** Sets the body class per page * */
            $t->registerEvent('page.body_class');

            /** Sets SEO title * */
            $t->registerEvent('page.seo_title');

            /** Sets Meta Keywords * */
            $t->registerEvent('page.meta_keywords');

            /** Sets Meta Description * */
            $t->registerEvent('page.meta_description');

            /** Override the page template before the page is outputted * */
            $t->registerEvent('page.template');

            /** Sets tracking scripts in header * */
            $t->registerEvent('page.tracking_header');

            /** Sets tracking scripts in footer * */
            $t->registerEvent('page.tracking_footer');

            /** Fires when page is created in the admin */
            $t->registerEvent('page.created');

            /** Fires when page is edited in the admin */
            $t->registerEvent('page.edited');

            /** Fires when page is deleted */
            $t->registerEvent('page.deleted');

            /** Fires when new pattern is created */
            $t->registerEvent('pattern.created');

            /** Fires when new pattern is edited */
            $t->registerEvent('pattern.edited');

            /** Fires when new pattern is deleted */
            $t->registerEvent('pattern.deleted');

            /** Checks if module is loaded * */
            $t->registerEvent('module.loaded');

            $t->registerEvent('search.providers');
            $t->registerEvent('sitemap.providers');

            $t->registerEvent('file.uploaded');

            $t->registerEvent('admin.login');

            $t->registerEvent('user.created');
            $t->registerEvent('user.updated');
            $t->registerEvent('user.deleted');
        }
    }

    public function boot()
    {

        if (getenv('ATLANTIS_INSTALL') == 'true')
        {

            /**
             * Load the Atlantis config into the Config object
             */
            //\Atlantis\Helpers\Cache\AtlantisCache::clearAll();

            $aConfig = \Atlantis\Helpers\Cache\AtlantisCache::rememberQuery('config-atlantis', array(), function ()
            {

                $atlantis_config = AtlantisConfig::getAll();

                $aConfig = array();
                foreach ($atlantis_config as $ac)
                {
                    $aConfig[$ac->config_key] = unserialize($ac->config_value);
                }

                return $aConfig;

            }, TRUE);

            /**
             * $atlantis_config = AtlantisConfig::getAll();
             *
             * $aConfig = array();
             * foreach ($atlantis_config as $ac) {
             * $aConfig[$ac->config_key] = unserialize($ac->config_value);
             * //\Config::set($ac->config_key, unserialize($ac->config_value));
             * }
             *
             */

            /** append the framework version as well */

            $aConfig['framework_version'] = Tools::getFrameworkVersion();

            \Config::set('atlantis', $aConfig);

            /**
             *  Read the layout views for the site from the "layout" dir.
             */
            $themePath = ThemeTools::getFullThemePath();

            \View::addLocation($themePath . "/views");

            $this->loadViewsFrom($themePath . "/views", "atlantis");
            $this->loadViewsFrom(__DIR__ . '/../../Atlantis/Views', "atlantis-admin");

            /**
             *  call this with trans('site::file.key');
             */
            $this->loadTranslationsFrom($themePath . "/lang/", "site");
            $this->loadTranslationsFrom(__DIR__ . '/../../Atlantis/Languages', "admin");

            /**
             *  We have to register here the default scripts to precede the modules assets
             * TODO: load them from the Config object
             */
            /**
             *  Load Atlantis Modules
             */
            $loader = new \Atlantis\Helpers\Loader();
            $loader->modules(new \Atlantis\Models\Modules);

            /** register the body class event * */
            \Event::subscribe(new \Atlantis\Events\PageBodyClass());

            /** register the nomobile event* */
            //\Event::subscribe(new \Atlantis\Events\NoMobileEvent());

            /** register file upload event * */
            \Event::subscribe(new \Atlantis\Events\FileUploaded);

            /** Create a View Composer to feed the shell at all times * */
            \View::composer('atlantis::' . config('atlantis.frontend_shell_view'), function ($view)
            {

                $a = \App::make('Assets');

                $t = \App::make('Transport');

                \Event::fire('page.tracking_header');

                $view->with("_scripts", $a->getRegisteredScripts());
                $view->with("_styles", $a->getRegisteredStyles());
                $view->with("_headTags", $a->getRegisteredHeadTags());
                $view->with("_js", $a->getRegisteredJSs());

                $view->with("body_class", $t->getEvent("page.body_class"));
                //dd($t->getEvent('page.tracking_header'));
                $view->with('tracking_header', $t->getEvent('page.tracking_header'));
                $view->with('tracking_footer', $t->getEvent('page.tracking_footer'));
            });

            /** Create a View Composer to feed the admin shell at all times * */
            \View::composer('atlantis-admin::admin-shell', function ($view)
            {

                $a = \App::make('Assets');

                $view->with("_scripts", $a->getRegisteredScripts());
                $view->with("_styles", $a->getRegisteredStyles());
                $view->with("_js", $a->getRegisteredJSs());
            });


            /**
             * Include Atlantis Routes
             */
            include __DIR__ . '/../../routes.php';
        }
    }

}
