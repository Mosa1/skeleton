<?php

namespace BetterFly\Skeleton\App\Http\Middleware;

use Illuminate\Foundation\Application;
use BetterFly\Skeleton\App\Http\Responses\APIResponseTrait;
use Closure;

class LocalizeApiRoutes
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

    // read the language from the request header
    $sessionLocale = \Session('locale');
    $acceptLanguage = $request->getPreferredLanguage();
    $useRequestedLang = in_array($request->getPreferredLanguage(), $this->app->config->get('translatable.locales'));

    if($useRequestedLang){
      $locale = $request->getPreferredLanguage();
    }else if($sessionLocale !== null){
      $locale = \Session('locale');
    }else if($acceptLanguage !== null){
      $locale = $this->decodeAcceptLanguage($acceptLanguage);
    } else{
      $locale = $this->app->config->get('translatable.fallback_locale');
    }

    // check the languages defined is supported
    if (!in_array($locale, $this->app->config->get('translatable.locales'))) {
      // respond with error

      return $this->responseWithError('Language not supported.',403);
    }

    //save local language in to session
    \Session::put('locale',$locale);

    // set the local language
    $this->app->setLocale($locale);

    // get the response after the request is done
    $response = $next($request);

    // set Content Languages header in the response
    $response->headers->set('Content-Language', $locale);

    return $response;
  }

  public function decodeAcceptLanguage($locale){
    $locale = current(explode('_', $locale));

    return $locale;
  }
}
