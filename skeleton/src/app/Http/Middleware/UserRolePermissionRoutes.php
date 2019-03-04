<?php

namespace BetterFly\Skeleton\App\Http\Middleware;

use Illuminate\Foundation\Application;
use BetterFly\Skeleton\App\Http\Responses\APIResponseTrait;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class UserRolePermissionRoutes
{
    use APIResponseTrait;
    /**
     * Localization constructor.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currentAction = Route::currentRouteAction();

        //echo $currentAction;die();
//        if(Auth::check() && !Auth::user()->can($currentAction))
//            return $this->responseWithError('Permission is missing',403);
        return $next($request);
    }
}
