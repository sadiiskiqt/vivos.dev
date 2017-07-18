<?php

namespace Atlantis\Helpers\Modules;

use Atlantis\Models\Repositories\ModulesRepository;

/*
  \Artisan::call('migrate', [
  '--path'   => "modules/atlantis/news/src/News/Migrations/"
  ]);
 * 
 * 
 *  $load  = new \Atlantis\Helpers\Loader();

  $load->loadModuleSeed('News\Seed', 'news/src/');

  \Artisan::call('db:seed', [
  '--class' => '\News\Seed\NewsSeeder'
  ]);


  $installer = new \Atlantis\Helpers\Modules\Installer();

  $available = $installer->showAvailableModules();

  foreach ($available as $details ) {

  $check = $installer->isInstalled($details[0]['moduleNamespace']);

  if ( !$check ) {

  $installer->install($details[0]);

  }
  }


 * 
 */

class Installer {

    public function __constuct() {
        
    }

    public function install($modDetails) {

        $modulesDB = ModulesRepository::getModule($modDetails['moduleNamespace']);

        if ($modulesDB->isEmpty()) {

            $load = new \Atlantis\Helpers\Loader();

            /* we are skipping the blank module , its only a bootstrap for development */

            if ($modDetails["path"] != "blank/src") {

                $load->loadModuleSeed($modDetails['seedNamespace'], $modDetails['path']);

                $this->migrate($modDetails['migration']);

                if (!empty($modDetails['seeder'])) {

                    $this->seed($modDetails['seeder']);
                }
            }
        } else {
            //throw exception if namespace already exists in modules table.
            abort(500, 'Namespace ' . $modDetails['moduleNamespace'] . ' already exists');
        }
    }

    public function seed($class) {
        \Artisan::call('db:seed', [
        '--class' => $class,
        '--force' =>"yes",
        "--no-interaction" => "yes"
        ]);
    }

    public function migrate($path) {

        \Artisan::call('migrate', [
            '--path' => $path,
            '--force' => "yes",
            "--no-interaction" => "yes"
        ]);
    }

    public function showAvailableModules() {

        $dir_iterator = new \RecursiveDirectoryIterator(base_path() . "/modules/");

        $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);

        $regex = new \RegexIterator($iterator, '/.+Setup\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        $foundSetups = array();

        foreach ($regex as $r) {

            $moduleSeed = require($r[0]);

            $foundSeeds[$moduleSeed['name']][] = $moduleSeed;
        }

        return $foundSeeds;
    }

    public function isInstalled($modNamespace) {

        $module = ModulesRepository::getModule($modNamespace)->first();

        if ($module != NULL) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function isActive($mod) {

        $model = new \Atlantis\Models\Modules();

        if (count($model->where("name", "=", $mod)
                                ->where("active", "=", 1)
                                ->first())) {
            return true;
        }

        return false;
    }

}
