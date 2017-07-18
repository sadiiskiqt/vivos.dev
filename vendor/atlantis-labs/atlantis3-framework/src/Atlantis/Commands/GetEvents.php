<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;

class GetEvents extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $name = "Get Atlantis events";
  protected $signature = 'atlantis:get:events';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Get Atlantis events';

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

    $t = \App::make('Transport');

    $Events = array();
    
    foreach ($t->getRegisteredEvents() as $name => $obj) {

      $Events[$name]['name'] = $name;
      //$Events[$name]['has_listeners'] = \Event::hasListeners($name) ? 'true' : 'false';
    }   
    
    //$headers = ['Name', 'Has Listeners'];
    $headers = ['Name'];
    $this->table($headers, $Events);
  }

}
