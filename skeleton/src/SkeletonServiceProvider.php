<?php

namespace BetterFly\Skeleton;

use BetterFly\Skeleton\Commands\MakeBuildModuleCommand;
use BetterFly\Skeleton\Commands\MakeControllerCommand;
use BetterFly\Skeleton\Commands\MakeMigrationCommand;
use BetterFly\Skeleton\Commands\MakeModuleCommand;
use BetterFly\Skeleton\Commands\MakeRepositoryCommand;
use BetterFly\Skeleton\Commands\MakeRequestCommand;
use BetterFly\Skeleton\Commands\MakeRouteCommand;
use BetterFly\Skeleton\Commands\DatabaseReset;
use BetterFly\Skeleton\Commands\MakeServiceCommand;
use BetterFly\Skeleton\Commands\MakeTransformerCommand;
use BetterFly\Skeleton\Commands\MakeModelCommand;
use BetterFly\Skeleton\Commands\ConfigureCommand;
use BetterFly\Skeleton\Commands\MakeViewCommand;

use Illuminate\Support\ServiceProvider;

class SkeletonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->publishes([
            __DIR__ . '/assets' => public_path('vendor/betterfly'),
        ]);

        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('skeleton.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__ . '/views', 'betterfly');
        include __DIR__ . '/routes.php';

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $router->pushMiddlewareToGroup('web', '\BetterFly\Skeleton\App\Http\Middleware\LocalizeWebRoutes');
        $router->pushMiddlewareToGroup('web', '\BetterFly\Skeleton\App\Http\Middleware\UserRolePermissionRoutes');
        $router->pushMiddlewareToGroup('web', '\BetterFly\Skeleton\App\Http\Middleware\Localization');



        $router->prependMiddlewareToGroup('api', 'Illuminate\Session\Middleware\StartSession');
        $router->prependMiddlewareToGroup('api', '\App\Http\Middleware\EncryptCookies');
        $router->pushMiddlewareToGroup('api', '\BetterFly\Skeleton\App\Http\Middleware\LocalizeApiRoutes');
        $router->pushMiddlewareToGroup('api', '\BetterFly\Skeleton\App\Http\Middleware\UserRolePermissionRoutes');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ConfigureCommand::class,
                MakeModuleCommand::class,
                MakeModelCommand::class,
                MakeBuildModuleCommand::class,
                MakeRepositoryCommand::class,
                MakeServiceCommand::class,
                MakeControllerCommand::class,
                MakeRequestCommand::class,
                MakeTransformerCommand::class,
                MakeMigrationCommand::class,
                MakeRouteCommand::class,
                MakeViewCommand::class,
                DatabaseReset::class
            ]);
        }

        Schema::defaultStringLength(191);
//        $this->registerHelpers();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(
            'BetterFly\Skeleton\Providers\RouteServiceProvider'
        );

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');

        $this->mergeConfigFrom(
            __DIR__ . '/config/config.php', 'skeleton'
        );
    }
//    /**
//     * Register helpers file
//     */
//    public function registerHelpers()
//    {
//        // Load the helpers in app/Http/helpers.php
//        if (file_exists($file = app_path('app/Helpers/helpers.php')))
//        {
//            require $file;
//        }
//    }
}
