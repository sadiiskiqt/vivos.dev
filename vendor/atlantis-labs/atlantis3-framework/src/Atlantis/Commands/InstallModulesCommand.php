<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;

class InstallModulesCommand extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $name = "Run Atlantis modules installation";
  protected $signature = 'atlantis:install:modules';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'This command will install all new modules from /modules folder';

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

    $installer = new \Atlantis\Helpers\Modules\Installer();

    $available = $installer->showAvailableModules();
    
    foreach ($available as $details) {
      
      $check = $installer->isInstalled($details[0]['moduleNamespace']);      
      
      if (!$check) {

        $installer->install($details[0]);
        
        $this->line($details[0]['name'] . ' Installed.');
        
      }
    }

    $this->info('Installation complete.');
  }

}
