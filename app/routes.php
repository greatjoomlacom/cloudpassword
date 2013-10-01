<?php
use \CustomHelpers\CustomLanguageHelper;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

if(App::runningInConsole())
{
    return true;
}

// set locale to session
$locales = CustomLanguageHelper::getListOfLanguages();

$locale = Config::get('app.locale', 'en');

$segment_locale = Request::segment(1);

if(in_array($segment_locale, array_keys($locales)))
{
    $locale = $segment_locale;
}
else
{
    $locale = Session::get('locale', $locale);
}

if(!File::isDirectory(app_path('lang') . DIRECTORY_SEPARATOR . $locale))
{
    $locale = 'en';
}

if(!in_array($segment_locale, array_keys($locales)))
{
    // do redirect first
    $url = Request::segments();

    // no locale - homepage probably
    if (isset($url[0]) and $url[0] !== $locale)
    {
        array_unshift($url, $locale);

        $url = URL::to(implode('/', $url));

        if (Session::get('flash.old'))
        {
            // reflash session
            Session::reflash();
        }

        header("Location: " . $url);
        exit;
    }
    else
    {
        // url is without locale at the end
        header("Location: " . URL::to('/' . $locale));
        exit;
    }
}

Session::set('locale', $locale);
View::share('locale', $locale);

// original locale stored in config app.php file
View::share('app_locale', Config::Get('app.locale'));
if(!defined('CONFIG_APP_LOCALE'))
{
    define('CONFIG_APP_LOCALE', Config::Get('app.locale'));
}

App::setLocale($locale);

Route::group(array('prefix' => $locale), function()
{
    // installation goes first if app is not installed
    if(!APP_INSTALLED)
    {
        Route::controller('install', 'InstallController');
    }
    else
    {
        // load controllers from DB
        if (Schema::hasTable('controllers'))
        {
            $controllers = ControllersModel::orderBy('ordering')->get();

            if($controllers)
            {
                foreach($controllers as $c)
                {
                    Route::controller($c->uri, $c->action);
                }
            }
        }
    }
});

/*** Default controller ***/
Route::controller('/', 'IndexController');