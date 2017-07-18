<?php

 namespace %mod_namespace%\%capital_name%\Commands;

/*
 * Command: %capital_name%Command
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Console\Command;

class %capital_name%Command extends Command
{

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $name = "Dummy name";
  protected $signature = '%lower_name%:dummy';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Dummy description';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() 
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() 
  {

    $this->info('Dummy command completed.');
  }

}
