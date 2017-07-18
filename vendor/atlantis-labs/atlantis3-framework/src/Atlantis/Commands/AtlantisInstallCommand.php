<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;
use Atlantis\Helpers\Tools;

class AtlantisInstallCommand extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $name = "Run Atlantis installation";
  protected $signature = 'atlantis:install';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'This command will run Atlantis installation';

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

    if (getenv('ATLANTIS_INSTALL') != 'true') {
   
      $this->call('atlantis:migrate', ['force']);
      $this->call('atlantis:seed');

      Tools::setDotenvVar('ATLANTIS_INSTALL', 'true');

      $this->info('Installation complete.');
    } else {
      $this->error('Atlantis is already installed');
    }
  }

}
