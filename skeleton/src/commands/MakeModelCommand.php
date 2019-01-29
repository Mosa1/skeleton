<?php

namespace BetterFly\Skeleton\Commands;

class MakeModelCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:model {moduleName : Module classname} {--dirPathType=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making Betterfly Model';

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

        $dirPath = $this->getDirPath($className, 'Model');
        $this->validateDirPath($dirPath);

        $config = $this->getConfigFile($className, true);
        if(!property_exists($config,'fillable'))
            die($this->error("\n \n Can't create model missing fillable in ".$className.'.config.json'));

        $params['config'] = $config;

        $this->createFile($className, $dirPath, 'Model', "App\Modules\\".$className, $params);
        if ($config->translatable) {
            $params['config']->translatableModel = true;
            $this->createFile($className . 'Translation', $dirPath, 'Model', "App\Modules\\".$className, $params);
        }

        $this->finishProgressBar();
    }
}
