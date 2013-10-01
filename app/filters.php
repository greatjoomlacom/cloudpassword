<?php
/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

use CustomHelpers\CustomUserHelper;

// check if installed
if(!defined('APP_INSTALLED'))
{
    if(File::exists(storage_path('meta') . DIRECTORY_SEPARATOR . 'installed'))
    {
        define('APP_INSTALLED', 1);
    }
    else
    {
        define('APP_INSTALLED', 0);
    }
}

App::before(function($request)
{
    if(APP_INSTALLED)
    {
        if($user = CustomUserHelper::getUser())
        {
            // search for currently logged-in user
            if(!defined('APP_USER_ID') and $user->id)
            {
                define('APP_USER_ID', (int)$user->id);
            }
            else
            {
                define('APP_USER_ID', 0);
            }

            // set logged-in user info to global
            if(!defined('APP_USER') and $user)
            {
                define('APP_USER', $user->toJson());
            }
            else
            {
                define('APP_USER', null);
            }
        }
    }

    // paginate
    if(!defined('APP_SHARED_PAGINATE'))
    {
        define('APP_SHARED_PAGINATE', (int)Config::get('shared.paginate', 30));
    }

    // enable profilers for super admin only
    Config::set('profiler::profiler', (isset($user) ? $user->isSuperUser() : false));
    Config::set('app.debug', (isset($user) ? $user->isSuperUser() : false));

});

App::after(function($request, $response)
{
    // no cache
    $response->headers->set('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate');
    $response->headers->set('', 'no-cache');
    $response->headers->set('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');

    $content = $response->getContent();

    // place all scripts at the page bottom
    preg_match_all('#(<script\b[^>]*>.*?<\/script>)#is', $content, $scripts);

    if (isset($scripts[1]))
    {
        foreach($scripts[1] as $script)
        {
            $content = str_replace($script, '', $content);
        }
        $content = str_replace('</body>', implode("\n", $scripts[1]) . "\n</body>", $content);

        $response->setContent($content);
    }

    // search for styles inside document
    preg_match_all('#(<link\smedia\b[^>]*>)#i', $content, $styles);

    if (isset($styles[1]))
    {
        foreach($styles[1] as $style)
        {
            $content = str_replace($style, '', $content);
        }
        $content = str_replace('</head>', implode("\n", $styles[1]) . "\n</head>", $content);

        $response->setContent($content);
    }

    // remove blank lines
    $content = preg_replace('#\r\n#', '', $content);

    $response->setContent($content);
});

/**
 * Missing page - 404
 */
App::missing(function($exception)
{
    return Redirect::to('/')->with('error', Lang::get('shared.error.status.http.404', array('url' => URL::full())));
});

/**
 * Installation filter
 */
Route::filter('installed', function($route, $request)
{
    // application is not installed yet
    if(!APP_INSTALLED)
    {
        if (strpos(URL::full(), '/install/') === false)
        {
            return Redirect::action('InstallController@getIndex');
        }
    }
    else
    {
        if (strpos(URL::full(), '/install/') !== false)
        {
            // already installed
            return Redirect::to('/');
        }

    }
});

/**
 * Check for any access
 */
Route::filter('hasAnyAccess', function($route, $request, $permissions = '')
{
    if(!$permissions) return false;

    $permissions = explode('|', $permissions);

    if(!$permissions) return false;

    if(!CustomUserHelper::hasAnyAccess($permissions))
    {
        return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
    }
});
/**
 * Check for specific access
 */
Route::filter('hasAccess', function($route, $request, $permissions = '')
{
    if(!$permissions) return false;

    $permissions = explode('|', $permissions);

    if(!$permissions) return false;

    if(!CustomUserHelper::hasAccess($permissions))
    {
        return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
    }
});

/**
 * Filter page number
 */
Route::filter('page', function($route, $request, $context = '')
{
    if(!$context) return false;

    Session::remove('page');

    if($page = (int)Input::get('page', 0))
    {
        $data = array();
        $data[$context] = $page;

        Session::set("page", $data);
    }
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
    if (!Sentry::check())
    {
        return Redirect::guest('account/login')->with('error', Lang::get('shared.error.login_first'));
    }
	//if (Auth::guest()) return Redirect::guest('login');
});

/*
Route::filter('auth.basic', function()
{
	return Auth::basic();
});
*/

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
        return Redirect::to('/')->with('error', Lang::get('shared.error.invalid_token'));
		//throw new Illuminate\Session\TokenMismatchException;
	}
});