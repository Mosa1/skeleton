<?php

namespace BetterFly\Skeleton\Commands;

class MakeRepositoryCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:repository {moduleName : Module classname} {--dirPathType=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making Betterfly Repository';

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

        $dirPath = $this->getDirPath($className, 'Repository');
        $this->validateDirPath($dirPath);
        $nameSpace = 'App\\Modules\\'.$className;

        $this->createFile($className, $dirPath, 'Repository', $nameSpace);

        $this->finishProgressbar();
    }
}
