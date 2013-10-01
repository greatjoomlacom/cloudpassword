<?php

namespace Admin;

use View,
    Lang,
    Validator,
    Input,
    Session,
    Redirect,
    Cartalyst\Sentry\Groups\GroupNotFoundException,
    Cartalyst\Sentry\Groups\GroupExistsException,
    Cartalyst\Sentry\Facades\Laravel\Sentry,
    CustomHelpers\CustomUserHelper,
    Paginator,
    Config,
    CustomHelpers\CustomViewHelper,
    CustomHelpers\UrlHelper;

class AdminGroupsController extends \BaseController {

    private $canEdit = 0;

    public function __construct()
    {
        parent::__construct();

        $this->beforeFilter('hasAnyAccess:groups|groups.view|groups.edit');

        $this->canEdit = CustomUserHelper::hasAnyAccess(array('groups', 'groups.edit'));
        View::share('canEdit', $this->canEdit);

        $this->beforeFilter('page:groups', array('only' => array('getIndex')));

        $this->page = (int)(isset($this->page['groups']) ? $this->page['groups'] : 0);
    }

    /**
     * Get index
     */
    public function getIndex()
    {
        $view = View::make('admin.groups.index');

        $this->layout->document_title = Lang::get('admin/groups.document_title');
        $this->layout->article_title = Lang::get('admin/groups.article_title');
        $this->layout->article_title_icon = Lang::get('admin/groups.article_title_icon');

        $groups = CustomUserHelper::getAllGroups(true);

        // filtering
        $filter = (array)Session::get('filter', array());

        if(isset($filter['groups']) and $filter = $filter['groups'])
        {
            if(isset($filter['text']))
            {
                if($filter_text = $filter['text'])
                {
                    $groups = array_filter($groups, function($item) use($filter_text)
                    {
                        $item = $item->getAttributes();

                        $search_in = array(
                            'name',
                        );

                        $founded = false;
                        foreach($item as $key=>$value)
                        {
                            // only on specified fields
                            if(!in_array($key, $search_in)) continue;

                            if($f = stristr($value, $filter_text))
                            {
                                $founded = true;
                                break;
                            }
                        }
                        return $founded;
                    });
                }
                else
                {
                    unset($filter['text']);
                }
            }
        }

        // ordering
        if($ordering = (array)Session::get('ordering', array()))
        {
            if(isset($ordering['groups']) and $ordering = $ordering['groups'])
            {
                usort($groups, function($item_a, $item_b) use($ordering)
                {
                    if(!isset($ordering['subject']) or !$ordering['subject']) $ordering['subject'] = 'name';
                    if(!isset($ordering['direction']) or !$ordering['direction']) $ordering['direction'] = 'ASC';

                    switch($ordering['subject'])
                    {
                        case 'name':
                        default:
                            switch($ordering['direction'])
                            {
                                case 'ASC':
                                default:
                                    return (strtolower($item_a->name) > strtolower($item_b->name));
                                    break;
                                case 'DESC':
                                    return (strtolower($item_a->name) < strtolower($item_b->name));
                                    break;
                            }

                            break;
                    }
                });
            }
        }

        if(!isset($ordering['groups']))
        {
            $ordering = array();
            $ordering['groups']['subject'] = 'name';
            $ordering['groups']['direction'] = 'ASC';
        }

        $view->ordering_name = CustomViewHelper::orderingView('groups', 'name', $ordering);

        //$view->filter = $filter;
        $view->filter = '';

        // paginate
        $groups = Paginator::make(
            array_slice(
                $groups,
                (((int)\Input::get('page', 1) * APP_SHARED_PAGINATE) - APP_SHARED_PAGINATE),
                APP_SHARED_PAGINATE),
            count($groups),
            APP_SHARED_PAGINATE
        );

        $view->groups = $groups;

        $this->layout->content = $view;
    }

