<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;
use Atlantis\Helpers\Tools;

class SetDatabaseCommand extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $name = "Set Database credential";
  protected $signature = 'atlantis:set:db';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'This command will run database configuration';

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
    
    if ($this->confirm('Do you want to set database credential?')) {
      
      $db_host = $this->ask('Host');
      $db_database = $this->ask('Database name');
      $db_username = $this->ask('Username');
      $db_password = $this->secret('Password');
      
      Tools::setDotenvVar('DB_HOST', $db_host);
      Tools::setDotenvVar('DB_DATABASE', $db_database);
      Tools::setDotenvVar('DB_USERNAME', $db_username);
      Tools::setDotenvVar('DB_PASSWORD', $db_password);
      
      $this->info("Database configuration is changed");
      
    }
    
  }
  
}
