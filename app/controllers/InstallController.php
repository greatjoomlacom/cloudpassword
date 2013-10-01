<?php

use \CustomHelpers\CustomLanguageHelper;
use \CustomHelpers\CustomConfigHelper;
use \CustomHelpers\CustomPathHelper;

class InstallController extends BaseController {

    public function __construct()
    {
        parent::__construct();

        // already installed
        if(APP_INSTALLED)
        {
            return Redirect::to('/')->with('error', Lang::get('install.status.error.application_installed'));
        }

        // check prerequisites for all routes
        if(!Session::get('installation.prerequisites ', 0))
        {
            return Redirect::action('InstallController@getIndex');
        }
    }

    /**
     * Index page
     */
    public function getIndex()
	{
        // prerequisites page  already set up
        if(Session::get('installation.prerequisites ', 0))
        {
            return Redirect::action('InstallController@getEstablishConnection');
        }

        $view = View::make('install.index');

        $this->layout->document_title = Lang::get('install.prerequisites.document_title');
        $this->layout->article_title = Lang::get('install.prerequisites.article_title');
        $this->layout->article_title_icon = Lang::get('install.article_title_icon');
        $this->layout->content = $view;

        /**
         * Prerequirements
         */
        $view->passed = true;

        // PHP >= 5.3.7
        $view->php_version = phpversion();
        $view->php_version_status = true;
        if(!version_compare($view->php_version, '5.3.7', '>='))
        {
            $view->php_version_status = false;
            $view->passed = false;
        }

        // mysql support
        $view->mysql_support = true;
        if (!function_exists('mysql_connect'))
        {
            $view->mysql_support = false;
            $view->passed = false;
        }

        // writable dirs
        if(function_exists('ini_get'))
        {
            $save_path = ini_get('session.save_path');
            $upload_tmp_dir = ini_get('upload_tmp_dir');
        }

        // tmp directory
        if(!isset($upload_tmp_dir) or !$upload_tmp_dir and function_exists('sys_get_temp_dir'))
        {
            $upload_tmp_dir = sys_get_temp_dir();
        }

        // writable dirs
        $writable_dirs = array(
            app_path('config'),
            app_path('lang'),
            app_path('storage'),
            app_path('storage/cache'),
            app_path('storage/logs'),
            app_path('storage/meta'),
            app_path('storage/sessions'),
            app_path('storage/views'),
            (isset($save_path) ? $save_path : ''),
            (isset($upload_tmp_dir) ? $upload_tmp_dir : ''),
        );

        foreach($writable_dirs as $key=>$dir)
        {
            if(File::isDirectory($dir))
            {
                if(File::isWritable($dir))
                {
                    $writable_dirs[CustomPathHelper::clean($dir)] = 1;
                }
                else
                {
                    $view->passed = false;
                    $writable_dirs[CustomPathHelper::clean($dir)] = 0;
                }
            }
            else
            {
                $view->passed = false;
                $writable_dirs[CustomPathHelper::clean($dir)] = 0;
            }
            unset($writable_dirs[$key]);
        }

        $view->writable_dirs = $writable_dirs;

        // memory limit, >= 32M
        $view->memory_limit = null;
        $view->memory_limit_status = true;
        if(function_exists('ini_get'))
        {
            $view->memory_limit = ini_get('memory_limit');

            // lower then expected
            if(intval($view->memory_limit) <= 32)
            {
                $view->memory_limit_status = false;
                $view->passed = false;
            }
        }

        // mcrypt
        $view->mcrypt = true;
        $view->mcrypt = true;
        if(!extension_loaded('mcrypt'))
        {
            $view->mcrypt_status = false;
            $view->passed = false;
        }

        // save session info that everything is fine
        if($view->passed)
        {
            Session::set('installation.prerequisites ', 1);
        }
	}