    /**
     * New group form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getNew()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $view = View::make('admin.groups.form');

        $this->layout->document_title = Lang::get('admin/groups.new.document_title');
        $this->layout->article_title = Lang::get('admin/groups.new.article_title');
        $this->layout->article_title_icon = Lang::get('admin/groups.new.article_title_icon');

        $accordion_panels = array(
            'basic',
            'permissions',
        );

        $view->accordion_panels = $accordion_panels;

        $view->allGroups = CustomUserHelper::getAllGroups();

        $view->permissions_old_input = (array)Input::old('permissions', array());

        $config_permissions = Config::get('shared.groups.permissions');
        $view->config_permissions = $config_permissions;

        // form object
        $form = new \stdClass();
        $form->action = 'Admin\AdminGroupsController@postNewSave';

        $view->form = $form;

        $this->layout->content = $view;
    }

    /*
     * Save new group
     */
    public function postNewSave()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'id' => (int)(Input::get('id')),
                'name' => strip_tags(Input::get('name')),
                'permissions' => Input::get('permissions'),
            ),
            array(
                'id' => array('required', 'integer'),
                'name' => array('required'),
                'permissions' => array('required'),
            ),
            array(
                'id.required' => Lang::get('admin/groups.status.error.required.id'),
                'id.integer' => Lang::get('admin/groups.status.error.integer.id'),
                'name.required' => Lang::get('admin/groups.status.error.required.name'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = $validator->getData();

        // make sure permissions variable is array
        if(!is_array($data['permissions']))
        {
            $data['permissions'] = (array)$data['permissions'];
        }

        // prevent add super user privileges to some group
        if(in_array(Config::get('shared.groups.protected.Super Users'), $data['permissions']))
        {
            return Redirect::back()->with('error', Lang::get('admin/groups.status.error.group.protected'))->withInput();
        }

        // update #_groups table
        try
        {
            $permissions = array();

            foreach($data['permissions'] as $permission)
            {
                $permissions[$permission] = 1;
            }

            // Create the group
            Sentry::getGroupProvider()->create(
                array(
                    'name' => $data['name'],
                    'permissions' => $permissions
                )
            );

        }
        catch (GroupExistsException $e)
        {
            return Redirect::back()->with('error', Lang::get('admin/groups.status.error.group.exists', array('name' => $data['name'])))->withInput();
        }

        return Redirect::action('Admin\AdminGroupsController@getIndex')
            ->with('success', Lang::get('admin/groups.new.status.success', array('name' => $data['name'])));

    }

    /**
     * Edit user view
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getEdit($id = 0)
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        if(!$id) return Redirect::to('/');

        $group = Sentry::getGroupProvider()->findById((int)$id);

        if(!$group)
        {
            return Redirect::back()->with('error', Lang::get('admin/groups.status.error.group.not_found', array('id' => $id)))->withInput();
        }

        $view = View::make('admin.groups.form');

        $view->group = $group;

        $groupPermissions = $group->getPermissions();

        $config_permissions = Config::get('shared.groups.permissions');
        $config_permissions;
        $view->config_permissions = $config_permissions;

        foreach($groupPermissions as $g_key=>$g_value)
        {
            if($g_value == '1')
            {
                $groupPermissions[] = $g_key;
                unset($groupPermissions[$g_key]);
            }
        }

        $view->groupPermissions = $groupPermissions;

        $this->layout->document_title = Lang::get('admin/groups.edit.document_title');

        $this->layout->article_title = Lang::get('admin/groups.edit.article_title', array('name' => $group->name));
        $this->layout->article_title_icon = Lang::get('admin/groups.edit.article_title_icon');

        $accordion_panels = array(
            'basic',
        );

        // prevent edit permissions panel for Registered and Super Users group
        if (!in_array($group->id, Config::get('shared.groups.protected')))
        {
            $accordion_panels[] = 'permissions';
        }

        if(count($group->users))
        {
            $accordion_panels[] = 'users';
        }

        // form object
        $form = new \stdClass();
        $form->action = 'Admin\AdminGroupsController@postUpdateDetails';

        $view->form = $form;

        $view->accordion_panels = $accordion_panels;

        $this->layout->content = $view;
    }

    /**
     * Update details
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdateDetails()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'id' => (int)(Input::get('id')),
                'name' => strip_tags(Input::get('name')),
                'permissions' => (array)Input::get('permissions'),
            ),
            array(
                'id' => array('required', 'integer'),
                'name' => array('required'),
                'permissions' => array('required'),
            ),
            array(
                'id.required' => Lang::get('admin/groups.status.error.required.id'),
                'id.integer' => Lang::get('admin/groups.status.error.integer.id'),
                'name.required' => Lang::get('admin/groups.status.error.required.name'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = $validator->getData();

        // disable permissions edit on super users and registered group
        if (in_array($data['id'], Config::get('shared.groups.protected')))
        {
            if(isset($data['permissions']) and $data['permissions'])
            {
                return Redirect::back()
                    ->with('error', Lang::get('admin/groups.status.error.group.protected'))
                    ->withInput();
            }
        }

        // update #_groups table
        try
        {
            // Find the user using the user id
            $item = Sentry::getGroupProvider()->findById($data['id']);

            // Update the user details
            $item->name = $data['name'];

            if(isset($data['permissions']) and $data['permissions'])
            {
                foreach($data['permissions'] as $key=>$permission)
                {
                    $data['permissions'][$permission] = 1;
                    unset($data['permissions'][$key]);
                }

                $permissions = array();

                $current_permissions = array_keys($item->getPermissions());
                $posted_permissions = array_keys($data['permissions']);

                foreach($current_permissions as $current_permission)
                {
                    if(!in_array($current_permission, $posted_permissions))
                    {
                        $permissions[$current_permission] = 0;
                    }
                    else
                    {
                        $permissions[$current_permission] = 1;
                    }
                }

                if($fresh_permissions = array_diff($posted_permissions, $current_permissions))
                {
                    foreach($fresh_permissions as $fresh_permission)
                    {
                        $permissions[$fresh_permission] = 1;
                    }
                }

                $item->permissions = $permissions;
            }

            // Update the item
            if (!$item->update())
            {
                return Redirect::back()->with('error', Lang::get('shared.error.db.update'))->withInput();
            }

        }
        catch (GroupNotFoundException $e)
        {
            return Redirect::back()
                ->with('error', Lang::get('admin/users.status.error.group.not_found', array('id' => $data['id'])))
                ->withInput();
        }

        if($page = $this->page)
        {
            $redirect = Redirect::action('Admin\AdminGroupsController@getIndex', array('page' => $page));
        }
        else
        {
            $redirect = Redirect::action('Admin\AdminGroupsController@getIndex');
        }


        return $redirect
            ->with('success', Lang::get('admin/groups.edit.status.success', array('name' => $item->name)));
    }

    /**
     * Delete user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'id' => (int)(Input::get('id')),
            ),
            array(
                'id' => array('required', 'integer'),
            ),
            array(
                'id.required' => Lang::get('admin/users.edit.status.error.required.id'),
                'id.integer' => Lang::get('admin/users.edit.status.error.integer.id'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }

        $data = $validator->getData();

        // user is trying to delete super admin group
        if(in_array($data['id'], Config::get('shared.groups.protected')))
        {
            return Redirect::back()->
                with('error', Lang::get('admin/groups.status.error.group.protected'));
        }

        try
        {
            // Find the user using the user id
            $group = Sentry::getGroupProvider()->findById($data['id']);

            $group_name = $group->name;

            // delete user itself
            $group->delete();
        }
        catch (GroupNotFoundException $e)
        {
            return Redirect::back()->with('error', Lang::get('admin/groups.status.error.group.not_found', array('id' => $data['id'])));
        }

        if(isset($group_name) and $group_name)
        {
            return Redirect::back()
                ->with('success', Lang::get('admin/groups.delete.status.success', array('name' => $group_name)));
        }
    }

}