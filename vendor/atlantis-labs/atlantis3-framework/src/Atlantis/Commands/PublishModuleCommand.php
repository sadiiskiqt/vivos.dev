<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;
use Atlantis\Models\Repositories\ModulesRepository;
use Atlantis\Helpers\Tools;
use Atlantis\Helpers\Modules\Updater;

class PublishModuleCommand extends Command {

  /**
   *  Module path
   * 
   * @var string
   */
  protected $module_path;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $name = "Run Atlantis Module publishing";
  protected $signature = 'atlantis:publish:module {namespace?}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'This command will publish installed Atlantis Module';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {

    $namespace = $this->argument('namespace');

    $module = ModulesRepository::getModule($namespace)->first();

    if ($module != NULL && is_dir(base_path(config('atlantis.modules_dir') . '/' . $module->path))) {

      $_email = $this->ask('Email');
      $_password = $this->secret('Password');

      $modulePath = str_replace('/', '', config('atlantis.modules_dir')) . '/' . Tools::getParentFolderPath($module->path);
      $moduleFolder = last(array_filter(explode('/', $modulePath)));
      $filenameZIP = $moduleFolder . '.zip';
      $publishFolder = Updater::$_PUBLISH_STORAGE . '/' . time() . '-' . $moduleFolder . '/';
      $publishZIPpath = $publishFolder . $filenameZIP;
      $moduleJSONFile = config('atlantis.modules_dir') . $module->path . '/module.json';

      /**
       * make module.json file from setup.php
       */
      $this->line('Making module.json from setup.php...');
      $setupFile = Tools::getModuleFileSetup($module->path);
      if (!\Storage::disk('local')->put($moduleJSONFile, json_encode($setupFile, JSON_PRETTY_PRINT))) {
        $this->error('Can not create module.json file');
        exit();
      }

      $this->line('Changing module.json permissions to 0775...');
      if (!chmod(base_path($moduleJSONFile), 0775)) {
        $this->error('Can not change module.json permissions');
        exit();
      }

      /**
       * make module archive
       */
      $this->line('Making module archive...');
      \Zipper::make(base_path($publishZIPpath))->folder($moduleFolder)->add(base_path($modulePath))->close();
      if (!\Storage::disk('local')->has($publishZIPpath)) {
        $this->error('Can not archive the module.');
        exit();
      }

      /**
       * publish module
       */
      $this->line('Publishing...');
      $result = $this->publish($publishZIPpath, $filenameZIP, $_email, $_password);

      if (isset($result['success'])) {
        $this->info($result['success']);
      } else if (isset($result['error'])) {
        $this->error($result['error']);
      } else {
        $this->error('Something went wrong.');
      }

      /**
       * delete archived module
       */
      \Storage::disk('local')->deleteDirectory($publishFolder);
    } else {
      $this->error('Invalid module namespace "atlantis:publish:module "<module_namespace>"".');
    }
  }

  private function publish($publishZIPpath, $filenameZIP, $_email, $_password) {

    $filesize = \Storage::disk('local')->size($publishZIPpath);

    $client = new \GuzzleHttp\Client();

    try {
      $body = \GuzzleHttp\Psr7\stream_for(fopen(base_path($publishZIPpath), 'r'));
      $res = $client->request('POST', 'http://modules.atlantis-cms.com/api/publish-module', [
          'body' => $body,
          'headers' => [
              'filename' => $filenameZIP,
              'email' => $_email,
              'password' => $_password,
              'filesize' => $filesize
          ]
      ]);
    } catch (\Exception $e) {
      return ['error' => $e->getMessage()];
    }

    return json_decode($res->getBody()->getContents(), TRUE);
  }

}
