<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Atlantis\Models\Repositories\ModulesRepository;
use Atlantis\Helpers\Tools;

class MigrateModuleCommand extends Command {

    /**
     *  Module path
     * 
     * @var string
     */
    protected $module_path;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = "Run Atlantis Module migration";
    protected $signature = 'atlantis:migrate:module {namespace?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run migrations for a single Atlantis Module';

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

        $namespace = $this->argument('namespace');

        $modules = ModulesRepository::getModule($namespace);

        if ($modules->first() != NULL) {

            $name = Tools::stringToFolderName($modules->first()->name);

            $this->module_path = 'modules/' . $modules->first()->path . '/Module/' . ucfirst($name) . '/Migrations/';

            if (is_dir($this->module_path)) {

                Artisan::call('migrate', [
                    '--path' => $this->module_path,
                    "--no-interaction" => "yes",
                    "--force" => "yes"
                ]);

                $this->info('Success.');
            } else {
                $this->error('Invalid module path or module not installed.');
            }
        } else {
            $this->error('Please add module namespace "atlantis:migrate:module "<module_namespace>"".');
        }
    }

}
