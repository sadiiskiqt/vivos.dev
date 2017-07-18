<?php

namespace Atlantis\Commands;

use Illuminate\Console\Command;
use Atlantis\Helpers\Tools;

class CreateThemeCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'atlantis:create:theme';

	 /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = "Create Theme";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run Atlantis Create Theme';
	
	
    /**
     * @var string
     */
    protected $sPath = 'resources/themes';


    public function __construct()
    {
        parent::__construct();
        $this->sPath = base_path() . '/resources/themes/';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $sThemeName = $this->ask('Theme Name');

        if (preg_match("/^[a-z0-9.]+$/i", $sThemeName) != true) {
            $this->line('Only letters and numbers allowed for the Theme ' . $sThemeName . '!');
            $sThemeName = $this->ask('Theme Name');
        }
        $dst = $this->sPath . "/" . $sThemeName;
        $src = $this->sPath . "/theme101";

        $sVersion = $this->ask('Version');
        if (preg_match('/^[1-9][0-9]*$/', $sVersion) != true) {
            $this->error('Only numbers allowed for the Theme Version!');
            $sVersion = $this->ask('Version');
        }
        $sAuthor = $this->ask('Author');
        $sDescription = $this->ask('Description');

        if (!is_dir($dst)) {

            \File::copyDirectory($src, $dst);
            \File::delete($dst . '/config.php');

            $sConfigFilePath = base_path() . '/vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Stubs/Theme_Template/config.tpl';
            $template = file_get_contents($sConfigFilePath);
            $template = str_replace("%name%", ucfirst($sThemeName), $template);
            $template = str_replace("%version%", ucfirst($sVersion), $template);
            $template = str_replace("%author%", ucfirst($sAuthor), $template);
            $template = str_replace("%description%", ucfirst($sDescription), $template);

            $newFile = fopen($dst . "/config.php", 'w+');
            fwrite($newFile, $template);
            fclose($newFile);

            \File::deleteDirectory($dst . '/lang/bg/');
            \File::deleteDirectory($dst . '/modules/openweather/');

            $this->line('Theme ' . $sThemeName . ' was successfully created!');

        } else {
            $this->error('The directory ' . $sThemeName . ' already exists!');
        }

    }

}
