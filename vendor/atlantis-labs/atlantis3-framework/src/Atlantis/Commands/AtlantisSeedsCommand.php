<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AtlantisSeedsCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = "Run Atlantis seeds";
    protected $signature = 'atlantis:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run Atlantis seeds';

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

        if ($this->confirm('Do you want to run seeds?')) {
            Artisan::call('db:seed', [
                '--class' => 'Atlantis\Seeds\DatabaseSeeder',
                "--no-interaction" => "yes",
                "--force" => "yes"
            ]);

            $this->info('Seeds complete.');
        }
    }

}
