<?php

namespace Atlantis\Helpers;

/**
 *  This is a thin wrapper around Composer's Autoloader
 * to help us dynamically autoload classes from modules
 */
class Loader extends \Composer\Autoload\ClassLoader
{

    public function load($prefix, $paths)
    {

        $this->add($prefix, $paths);

        $this->register();
    }

    public function modules(\Atlantis\Models\Modules $modules)
    {
        $active_modules = $modules::where("active", "=", 1)->get();

        foreach ($active_modules as $module) {

            /** if the config values from the DB are not empty **/
            if (!empty($module->namespace) && !empty($module->path)) {

                /** load the class Psr-0 style **/
                $this->load("{$module->namespace}\\", base_path() . "/modules/" . $module->path);

                /** register **/
                \App::register($module->provider);
                
                /** put the module config in the global config
                 *  you can retrieve it like this
                 *
                 *   \Config::get('module.<modulenamespace>') ;
                 *
                 * **/

                $mod_setup = base_path() . config('atlantis.modules_dir') . $module->path . "/" . $module->namespace . '/Setup/Setup.php';

                if (is_file($mod_setup)) {

                    $setup = require_once($mod_setup);

                    \Config::set('module.' . strtolower($module->namespace), $setup);
                }

            }
        }
    }

    public function loadModuleSeed($seed, $path)
    {

        $this->load("{$seed}\\", base_path() . '/modules/' . $path);

    }


}
