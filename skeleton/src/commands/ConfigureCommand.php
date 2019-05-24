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
        Artisan::call('vendor:publish', ['--provider' => 'BetterFly\Skeleton\SkeletonServiceProvider', '--force' => true, '--tag' => 'config']);
        $this->finishProgressBar();

        $this->createProgressbar(['message' => 'Running vendor:publish --tag=translatable']);
        Artisan::call('vendor:publish', ['--tag' => 'translatable']);
        $this->finishProgressBar();


        $this->createProgressbar(['message' => 'Running storage:link']);
        Artisan::call('storage:link');
        $this->finishProgressBar();

        $this->createProgressbar(['message' => 'Checking and creating required directories']);
        if (!File::isDirectory(resource_path('views/admin'))) {
            try {
                File::makeDirectory(resource_path('views/admin'));
                File::makeDirectory(resource_path('views/admin/common'));
                File::makeDirectory(public_path('userfiles'), 0777, true);
            } catch (\Exception $e) {
                $this->info($e->getMessage());
            }
        } else if (File::isDirectory(resource_path('views/admin')) && !File::isDirectory(resource_path('views/admin/common'))) {
            try {
                File::makeDirectory(resource_path('views/admin/common'));
            } catch (\Exception $e) {
                $this->info($e->getMessage());
            }
        }


        if (!File::isDirectory(app_path('Modules')))
            File::makeDirectory(app_path('Modules'));

        if (!File::isDirectory(app_path('Modules/Dashboard')))
            File::makeDirectory(app_path('Modules/Dashboard'));

        File::copy(substr(__DIR__, 0, -8) . 'views/generators/Dashboard/DashboardController.php', app_path('Modules/Dashboard/DashboardController.php'));
        File::copy(substr(__DIR__, 0, -8) . 'views/generators/Dashboard/dashboard.route.php', app_path('Modules/Dashboard/dashboard.route.php'));


        if (!File::isFile(resource_path('views/admin/common/menu.blade.php'))) {
            File::copy(substr(__DIR__, 0, -8) . 'views/generators/menu-example.blade.php', resource_path('views/admin/common/menu.blade.php'));
        }

        if (!File::isFile(resource_path('views/admin/common/dashboard.blade.php'))) {
            File::copy(substr(__DIR__, 0, -8) . 'views/generators/Dashboard/dashboard-example.blade.php', resource_path('views/admin/common/dashboard.blade.php'));
        }

        if (!File::isDirectory(resource_path('lang')))
            File::makeDirectory(resource_path('lang'));

        $locales = config('translatable.locales');

        $arrType = $this->checkArrayType($locales);

        if ($locales && $arrType == 'sequential') {
            foreach ($locales as $locale) {
                if (!File::isFile(resource_path('lang/' . $locale . '.json')))
                    File::put(resource_path('lang/' . $locale . '.json'), '{"" : ""}');
            }
        } else {
            $this->comment("\n \n Please configure config/translatable.php ('locales') and then run configure \n");
        }

        $this->finishProgressBar();

        $this->createProgressbar(['message' => 'Running betterfly:db_reset']);
        Artisan::call('betterfly:db_reset');
        $this->finishProgressBar();

    }

    public function checkArrayType($arr)
    {
        foreach ($arr as $key => $value) {
            if (is_array($value))
                return 'associative';
        }

        return 'sequential';
    }
}
