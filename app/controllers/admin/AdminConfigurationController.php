<?php

namespace Admin;

use CustomHelpers\CustomConfigHelper;
use CustomHelpers\CustomUserHelper;
use Illuminate\Encryption\DecryptException;
use View,
    Lang,
    Validator,
    Input,
    Redirect,
    File,
    Crypt,
    Config,
    PasswordsModel,
    CustomHelpers\CustomPathHelper,
    CustomHelpers\CustomSecurityHelper,
    CustomHelpers\CustomLanguageHelper;
use Session;

class AdminConfigurationController extends \BaseController {

    private $canEdit = 0;

    public function __construct()
    {
        $this->beforeFilter('hasAnyAccess:configuration|configuration.view|configuration.edit');

        $this->canEdit = CustomUserHelper::hasAnyAccess(array('configuration', 'configuration.edit'));
        View::share('canEdit', $this->canEdit);
    }

	public function getIndex()
	{
        $view = View::make('admin.configuration.index');

        $this->layout->document_title = Lang::get('admin/configuration.document_title');
        $this->layout->article_title = Lang::get('admin/configuration.article_title');
        $this->layout->article_title_icon = Lang::get('admin/configuration.article_title_icon');

        $view->configuration_options = array(
            'default',
            'server',
            'security',
        );

        $view->languages = CustomLanguageHelper::getListOfLanguages();

        $this->layout->content = $view;
	}

    /**
     * Update server configuration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdateConfiguration()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'app' => (array)Input::get('app'),
                'database' => (array)Input::get('database'),
                'shared' => (array)Input::get('shared'),
            ),
            array()
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        if(isset($data['shared']) and $data['shared'])
        {
            foreach($data['shared'] as $shared_key=>$shared_value)
            {
                $data[$shared_key] = $shared_value;
            }

            unset($data['shared']);
        }

        $data = array_dot($data);

        CustomConfigHelper::setConfig('shared', $data);

        return Redirect::back()->with('success', Lang::get('admin/configuration.status.success'));
    }

    /**
     * Re-crypt all password in database
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postReCryptAllPasswords()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $passwords = PasswordsModel::all();

        foreach($passwords as $item)
        {
            try
            {
                $item->password = Crypt::encrypt(Crypt::decrypt($item->password));
            }
            catch(DecryptException $e)
            {
                return Redirect::back()->with('error', $e->getMessage());
            }

            if(!$item->update())
            {
                return Redirect::back()->with('success', Lang::get('admin/configuration.security.recrypt.status.error', array('id' => $item->id)));
            }

        }

        return Redirect::back()->with('success', Lang::get('admin/configuration.security.recrypt.status.success'));
    }

    /**
     * Get master password form
     */
    public function getMasterPasswordSet()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        if(!(boolean)Config::get('shared.security.master_password.enabled'))
        {
            return Redirect::back();
        }

        $view = View::make('admin.configuration.set_master_password');

        $this->layout->document_title = Lang::get('admin/configuration.security.master_password.set.document_title');
        $this->layout->article_title = Lang::get('admin/configuration.security.master_password.set.article_title');
        $this->layout->article_title_icon = Lang::get('admin/configuration.article_title_icon');

        $view->passwd_file_path = CustomPathHelper::clean(dirname(app('path')) . DIRECTORY_SEPARATOR . CustomSecurityHelper::random_key(5, true, true) . DIRECTORY_SEPARATOR . '.' . CustomSecurityHelper::random_key(10, true, true));

        if($htpasswd_file = Config::get('shared.security.master_password.passwd_path'))
        {
            if(File::exists($htpasswd_file))
            {
                $view->passwd_file_path = $htpasswd_file;
            }
        }

