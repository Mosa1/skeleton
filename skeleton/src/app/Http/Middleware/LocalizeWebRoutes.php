<?php

namespace BetterFly\Skeleton\App\Http\Middleware;

use Closure;

use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class LocalizeWebRoutes
{

    protected $app;

    protected $redirector;

    protected $request;


    public function __construct(Application $app, Redirector $redirector, Request $request)
    {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
        $this->locales = $this->app->config->get('translatable.locales');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$this->locales) return $next($request);

        // Make sure current locale exists.
        $locale = $request->segment(1);

        if (!in_array($locale, $this->locales)) {

            $locale = \Session('locale') !== null ? \Session('locale') : $this->app->config->get('translatable.fallback_locale');
            $url = $locale . $request->getRequestUri();

            if(strpos($url,'login') || strpos($url,'admin') || strpos($url,'logout')) return $next($request);


            return $this->redirector->to($url);
        }

        \Session::put('locale', $locale);
        $this->app->setLocale($locale);

        return $next($request);
    }
}
