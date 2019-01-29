<?php

namespace BetterFly\Skeleton\Commands;

use Illuminate\Support\Facades\Artisan;

class MakeBuildModuleCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:build_module {moduleName : Module classname} {--dirPathType=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build Betterfly Module';

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

        $moduleName = trim($this->argument('moduleName'));
        $config = $this->getConfigFile($moduleName, true,false);

        if (!$config) {
            Artisan::call('betterfly:create_module', ['moduleName' => $moduleName]);
        }

        $this->call('betterfly:repository', ['moduleName' => $moduleName]);
        $this->call('betterfly:service', ['moduleName' => $moduleName]);
        $this->call('betterfly:controller', ['moduleName' => $moduleName]);
        $this->call('betterfly:request', ['moduleName' => $moduleName]);
        $this->call('betterfly:transformer', ['moduleName' => $moduleName]);
        $this->call('betterfly:route', ['moduleName' => $moduleName]);
        $this->call('betterfly:model', ['moduleName' => $moduleName]);
        $this->call('betterfly:migration', ['moduleName' => $moduleName]);
        $this->call('betterfly:make_view', ['moduleName' => $moduleName,'--file' => ['all']]);

        /*if(File::exists(app_path($moduleName.'.php'))){
            File::move(app_path($moduleName.'.php'), app_path('Modules/'.$moduleName.'/'.$moduleName.'.php'));
        }*/

        $this->comment("\n \n Files Created. Please run betterfly:db_reset to reset/update db changes \n");
    }
}
