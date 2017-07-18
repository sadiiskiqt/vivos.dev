<?php

namespace Atlantis\Helpers\Modules;

use Atlantis\Models\Repositories\ModulesRepository;
use Illuminate\Support\Facades\DB;

class Uninstaller {

  private $moduleID;
  private $success;
  private $error_messages = array();
  private $success_messages = array();

  public function __construct($module_id) {
    $this->moduleID = $module_id;
  }

  public function uninstall() {

    $module = ModulesRepository::getInstalledModuleByID($this->moduleID);

    if ($module != NULL) {

      $fileSetup = \Atlantis\Helpers\Tools::getModuleFileSetup($module->path);

      $migrationFiles = \Atlantis\Helpers\Iterator::getFiles('/' . $fileSetup['migration'], "WITHOUT EXT");

      $this->rollbackMigrations($migrationFiles, $fileSetup['migration']);

      $this->removeFromMigrationTable($migrationFiles);

      $module->delete();

      $this->addSuccessMessage('The module was successfuly uninstalled. Please delete module folder from atlantis');
      $this->success = TRUE;
    } else {
      $this->addErrorMessage('Invalid module id');
      $this->success = FALSE;
    }
  }

  public function isSuccessful() {

    if ($this->success == NULL) {
      return FALSE;
    } else {
      return $this->success;
    }
  }

  public function getMessages() {

    if ($this->success == NULL) {
      $this->addErrorMessage('Uninstall is not completed');
    }

    if ($this->success) {
      return $this->success_messages;
    } else {
      return $this->error_messages;
    }
  }

  private function addSuccessMessage($message) {
    array_push($this->success_messages, $message);
  }

  private function addErrorMessage($message) {
    array_push($this->error_messages, $message);
  }

  private function rollbackMigrations($migrationFiles, $migrationPath) {

    $model = DB::table('migrations')
            ->whereIn('migration', $migrationFiles)
            ->orderBy('batch', 'DESC')
            ->get();

    foreach ($model as $m) {

      $migrationName = $m->migration;

      include($migrationPath . $migrationName . '.php');

      $aStripName = explode('_', $migrationName);

      foreach ($aStripName as $key => $part) {
        if (is_numeric($part)) {
          unset($aStripName[$key]);
        } else {
          $aStripName[$key] = ucfirst($part);
        }
      }

      $className = implode('', $aStripName);

      \App::make($className)->down();
    }
  }

  private function removeFromMigrationTable($migrationFiles) {

    foreach ($migrationFiles as $file) {
      DB::table('migrations')
              ->where("migration", "=", $file)
              ->delete();
    }
  }

}
