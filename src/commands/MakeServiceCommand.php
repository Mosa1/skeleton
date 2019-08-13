<?php

namespace BetterFly\Skeleton\Commands;

use BetterFly\Skeleton\Commands\BaseCommand;

class MakeServiceCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:service {moduleName : Module classname} {--dirPathType=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making Betterfly Service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $className = trim($this->argument('moduleName'));
        $this->createProgressbar();

        $dirPath = $this->getDirPath($className, 'Service');
        $this->validateDirPath($dirPath);

        /*$templateParams = [];
        if($this->option('repository')){
            $repositoryDirPath = $this->getDirPath($className, 'Service');
            $this->createFile($className, $dirPath, 'Service', 'App\Services', $templateParams);

        }*/

        $nameSpace = 'App\\Modules\\'.$className;

        $this->createFile($className, $dirPath, 'Service', $nameSpace);
        $this->finishProgressBar();
    }
}
