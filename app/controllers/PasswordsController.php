<?php

use CustomHelpers\CustomSecurityHelper;
use Illuminate\Encryption\DecryptException;
use CustomHelpers\CustomUserHelper;

class PasswordsController extends BaseController {

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->beforeFilter('hasAnyAccess:passwords|passwords.view|passwords.edit');
        $this->canEdit = CustomUserHelper::hasAnyAccess(array('passwords', 'passwords.edit'));

        View::share('canEdit', $this->canEdit);
    }

    /**
     * Get passwords from category
     * @param string $category_slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getCategory($category_slug = '')
	{
        if (!$category_slug)
        {
            return Redirect::to('/')->with('error', Lang::get('passwords.error.no_category_slug'));
        }

        $CategoriesModel = new CategoriesModel();

        $category = $CategoriesModel->whereUserId()->where('slug', 'LIKE', $category_slug)->first();

        $PasswordsModel = new PasswordsModel();
        $passwords = $PasswordsModel->whereUserId()->where('category_id', '=', $category->id)->get();

        $view = View::make('passwords.index');

        $view->passwords = $passwords;
        $view->category = $category;

        $this->layout->document_title = Lang::get('passwords.document_title');
        $this->layout->article_title = Lang::get('passwords.article_title', array('category' => $category->name));

        $this->layout->article_title_icon = Lang::get('passwords.article_title_icon');

        $this->layout->content = $view;
	}

    /**
     * Return password in ajax response
     */
    public function postCopyPassword()
    {
        $validator = Validator::make(
            array(
                'id' => Input::get('id'),
            ),
            array(
                'id' => array('required', 'integer'),
            ),
            array(
                'id.required' => Lang::get('passwords.error.missing_id'),
            )
        );

        $error = '';
        $item_password = '';

        if ($validator->fails())
        {
            $messages = $validator->messages();
            $error = $messages->first('id');
        }
        else
        {
            $data = $validator->getData();

            try
            {
                // get row from db
                $item = PasswordsModel::findWhereUserId((int)$data['id'], array('password'));

                if(!$item)
                {
                    throw new ErrorException(Lang::get('shared.error.db.no_item'));
                }

            }
            catch(ErrorException $e)
            {
                $error = $e->getMessage();
            }

            if (!$error)
            {
                try
                {
                    if (!isset($item->password) or trim($item->password) === '')
                    {
                        throw new ErrorException(Lang::get('shared.error.db.no_item'));
                    }

                    $item_password = Crypt::decrypt($item->password);
                }
                catch(DecryptException $e)
                {
                    $error = $e->getMessage();
                }
            }

        }

        if ($error)
        {
            return Response::json(array('type' => 'error', 'message' => $error));
        }
        else
        {
            $data = array('type' => 'success', 'message' => array('prompt_header' => Lang::get('passwords.copy_password.prompt_header'), 'prompt_message' => $item_password));
        }

        return Response::json($data);
    }

    /**
     * New password form
     * @param string $category_slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getNewPassword($category_slug = '')
    {
        if (!$category_slug) return Redirect::back();

        $view = View::make('passwords.new_password');

        $CategoriesModel = new CategoriesModel();

        $CategoriesModel = $CategoriesModel->whereUserId()->where('slug', 'LIKE', $category_slug)->first();

        $view->category = $CategoriesModel;

        $this->layout->document_title = Lang::get('passwords.new.document_title');
        $this->layout->article_title = Lang::get('passwords.new.article_title', array('category_name' => $view->category->name));
        $this->layout->article_title_icon = Lang::get('passwords.new.article_title_icon');

        $categories = array();

        foreach($CategoriesModel->whereUserId()->get() as $category)
        {
            $categories[$category->id] = $category->name;
        }

        $view->categories = $categories;

        $view->random = CustomSecurityHelper::random_key(10);

        $this->layout->content = $view;
    }

    /**
     * Save new password to database
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNewPasswordSave()
    {
        $validator = Validator::make(
            array(
                'title' => Input::get('title'),
                'username' => Input::get('username'),
                'password' => Input::get('password'),
                'url' => Input::get('url'),
                'note' => Input::get('note'),
                'category_id' => Input::get('category_id'),
            ),
            array(
                'username' => array('required'),
                'password' => array('required'),
                'category_id' => array('required', 'integer'),
            ),
            array(
                'username.required' => Lang::get('passwords.form.error.required.username'),
                'password.required' => Lang::get('passwords.form.error.required.password'),
                'category_id.integer' => Lang::get('passwords.form.error.integer.category_id'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        $data['user_id'] = APP_USER_ID;

        // encrypt password
        $data['password'] = Crypt::encrypt($data['password']);

        if(!$data['title']) $data['title'] = $data['username'];

        $category_slug = (string)CategoriesModel::find($data['category_id'])->slug;

        if(!PasswordsModel::insert($data))
        {
            return Redirect::back()->with('error', Lang::get('shared.error.db.insert'))->withInput();
        }

        return Redirect::to(URL::action('PasswordsController@getCategory', array($category_slug)))
            ->with('success', Lang::get('passwords.new.success'));
    }

    /**
     * Edit password layout
     * @param int $item_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getEditPassword($item_id = 0)
    {
        if (!$item_id) return Redirect::back();

        $view = View::make('passwords.edit_password');

        $PasswordsModel = new PasswordsModel();
        $password = $PasswordsModel->whereUserId()->find((int)$item_id);

        if(!$password)
        {
            return Redirect::to('/');
        }

        $password->password = Crypt::decrypt($password->password);

        $view->password = $password;

        $view->category = $password->category;

        $categories = array();

        $CategoriesModel = new CategoriesModel();

        foreach($CategoriesModel->whereUserId()->get() as $category)
        {
            $categories[$category->id] = $category->name;
        }

        $view->categories = $categories;

        $this->layout->document_title = Lang::get('passwords.edit.document_title');
        $this->layout->article_title = Lang::get('passwords.edit.article_title', array('password_title' => $password->title));
        $this->layout->article_title_icon = Lang::get('passwords.edit.article_title_icon');

        $this->layout->content = $view;
    }

    /**
     * Edit password - save request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEditPasswordSave()
    {
        $validator = Validator::make(
            array(
                'id' => Input::get('id'),
                'title' => Input::get('title'),
                'username' => Input::get('username'),
                'password' => Input::get('password'),
                'url' => Input::get('url'),
                'note' => Input::get('note'),
                'category_id' => Input::get('category_id'),
            ),
            array(
                'id' => array('required', 'integer'),
                'username' => array('required'),
                'password' => array('required'),
                'category_id' => array('required', 'integer'),
                'url' => array('url'),
            ),
            array(
                'id.required' => Lang::get('passwords.form.error.required.id'),
                'username.required' => Lang::get('passwords.form.error.required.username'),
                'password.required' => Lang::get('passwords.form.error.required.password'),
                'category_id.integer' => Lang::get('passwords.form.error.integer.category_id'),
                'url.url' => Lang::get('passwords.form.error.url.url'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        if(!$data['title']) $data['title'] = $data['username'];

        // encrypt password
        $data['password'] = Crypt::encrypt($data['password']);

        $PasswordsModel = PasswordsModel::findWhereUserId((int)$data['id']);

        if(!$PasswordsModel)
        {
            return Redirect::back()->with('error', Lang::get('shared.error.not_auth'));
        }

        $category = CategoriesModel::findWhereUserId($data['category_id']);

        if(!$category)
        {
            return Redirect::to('/');
        }

        $category_slug = $category->slug;

        if(!$PasswordsModel)
        {
            return Redirect::to(URL::action('PasswordsController@getCategory', array($category_slug)))
                ->with('error', Lang::get('shared.error.edit_foreign'));
        }

        if(!$PasswordsModel->update($data))
        {
            return Redirect::back()->with('error', Lang::get('shared.error.db.edit'))->withInput();
        }

        return Redirect::to(URL::action('PasswordsController@getCategory', array($category_slug)))
            ->with('success', Lang::get('passwords.edit.success'));
    }

    /**
     * Get random password
     * @return string
     */
    public function postRandomPassword()
    {
        $data = array(
            'type' => 'success',
            'message' => CustomSecurityHelper::random_key((int)Input::get('password_length', 10))
        );

        return Response::json($data);
    }

    /**
     * Delete all confirmation page
     * @param string $category_slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteAll($category_slug = '')
    {
        if(!$category_slug) return Redirect::back();

        $view = View::make('passwords.delete_all');

        $CategoriesModel = new CategoriesModel();

        $category = $CategoriesModel->whereUserId()->where('slug', 'LIKE', $category_slug)->first();

        $this->layout->document_title = Lang::get('passwords.delete_all.document_title');
        $this->layout->article_title = Lang::get('passwords.delete_all.article_title', array('category_name' => $category->name));
        $this->layout->article_title_icon = Lang::get('passwords.delete_all.article_title_icon');

        $view->category = $category;

        $this->layout->content = $view;
    }

    /**
     * Delete all passwords from database
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteAll()
    {
        $validator = Validator::make(
            array(
                'category_id' => Input::get('category_id'),
            ),
            array(
                'category_id' => array('required', 'integer'),
            ),
            array(
                'category_id.required' => Lang::get('passwords.delete_all.error.missing_category_id'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        // get row from db
        $PasswordsModel = new PasswordsModel();

        $passwords = $PasswordsModel->whereUserId()->where('category_id', '=', (int)$data['category_id'])->get(array('id'))->toArray();

        if(!$passwords)
        {
            return Redirect::back()->with('error', Lang::get('passwords.delete_all.error.no_passwords_within'));
        }

        $items_to_delete = array();
        foreach($passwords as $item)
        {
            $items_to_delete[] = (int)$item['id'];
        }

        if(!$PasswordsModel->whereIn('id', $items_to_delete)->delete())
        {
            return Redirect::back()->with('error', Lang::get('shared.error.db.delete'));
        }

        return Redirect::action('PasswordsController@getCategory', array(CategoriesModel::find($data['category_id'])->slug))
            ->with('success', Lang::get('passwords.delete_all.success'));
    }

    /**
     * Delete password
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeletePassword()
    {
        $validator = Validator::make(
            array(
                'id' => Input::get('id'),
            ),
            array(
                'id' => array('required', 'integer'),
            ),
            array(
                'id.required' => Lang::get('passwords.error.missing_id'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        // get row from db
        $item = PasswordsModel::findWhereUserId((int)$data['id']);

        if (!$item)
        {
            return Redirect::back()->with('error', Lang::get('shared.error.db.no_item'));
        }

        if (!$item->delete())
        {
            return Redirect::back()->with('error', Lang::get('shared.error.db.delete'));
        }

        return Redirect::back()->with('success', Lang::get('passwords.delete_password.success'));
    }
}