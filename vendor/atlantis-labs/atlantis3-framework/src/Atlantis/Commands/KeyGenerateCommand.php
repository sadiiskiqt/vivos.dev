<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;
use Atlantis\Helpers\Tools;

class KeyGenerateCommand extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $name = "Set Atlantis key";
  protected $signature = 'atlantis:key:generate';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'This command will set Atlantis key';

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
    
    $key = Tools::makeAtlantisKey();
    
    Tools::setDotenvVar('ATLANTIS_KEY', $key);

    $this->info("Atlantis key [$key] set successfully.");
  }

}