        $this->layout->content = $view;
    }

    /**
     * Proceed new master password
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postMasterPasswordSet()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'passwd_file_path' => strip_tags(Input::get('passwd_file_path', Config::get('shared.security.master_password.passwd_path'))),
                'passwd_area_title' => strip_tags(Input::get('passwd_area_title')),
                'passwd_user' => strip_tags(Input::get('passwd_user')),
                'passwd_password' => strip_tags(Input::get('passwd_password')),
            ),
            array(
                'passwd_file_path' => array('required'),
                'passwd_area_title' => array('required'),
                'passwd_user' => array('required'),
                'passwd_password' => array('required'),
            ),
            array(
                'passwd_file_path.required' => Lang::get('admin/configuration.security.master_password.set.form.error.required.passwd_file_path'),
                'passwd_area_title.required' => Lang::get('admin/configuration.security.master_password.set.form.error.required.passwd_area_title'),
                'passwd_user.required' => Lang::get('admin/configuration.security.master_password.set.form.error.required.passwd_user'),
                'passwd_password.required' => Lang::get('admin/configuration.security.master_password.set.form.error.required.passwd_password'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        // delete htpasswd folder first if exists
        if (File::isDirectory(dirname($data['passwd_file_path'])))
        {
            if($error = File::deleteDirectory(dirname($data['passwd_file_path'])))
            {
                return Redirect::back()->with('error', Lang::get('shared.error.filesystem.delete_dir', array('path' => dirname($data['passwd_file_path']))));
            }
        }

        // crete a folder again
        if(!File::makeDirectory(dirname($data['passwd_file_path'])))
        {
            return Redirect::back()->with('error', Lang::get('shared.error.filesystem.delete_dir', array('path' => dirname($data['passwd_file_path']))));
        }

        $password = CustomSecurityHelper::cryptApr1Md5($data['passwd_password']);

        // create a .passwd file
        $passwd_file_content = $data['passwd_user'] . ':' . $password;

        if(!File::put($data['passwd_file_path'], $passwd_file_content))
        {
            return Redirect::back()->with('error', Lang::get('shared.error.filesystem.delete_dir', array('path' => $data['passwd_file_path'])));
        }

        // store path to config file
        if(!Config::get('shared.security.master_password.passwd_path'))
        {
            $config_file = CustomPathHelper::clean(app_path('config') . DIRECTORY_SEPARATOR . 'shared.php');
            $config_file_content = File::get($config_file);

            $config_file_content = str_replace(
                array(
                    "'passwd_path' => '',",
                ),
                array(
                    "'passwd_path' => '" . $data['passwd_file_path'] . "',",
                ),
                $config_file_content);

            if(File::put($config_file, $config_file_content) === false)
            {
                // error
                return Redirect::back();
            }
        }

        // check for .htaccess file
        $htaccess_file_path = dirname(app_path()) . DIRECTORY_SEPARATOR . '.htaccess';

        $htaccess_file_content = '';

        if (File::exists($htaccess_file_path))
        {
            if($htaccess_file_content = File::get($htaccess_file_path))
            {
                // preserver file content if necessary
                $htaccess_file_content = trim(preg_replace('/(### MASTER CREDENTIALS ###.*?### END ###)/s', '', $htaccess_file_content));
            }

            // delete .htaccess file
            if(!File::delete($htaccess_file_path))
            {
                return Redirect::back()->with('error', Lang::get('shared.error.filesystem.delete_dir', array('path' => $htaccess_file_path)));
            }
        }

        // write .htaccess file content on end
        $htaccess_file_content = trim($htaccess_file_content . "\n\n### MASTER CREDENTIALS ###\nAuthType Basic\nAuthName \"" . $data['passwd_area_title'] . "\"\nAuthUserFile " . $data['passwd_file_path'] . "\nRequire valid-user\n### END ###");

        if(!File::put($htaccess_file_path, $htaccess_file_content))
        {
            return Redirect::back()->with('error', Lang::get('shared.error.filesystem.delete_dir', array('path' => $htaccess_file_path)));
        }

        return Redirect::back()->with('success', Lang::get('admin/configuration.security.master_password.set.status.success'));
    }

    /**
     * Clear master password protection
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postMasterPasswordClear()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $passwd_file_path = Config::get('shared.security.master_password.passwd_path');

        // no file specified in configuration
        if(!$passwd_file_path)
        {
            return Redirect::back();
        }

        // file does not exists
        if(!File::exists($passwd_file_path))
        {
            return Redirect::back();
        }

        // modify .htaccess file first!
        $htaccess_file_path = dirname(app_path()) . DIRECTORY_SEPARATOR . '.htaccess';

        // check if .htaccess file even exists
        if(File::exists($htaccess_file_path))
        {
            $htaccess_file_content = File::get($htaccess_file_path);

            if($htaccess_file_content)
            {
                $htaccess_file_content = trim(preg_replace('/(### MASTER CREDENTIALS ###.*?### END ###)/s', '', $htaccess_file_content));
            }

            // delete file first
            if(!File::delete($htaccess_file_path))
            {
                return Redirect::back();
            }

            if($htaccess_file_content)
            {
                // write updated file content
                if(!File::put($htaccess_file_path, $htaccess_file_content))
                {
                    return Redirect::back();
                }
            }
        }

        // delete entire passwd directory
        if (File::isDirectory(dirname($passwd_file_path)))
        {
            if($error = File::deleteDirectory(dirname($passwd_file_path)))
            {
                return Redirect::back()->with('error', Lang::get('shared.error.filesystem.delete_dir', array('path' => dirname($passwd_file_path))));
            }
        }

        // update config file
        $config_file = CustomPathHelper::clean(app_path('config') . DIRECTORY_SEPARATOR . 'shared.php');
        $config_file_content = File::get($config_file);

        $config_file_content = str_replace(
            array(
                "'passwd_path' => '$passwd_file_path',",
            ),
            array(
                "'passwd_path' => '',",
            ),
            $config_file_content);

        if(!File::put($config_file, $config_file_content))
        {
            // error
            return Redirect::back();
        }

        return Redirect::back()->with('success', Lang::get('admin/configuration.security.master_password.clear.status.success'));
    }

}