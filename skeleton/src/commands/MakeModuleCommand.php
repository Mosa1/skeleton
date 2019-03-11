<?php

namespace BetterFly\Skeleton\Commands;

class MakeModuleCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:create_module {moduleName : Module classname} {--dirPathType=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making Betterfly Module';

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
        $modelName = trim($this->argument('moduleName'));
        $this->createProgressbar();

        $dirPath = $this->getDirPath($modelName, 'Config');
        $this->validateDirPath($dirPath);

        $this->createFile($modelName, $dirPath, 'Config', null);
        $this->info("\n Config File Created, please configure it and then run: \"php artisan betterfly:build_module " . $modelName . "\" ");

        $this->finishProgressBar();
    }
}
