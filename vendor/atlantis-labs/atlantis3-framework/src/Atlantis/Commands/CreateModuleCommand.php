<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;
use Atlantis\Helpers\Tools;

class CreateModuleCommand extends Command {

  public static $_MIGRATION_TIMESTAMP_FORMAT = 'm_d_Y_His';

  /**
   * Default module folder
   * 
   * @var string
   */
  protected $default_dir = 'atlantis';

  /**
   * Module path
   * 
   * @var string
   */
  protected $module_path;

  /**
   * Templates folder
   * 
   * @var string
   */
  protected $templates_path;

  /**
   * Module structure
   * 
   * @var array
   */
  protected $module_struct = array();

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $name = "Create Atlantis Module";
  protected $signature = 'atlantis:create:module {name?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'This command will generate all files for a new blank Atlantis Module';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();

    $this->module_path = base_path() . '/modules';
    $this->templates_path = base_path() . '/vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Stubs/Module_Templates';
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {

    $realName = $this->argument('name');
    $name = Tools::stringToFolderName($this->argument('name'));

    if ($name == NULL) {
      $this->error('Please add module name "atlantis:create:module "<name>"".');
    } else {

      $dir = '';
      if ($this->confirm('Do you want to change default directory "/atlantis"?')) {
        $dir = strtolower(preg_replace("/[^a-zA-Z]+/", "", $this->ask('Type directory')));
      } else {
        $dir = $this->default_dir;
      }

      $this->module_path .= '/' . $dir;

      $this->module_struct = $this->createModuleStruct($name, $dir);

      if (!is_dir($this->module_path . '/' . strtolower($name))) {

        $success = $this->createModule($name, $realName, $dir);

        if ($success) {
          $this->info('Module ' . $realName . ' created.');
        }
      } else {
        $this->error('This module name already exists.');
      }
    }
  }

  private function createModule($name, $realName, $dir) {

    $success = TRUE;

    if ($this->default_dir == $dir) {
      $namespace = 'Module';
    } else {
      $namespace = ucfirst($dir);
    }

    /**
     * create module directories
     */
    foreach ($this->module_struct['dirs'] as $path) {
      if ($success) {
        if (!is_dir($path)) {
          if (mkdir($path)) {
            $this->line('Creating ' . $path);
            $success = TRUE;
          } else {
            $this->error("Module can't be created.");
            $success = FALSE;
          }
        }
      }
    }

    /**
     * create files from templates
     */
    foreach ($this->module_struct['files'] as $file) {
      if ($success) {

        $template = file_get_contents($file['template']);
        $template = str_replace("%mod_dir%", $dir, $template);
        $template = str_replace("%mod_namespace%", $namespace, $template);
        $template = str_replace("%real_name%", $realName, $template);
        $template = str_replace("%capital_name%", ucfirst($name), $template);
        $template = str_replace("%lower_name%", strtolower($name), $template);
        $newFile = fopen($file['path'], 'w+');
        fwrite($newFile, $template);
        fclose($newFile);

        $this->line('Creating ' . $file['path']);
        $success = TRUE;
      }
    }


    return $success;
  }

  private function createModuleStruct($name, $dir) {

    $timestamp = date(self::$_MIGRATION_TIMESTAMP_FORMAT);

    if ($this->default_dir == $dir) {
      $folder = '/src/Module';
    } else {
      $folder = '/src/' . ucfirst($dir);
    }

    return ['dirs' => [
            $this->module_path,
            $this->module_path . '/' . strtolower($name),
            $this->module_path . '/' . strtolower($name) . '/src',
            $this->module_path . '/' . strtolower($name) . $folder,
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name),
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Commands',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Controllers',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Controllers/Admin',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Events',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Migrations',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Models',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Models/Repositories',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Providers',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Languages',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Languages/en',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Seed',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Setup',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Assets',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Vendor',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Helpers',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Traits',        
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Widgets',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Views',
            $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Views/admin',
        ],
        'files' => [
            [
                'path' => $this->module_path . '/' . strtolower($name) . '/src/routes.php',
                'template' => $this->templates_path . '/route.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Commands/' . ucfirst($name) . 'Command.php',
                'template' => $this->templates_path . '/command.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Controllers/' . ucfirst($name) . 'Controller.php',
                'template' => $this->templates_path . '/controller.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Controllers/Admin/' . ucfirst($name) . 'AdminController.php',
                'template' => $this->templates_path . '/admin-controller.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Events/' . ucfirst($name) . 'Event.php',
                'template' => $this->templates_path . '/event.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Migrations/' . $timestamp . '_create_' . strtolower($name) . '_table.php',
                'template' => $this->templates_path . '/migration.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Models/' . ucfirst($name) . '.php',
                'template' => $this->templates_path . '/model.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Models/Search.php',
                'template' => $this->templates_path . '/model-search.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Models/Sitemap.php',
                'template' => $this->templates_path . '/model-sitemap.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Models/Repositories/' . ucfirst($name) . 'Repository.php',
                'template' => $this->templates_path . '/model-repository.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Providers/' . ucfirst($name) . 'ServiceProvider.php',
                'template' => $this->templates_path . '/provider.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Languages/en/messages.php',
                'template' => $this->templates_path . '/messages-trans.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Languages/en/validation.php',
                'template' => $this->templates_path . '/validation-trans.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Seed/' . ucfirst($name) . 'Seeder.php',
                'template' => $this->templates_path . '/seed.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Setup/Setup.php',
                'template' => $this->templates_path . '/setup.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Setup/Config.php',
                'template' => $this->templates_path . '/config.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Traits/' . ucfirst($name) . 'Trait.php',
                'template' => $this->templates_path . '/trait.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Widgets/' . ucfirst($name) . 'Widget.php',
                'template' => $this->templates_path . '/widget.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Views/blank.blade.php',
                'template' => $this->templates_path . '/view.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Views/admin/blank.blade.php',
                'template' => $this->templates_path . '/view-admin.tpl'
            ],
            [
                'path' => $this->module_path . '/' . strtolower($name) . $folder . '/' . ucfirst($name) . '/Views/admin/view-widget.blade.php',
                'template' => $this->templates_path . '/view-widget.tpl'
            ]
        ]
    ];
  }

}
