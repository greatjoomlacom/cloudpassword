<?php

namespace Admin;

use View,
    Lang,
    Redirect,
    Validator,
    Input,
    Str,
    URL,
    CategoriesModel,
    CustomHelpers\CustomUserHelper;

class AdminCategoriesController extends \BaseController {

    private $canEdit = 0;

    public function __construct()
    {
        parent::__construct();

        $this->beforeFilter('hasAnyAccess:categories|categories.view|categories.edit');

        $this->canEdit = CustomUserHelper::hasAnyAccess(array('categories', 'categories.edit'));

        View::share('canEdit', $this->canEdit);

        $this->beforeFilter('page:categories', array('only' => array('getIndex')));

        $this->page = (int)(isset($this->page['categories']) ? $this->page['categories'] : 0);
    }

    /**
     * Index page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getIndex()
	{
        $CategoriesModel = new CategoriesModel();

        $categories = $CategoriesModel->whereUserId()->get();

        if (!$categories)
        {
            return Redirect::to('/')->with('error', Lang::get('admin/categories.error.no_items_from_db'));
        }

        $view = View::make('admin.categories.index');

        $view->categories = $categories;

        $this->layout->document_title = Lang::get('admin/categories.document_title');
        $this->layout->article_title = Lang::get('admin/categories.article_title');
        $this->layout->article_title_icon = Lang::get('admin/categories.article_title_icon');

        $this->layout->content = $view;
	}

    /**
     * New category form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getNewItem()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $view = View::make('admin.categories.new');

        $this->layout->document_title = Lang::get('admin/categories.new.document_title');
        $this->layout->article_title = Lang::get('admin/categories.new.article_title');
        $this->layout->article_title_icon = Lang::get('admin/categories.new.article_title_icon');

        $this->layout->content = $view;
    }

    /**
     * Save new category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNewItemSave()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'name' => strip_tags(Input::get('name')),
                'note' => strip_tags(Input::get('note')),
            ),
            array(
                'name' => array('required'),
            ),
            array(
                'name.required' => Lang::get('admin/categories.form.error.required.name'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        $data['slug'] = Str::slug(strip_tags($data['name']));
        $data['user_id'] = APP_USER_ID;

        $model = new CategoriesModel();

        if(!$model->insert($data))
        {
            return Redirect::back()->with('error', Lang::get('shared.error.db.edit'))->withInput();
        }

        return Redirect::to(URL::action('Admin\AdminCategoriesController@getIndex'))
            ->with('success', Lang::get('admin/categories.new.success'));
    }

    /**
     * Delete all confirmation page
     */
    public function getDeleteAll()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $view = View::make('admin.categories.delete_all');

        $this->layout->document_title = Lang::get('admin/categories.delete_all.document_title');
        $this->layout->article_title = Lang::get('admin/categories.delete_all.article_title');
        $this->layout->article_title_icon = Lang::get('admin/categories.delete_all.article_title_icon');

        $this->layout->content = $view;
    }

    /**
     * Delete all categories and passwords (foreign key)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteAll()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        if($error = CategoriesModel::removeAllCategories())
        {
            return Redirect::to(URL::action('Admin\AdminCategoriesController@getIndex'))->with('error', $error);
        }

        return Redirect::to(URL::action('Admin\AdminCategoriesController@getIndex'))->with('success', Lang::get('admin/categories.delete_all.success'));
    }

    /**
     * Get edit category layout
     * @param string $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getEditCategory($slug = '')
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        if (!$slug) return Redirect::back();

        $view = View::make('admin.categories.edit');

        $model = new CategoriesModel();
        $category = $model->whereUserId()->where('slug', 'LIKE', $slug)->first();

        $view->category = $category;

        $this->layout->document_title = Lang::get('admin/categories.edit.document_title');
        $this->layout->article_title = Lang::get('admin/categories.edit.article_title', array('category_name' => $category->name));
        $this->layout->article_title_icon = Lang::get('admin/categories.edit.article_title_icon');

        $this->layout->content = $view;
    }

    /**
     * Update details
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEditItemSave()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'id' => (int)Input::get('id'),
                'name' => strip_tags(Input::get('name')),
                'note' => strip_tags(Input::get('note')),
            ),
            array(
                'id' => array('required', 'integer'),
                'name' => array('required'),
            ),
            array(
                'id.required' => Lang::get('admin/categories.form.error.required.id'),
                'id.integer' => Lang::get('admin/categories.form.error.integer.id'),
                'name.required' => Lang::get('admin/categories.form.error.required.name'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        $data['slug'] = Str::slug(strip_tags($data['name']));

        $model = CategoriesModel::findWhereUserId((int)$data['id']);

        if(!$model)
        {
            return Redirect::back()->with('error', Lang::get('admin/categories.delete.item_not_found'));
        }

        if(!$model->update($data))
        {
            return Redirect::back()->with('error', Lang::get('shared.error.db.edit'))->withInput();
        }

        return Redirect::to(URL::action('Admin\AdminCategoriesController@getIndex'))
            ->with('success', Lang::get('admin/categories.edit.success'));
    }

    /**
     * Delete category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteCategory()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'id' => (int)Input::get('id'),
            ),
            array(
                'id' => array('required', 'integer'),
            ),
            array(
                'id.required' => Lang::get('admin/categories.form.error.required.id'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        $model = CategoriesModel::findWhereUserId((int)$data['id']);

        if (!$model)
        {
            return Redirect::back()->with('error', Lang::get('admin/categories.delete.item_not_found'));
        }

        if(!$model->delete())
        {
            return Redirect::back()->with('error', Lang::get('shared.error.db.delete'))->withInput();
        }

        return Redirect::to(URL::action('Admin\AdminCategoriesController@getIndex'))
            ->with('success', Lang::get('admin/categories.delete.success'));
    }

}
