<?php

namespace BetterFly\Skeleton\Commands;

class MakeControllerCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:controller {moduleName : Module classname} {--dirPathType=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making Betterfly Controller';

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
        $config = $this->getConfigFile  ($className, true, true);
        foreach ($config->relations as $key => $relation){
            if(!property_exists($relation,'pluginName') || !$relation->pluginName)
                unset($config->relations[$key]);
        }

        $params = [];
        $this->createProgressbar();

        $dirPath = $this->getDirPath($className, 'Controller');
        $nameSpace = 'App\\Modules\\'.$className;

        $params['dataLoaderMethod'] = property_exists($config,'dataLoaderMethod') ? $config->dataLoaderMethod : false;
        $params['sortable'] = $config->sortable;
        $params['routeName'] = $this->getRouteName();
        $params['parentModule'] = $config->parentModule;
        $params['relations'] = $config->relations;

        $this->validateDirPath($dirPath);

        $this->createFile($className, $dirPath, 'Controller', $nameSpace,$params);

        $this->finishProgressBar();
    }
}
