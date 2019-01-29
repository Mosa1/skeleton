<?php

namespace BetterFly\Skeleton\Commands;

use BetterFly\Skeleton\Commands\BaseCommand;

class MakeRouteCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:route {moduleName : Module classname} {--dirPathType=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making Betterfly Routes';

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
        $this->createProgressbar();

        $dirPath = $this->getDirPath($moduleName, 'Route');
        $this->validateDirPath($dirPath);

        $params = $this->getFields($moduleName, []);

        $this->createFile($moduleName, $dirPath, 'Route', '', $params);
        $this->finishProgressBar();
    }

    private function getFields($moduleName, $params){

        $params['modulePlural'] = strtolower(str_plural($moduleName));

        return $params;
    }
}
