<?php

namespace BetterFly\Skeleton\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ConfigureCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:configure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure app';

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
        $this->createProgressbar(['message' => 'Running vendor:publish --provider=BetterFly\Skeleton\SkeletonServiceProvider --force']);
        Artisan::call('vendor:publish',['--provider' => 'BetterFly\Skeleton\SkeletonServiceProvider' ,'--force' => true]);
        $this->finishProgressBar();

        $this->createProgressbar(['message' => 'Checking and creating required directories']);
        if(!File::isDirectory(resource_path('views/admin'))){
            try{
                File::makeDirectory(resource_path('views/admin'));
                File::makeDirectory(resource_path('views/admin/common'));
            }catch(\Exception $e){
                $this->info($e->getMessage());
            }
        }else if(File::isDirectory(resource_path('views/admin')) && !File::isDirectory(resource_path('views/admin/common'))){
            try{
                File::makeDirectory(resource_path('views/admin/common'));
            }catch(\Exception $e){
                $this->info($e->getMessage());
            }
        }

        if(!File::isFile(resource_path('views/admin/common/menu.blade.php'))){
            File::copy(substr(__DIR__, 0, -8) . 'views/generators/menu-example.blade.php',resource_path('views/admin/common/menu.blade.php'));
        }

        Artisan::call('betterfly:db_reset');

        $this->finishProgressBar();

    }
}