    /**
     * Db Configuration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getEstablishConnection()
    {
        $view = View::make('install.database_establish');
        $this->layout->document_title = Lang::get('install.db.establish.document_title');
        $this->layout->article_title = Lang::get('install.db.establish.article_title');
        $this->layout->article_title_icon = Lang::get('install.article_title_icon');
        $this->layout->content = $view;
    }

    /**
     * Confirm database connection
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEstablishConnection()
    {
        // db already installed
        if(Session::get('installation.database.established', 0))
        {
            return Redirect::action('InstallController@getInstallDb');
        }

        $validator = Validator::make(
            array(
                'database' => (array)Input::get('database'),
            ),
            array(
                'database' => array('required')
            ),
            array(
                'database.required' => Lang::get('install.status.error.database.empty_value'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = $validator->getData();

        if($this->checkForEmpty($data))
        {
            return Redirect::back()->with('error', Lang::get('install.status.error.database.empty_value'))->withInput();
        }

        // check for valid DB connection
        try {
            $dbh = new PDO(
                'mysql:host=' . $data['database']['connections']['mysql']['host'] . ';dbname=' . $data['database']['connections']['mysql']['database'],
                $data['database']['connections']['mysql']['username'],
                $data['database']['connections']['mysql']['password']
            );
            $dbh = null; // close connection
        } catch (PDOException $e)
        {
            return Redirect::back()->with(
                'error',
                Lang::get('install.status.error.database.connection', array('error' => $e->getMessage()))
            )->withInput();
        }

        CustomConfigHelper::setConfig('shared',
            array(
                'database.connections.mysql.host' => $data['database']['connections']['mysql']['host'],
                'database.connections.mysql.database' => $data['database']['connections']['mysql']['database'],
                'database.connections.mysql.username' => $data['database']['connections']['mysql']['username'],
                'database.connections.mysql.password' => $data['database']['connections']['mysql']['password'],
                'database.connections.mysql.charset' => $data['database']['connections']['mysql']['charset'],
                'database.connections.mysql.collation' => $data['database']['connections']['mysql']['collation'],
                'database.connections.mysql.prefix' => $data['database']['connections']['mysql']['prefix'],
            ),
            'config',
            true // ignore environment
        );

        Session::set('installation.database.established', 1);

        return Redirect::action('InstallController@getInstallDb');
    }

    /**
     * Install database view
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getInstallDb()
    {
        // db already installed
        if(Session::get('installation.database.installed', 0))
        {
            return Redirect::action('InstallController@getConfigAccount');
        }

        // db connection is not established yet, go back
        if(!Session::get('installation.database.established', 0))
        {
            return Redirect::action('InstallController@getEstablishConnection');
        }

        $view = View::make('install.database_install');
        $this->layout->document_title = Lang::get('install.db.install.document_title');
        $this->layout->article_title = Lang::get('install.db.install.article_title');
        $this->layout->article_title_icon = Lang::get('install.db.install.article_title_icon');
        $this->layout->content = $view;
    }

    /**
     * Create a db configuration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postInstallDb()
    {
        // db already installed
        if(Session::get('installation.database.installed', 0))
        {
            return Redirect::action('InstallController@getConfigAccount');
        }

        // db connection is not established yet, go back
        if(!Session::get('installation.database.established', 0))
        {
            return Redirect::action('InstallController@getEstablishConnection');
        }

        // migrate Sentry
        Artisan::call('migrate', array('--package' => 'cartalyst/sentry'));

        Artisan::call('migrate');

        // migrate application and seed with default data
        Artisan::call('db:seed');

        Session::set('installation.database.installed', 1);

        return Redirect::action('InstallController@getConfigAccount');
    }

    /**
     * Get config account layout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getConfigAccount()
    {
        // check for database session
        if(!Session::get('installation.database.installed', 0))
        {
            return Redirect::action('InstallController@getEstablishConnection');
        }

        // account already configured
        if(Session::get('installation.account', 0))
        {
            return Redirect::action('InstallController@getFinish');
        }

        $view = View::make('install.account');

        $this->layout->document_title = Lang::get('install.account.document_title');
        $this->layout->article_title = Lang::get('install.account.article_title');
        $this->layout->article_title_icon = Lang::get('install.article_title_icon');

        $view->languages = CustomLanguageHelper::getListOfLanguages();

        $this->layout->content = $view;
    }

    /**
     * Create a super user account
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateAnAccount()
    {
        // check for database session
        if(!Session::get('installation.database.installed', 0))
        {
            return Redirect::action('InstallController@getEstablishConnection');
        }

        // account already configured
        if(Session::get('installation.account', 0))
        {
            return Redirect::to('/');
        }

        $validator = Validator::make(
            array(
                'email' => Input::get('email'),
                'details.first_name' => Input::get('details.first_name'),
                'details.last_name' => Input::get('details.last_name'),
                'password' => Input::get('password'),
                'password2' => Input::get('password2'),
                'details.language' => Input::get('details.language'),
            ),
            array(
                'email' => array('required', 'email'),
                'password' => array('required'),
                'password2' => array('required'),
                'details.first_name' => array('required'),
                'details.last_name' => array('required'),
            ),
            array(
                'email.required' => Lang::get('account.edit.status.error.required.email'),
                'email.email' => Lang::get('account.edit.status.error.email.email'),
                'password.required' => Lang::get('account.edit.status.error.required.password'),
                'password2.required' => Lang::get('account.edit.status.error.required.password2'),
                'details.first_name.required' => Lang::get('account.edit.status.error.required.details.first_name'),
                'details.last_name.required' => Lang::get('account.edit.status.error.required.details.last_name'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = $validator->getData();
        $data = $this->createArrayFromDotKeys($data);

        try
        {
            // passwords do not match
            if($data['password'] !== $data['password2'])
            {
                throw new \Exception(Lang::get('install.status.error.account.passwords_do_not_match'));
            }

            // Find the user using the user id
            $user = Sentry::getUserProvider()->create(array(
                'email'    => $data['email'],
                'password' => $data['password'],
                'activated' => 1,
            ));

            if($details = $data['details'])
            {
                if(!isset($details['language']))
                {
                    $details['language'] = 'en';
                }

                if(!$user->details()->create($details))
                {
                    throw new Exception(Lang::get('shared.error.db.create'));
                }
            }

            $group = Sentry::getGroupProvider()->findById(1000); // super admin
            $user->addGroup($group);
        }
        catch(Exception $e)
        {
            return Redirect::back()->with('error', $e->getMessage())->withInput();
        }

        Session::set('installation.account', 1);

        return Redirect::action('InstallController@getFinish');
    }

    /**
     * Finish view
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getFinish()
    {
        // check for database session
        if(!Session::get('installation.database.installed', 0))
        {
            return Redirect::action('InstallController@getEstablishConnection');
        }

        // check for account
        if(!Session::get('installation.account', 0))
        {
            return Redirect::action('InstallController@getConfigAccount');
        }

        // put "installed" file in meta directory
        File::put(storage_path('meta') . DIRECTORY_SEPARATOR . 'installed', '');

        CustomConfigHelper::setConfig('shared',
            array(
                'app.debug' => false, // disable debug
                'app.locale' => 'en', // set default locale
                'app.url' => URL::to('/') . '/' . Config::get('app.locale'), // set URL to default
                'mode' => 'production',
            ),
            'config',
            true
        );

        Artisan::call('key:generate');
        Artisan::call('storage:clean');

        // clear session
        Session::clear();

        $view = View::make('install.finish');

        $this->layout->document_title = Lang::get('install.finish.document_title');
        $this->layout->article_title = Lang::get('install.finish.article_title');
        $this->layout->article_title_icon = Lang::get('install.finish.article_title_icon');

        $this->layout->content = $view;
    }

    /**
     * Create an array from dot notation, array('details.first_name')
     * @param array $array
     * @return array
     */
    private function createArrayFromDotKeys($array = array())
    {
        $new_array = array();

        foreach($array as $key=>$value)
        {
            if(strpos($key, '.') === false)
            {
                $new_array[$key] = $value;
            }
            else
            {
                $new_array_keys = explode('.', $key);
                $array_main_key = array_shift($new_array_keys);

                foreach($new_array_keys as $key)
                {
                    $new_array[$array_main_key][$key] = $value;
                }
            }
        }

        return $new_array;
    }


    /**
     * Restart installation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getRestartInstallation()
    {
        Session::clear();
        return Redirect::action('InstallController@getIndex');
    }

    /**
     * Check array for empty values
     * @param array $array
     * @return bool
     */
    private function checkForEmpty($array = array())
    {
        $is_empty = false;

        foreach($array as $key=>$a)
        {
            // password may be empty (localhost for instance)
            if($key === 'password') continue;

            if(is_array($a))
            {
                return $this->checkForEmpty($a);
            }
            else
            {
                if(!$a)
                {
                    $is_empty = true;
                    break;
                }
            }
        }

        return $is_empty;
    }

}