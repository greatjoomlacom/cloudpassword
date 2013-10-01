<?php

namespace Admin;

use View,
    Lang,
    Redirect,
    Validator,
    File,
    Input,
    Session,
    Symfony\Component\Finder\Finder,
    CustomHelpers\CustomLanguageHelper,
    CustomHelpers\CustomPathHelper,
    CustomHelpers\CustomConfigHelper,
    CustomHelpers\CustomUserHelper;

class AdminLanguagesController extends \BaseController {

    private $canEdit = 0;

    public function __construct()
    {
        $this->beforeFilter('hasAnyAccess:languages|languages.view|languages.edit');
        $this->canEdit = CustomUserHelper::hasAnyAccess(array('languages', 'languages.edit'));
        View::share('canEdit', $this->canEdit);
    }

    /**
     * Default index page
     */
    public function getIndex()
	{
        $view = View::make('admin.languages.index');

        $this->layout->document_title = Lang::get('admin/languages.document_title');
        $this->layout->article_title = Lang::get('admin/languages.article_title');
        $this->layout->article_title_icon = Lang::get('admin/languages.article_title_icon');

        $view->languages = CustomLanguageHelper::getListOfLanguages();

        $this->layout->content = $view;
	}

    /**
     * Edit language
     * @param string $code
     */
    public function getEditLanguage($code = 'en')
    {
        $view = View::make('admin.languages.edit');

        $this->layout->document_title = Lang::get('admin/languages.document_title');
        $this->layout->article_title = Lang::get('admin/languages.update.article_title', array('language' => Lang::get('language.name', array(), $code)));
        $this->layout->article_title_icon = Lang::get('admin/languages.article_title_icon');

        $language_directory_path = CustomPathHelper::clean(app_path('lang') . DIRECTORY_SEPARATOR . $code);

        if(!File::isDirectory($language_directory_path))
        {
            return Redirect::back()->with('error', Lang::get('admin/languages.update.status.error.missing_lang_directory', array('path' => $language_directory_path)));
        }

        $language_files = iterator_to_array(Finder::create()->files()->in($language_directory_path)->notName('-backup-'), false);

        $items = array();
        foreach($language_files as $language_file)
        {
            $path = CustomPathHelper::clean($language_file->getPathName());

            $dot_names = array();

            foreach(array_dot(File::getRequire($path)) as $key_name=>$dot_name)
            {
                //$dot_names['[' . str_replace(CustomPathHelper::clean(app_path('lang') . DIRECTORY_SEPARATOR . $code . DIRECTORY_SEPARATOR), '', $path) . ']' . '[' . str_replace('.', '][', $key_name) . ']'] = $dot_name;
                $dot_names[$key_name] = $dot_name;
            }

            $items[str_replace(CustomPathHelper::clean(app_path('lang')) . DIRECTORY_SEPARATOR . $code . DIRECTORY_SEPARATOR, '', $path)] = $dot_names;
        }

        $view->code = $code;
        $view->items = $items;
        $this->layout->content = $view;
    }

    /**
     * Update language file, backup original one
     * @param string $code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdateLanguageFile($code = 'en')
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $data = (array)Input::except(array('_token'));

        // only 2 items array allowed, 1 = code, 2 = language to update
        if(!$data or count($data) !== 2)
        {
            return Redirect::back()->with('error', Lang::get('admin/languages.update.status.error.no_data'));
        }

        if(!isset($data['code']) or !$data['code'])
        {
            return Redirect::back()->with('error', Lang::get('admin/languages.update.status.error.no_data'));
        }

        $code = $data['code'];
        unset($data['code']);

        $file = array_keys($data);
        if (!isset($file[0]) or !$file[0])
        {
            return Redirect::back()->with('error', Lang::get('admin/languages.update.status.error.no_data'));
        }

        CustomConfigHelper::setConfig($file[0], $data[$file[0]], 'lang' . DIRECTORY_SEPARATOR . $code);

        $file = $file[0];
        $filepath = CustomPathHelper::clean(app_path('lang') . DIRECTORY_SEPARATOR . $code . DIRECTORY_SEPARATOR  . $file . '.php');

        return Redirect::back()
            ->with('success', Lang::get('admin/languages.update.status.success', array('language' => basename($filepath))));
    }

    /**
     * Delete language folder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteLanguage()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'code' => strip_tags(Input::get('code')),
            ),
            array(
                'code' => array('required'),
            ),
            array(
                'code.required' => Lang::get('admin/languages.form.error.required.name'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        // prevent delete default language directory
        if ($data['code'] === 'en')
        {
            return Redirect::back()->with('error', Lang::get('admin/languages.delete.status.error.delete_default'));
        }

        $language_title = Lang::get('language.name', array(), $data['code']);

        $path_to_lang_directory = CustomPathHelper::clean(app_path('lang') . DIRECTORY_SEPARATOR . $data['code']);

        if(!File::isDirectory($path_to_lang_directory))
        {
            return Redirect::back()->with('error', Lang::get('shared.error.filesystem.is_dir', array('path' => $path_to_lang_directory)));
        }

        if($error = File::deleteDirectory($path_to_lang_directory))
        {
            return Redirect::back()->with('error', Lang::get('shared.error.filesystem.delete_dir', array('path' => $path_to_lang_directory)));
        }

        // set default English language to config file ir required
        if (CONFIG_APP_LOCALE === $data['code'])
        {
            $lang = 'en';
            Session::set('locale', $lang);

            // write config file
            CustomConfigHelper::setConfig('app', array('locale' => $lang));
        }

        return Redirect::back()
            ->with('success', Lang::get('admin/languages.delete.status.success', array('language' => $language_title)));

    }

    /**
     * Switch language, write app.php config file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSwitchLanguage()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'code' => strip_tags(Input::get('code')),
            ),
            array(
                'code' => array('required'),
            ),
            array(
                'code.required' => Lang::get('admin/languages.form.error.required.name'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        $language_title = Lang::get('language.name', array(), $data['code']);


        // prevent delete default language directory
        if ($data['code'] === CONFIG_APP_LOCALE)
        {
            return Redirect::back()->with('error', Lang::get('admin/languages.switch.status.error.switch_to_default', array('language' => $language_title)));
        }

        // write config file
        CustomConfigHelper::setConfig('app', array('locale' => $data['code']));

        Session::set('locale', $data['code']);

        return Redirect::back()
            ->with('success', Lang::get('admin/languages.switch.status.success', array('language' => $language_title)));
    }

}
