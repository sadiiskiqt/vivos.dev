<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AtlantisMigrateCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = "Run Atlantis migrations";
    protected $signature = 'atlantis:migrate {confirm?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run Atlantis migrations';

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

        $confirm = strtolower($this->argument('confirm'));

        if ($confirm == 'force') {
            $this->runMigration();
        } else if ($confirm == 'y') {
            if (getenv('ATLANTIS_INSTALL') == 'true') {
                $this->runMigration();
            } else {
                $this->info('Migrations will be executed after install.');
            }
        } else if ($this->confirm('Do you want to run migrations?')) {
            $this->runMigration();
        }
    }

    private function runMigration() {

        $exitCode = Artisan::call('migrate', [
                    '--path' => "vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Migrations/",
                    "--no-interaction" => "yes",
                    "--force" => "yes"
        ]);
        $this->info('Migrations complete.');
    }

}
