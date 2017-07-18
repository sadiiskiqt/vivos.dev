<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;

class UnlockExpiredItemsCommand extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $name = "Unlock expired items";
  protected $signature = 'atlantis:unlock-expired-items';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = "Unlocks all locked items older that 10 minutes";

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

    \Atlantis\Helpers\LockedItems::unlockAllExpiredItems(10);
    $this->info('Process completed.');
  }

}
