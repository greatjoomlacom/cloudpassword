<?php

namespace Admin;

use CustomHelpers\CustomViewHelper;
use View,
    Lang,
    Validator,
    Input,
    Session,
    Redirect,
    UsersDetailsModel,
    Cartalyst\Sentry\Users\UserNotFoundException,
    Cartalyst\Sentry\Users\UserExistsException,
    Cartalyst\Sentry\Groups\GroupNotFoundException,
    Cartalyst\Sentry\Facades\Laravel\Sentry,
    CustomHelpers\CustomUserHelper,
    CustomHelpers\CustomLanguageHelper,
    Exception,
    User;

class AdminUsersController extends \BaseController {

    private $canEdit = 0;

    public function __construct()
    {
        parent::__construct();

        $this->beforeFilter('hasAnyAccess:users|users.view|users.edit');
        $this->canEdit = CustomUserHelper::hasAnyAccess(array('users', 'users.edit'));
        View::share('canEdit', $this->canEdit);

        $this->beforeFilter('page:users', array('only' => array('getIndex')));

        $this->page = (int)(isset($this->page['users']) ? $this->page['users'] : 0);
    }

    /**
     * Get index
     */
    public function getIndex()
	{
        $view = View::make('admin.users.index');

        $this->layout->document_title = Lang::get('admin/users.document_title');
        $this->layout->article_title = Lang::get('admin/users.article_title');
        $this->layout->article_title_icon = Lang::get('admin/users.article_title_icon');

        $users = CustomUserHelper::getAllUsers();

        // filtering
        $filter = (array)Session::get('filter', array());
        if(isset($filter['users']) and $filter = $filter['users'])
        {
            if(isset($filter['text']) and $filter_text = $filter['text'])
            {
                $users = array_filter($users, function($user) use($filter_text)
                {
                    $details = $user->details->getAttributes();
                    $user = $user->getAttributes();
                    $searches = array_merge($user, $details);

                    $search_in = array(
                        'email',
                        'first_name',
                        'last_name',
                    );

                    $founded = false;
                    foreach($searches as $key=>$value)
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

            if(isset($filter['group']))
            {
                if($filter_group = (int)$filter['group'])
                {
                    $users = array_filter($users, function($user) use($filter_group)
                    {
                        foreach($user->groups as $group)
                        {
                            if($group->id == $filter_group)
                            {
                                return true;
                            }
                        }

                        return false;
                    });
                }
                else
                {
                    unset($filter['group']);
                }

            }
            if(isset($filter['language']))
            {
                if($filter_language = $filter['language'])
                {
                    $users = array_filter($users, function($user) use($filter_language)
                    {
                        return $user->details->language === $filter_language;
                    });

                }
                else
                {
                    unset($filter['language']);
                }
            }
        }

        // ordering
        if($ordering = (array)Session::get('ordering', array()))
        {
            if(isset($ordering['users']) and $ordering = $ordering['users'])
            {
                usort($users, function($user_a, $user_b) use($ordering)
                {
                    if(!isset($ordering['subject']) or !$ordering['subject']) $ordering['subject'] = 'name';
                    if(!isset($ordering['direction']) or !$ordering['direction']) $ordering['direction'] = 'ASC';

                    switch($ordering['subject'])
                    {
                        case 'name':
                        default:
                            $subject_a = $user_a->details->first_name . ' ' . $user_a->details->last_name;
                            $subject_b = $user_b->details->first_name . ' ' . $user_b->details->last_name;

                            switch($ordering['direction'])
                            {
                                case 'ASC':
                                default:
                                    return (strtolower($subject_a) > strtolower($subject_b));
                                    break;
                                case 'DESC':
                                    return (strtolower($subject_a) < strtolower($subject_b));
                                    break;
                            }

                            break;
                    }
                });
            }
        }

        // default ordering
        if(!isset($ordering['users']))
        {
            $ordering = array();
            $ordering['users']['subject'] = 'name';
            $ordering['users']['direction'] = 'ASC';
        }

        $view->ordering_name = CustomViewHelper::orderingView('users', 'name', $ordering);

        $users = \Paginator::make(
            array_slice($users, (((int)Input::get('page', 1) * APP_SHARED_PAGINATE) - APP_SHARED_PAGINATE), APP_SHARED_PAGINATE),
            count($users),
            APP_SHARED_PAGINATE
        );

        $view->users = $users;

        $groups = array();
        static $once;
        foreach(CustomUserHelper::getAllGroups(true) as $group)
        {
            if(!$once)
            {
                $groups[0] = Lang::get('shared.form.select');
                $once = 1;
            }
            $groups[(int)$group->id] = $group->name;
        }
        $view->groups = $groups;

        $languages = array();
        static $language_once;
        foreach(CustomLanguageHelper::getListOfLanguages() as $code=>$name)
        {
            if(!$language_once)
            {
                $languages[''] = Lang::get('shared.form.select');
                $language_once = 1;
            }
            $languages[$code] = $name;
        }
        $view->languages = $languages;

        $view->filter = CustomViewHelper::filterView('users',
            array(
                'group' => $groups,
                'language' => $languages,
            )
        );

        $this->layout->content = $view;
	}

    /**
     * New user form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getNewUser()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $view = View::make('admin.users.new');

        $this->layout->document_title = Lang::get('admin/users.new.document_title');
        $this->layout->article_title = Lang::get('admin/users.new.article_title');
        $this->layout->article_title_icon = Lang::get('admin/users.new.article_title_icon');

        $view->languages = CustomLanguageHelper::getListOfLanguages();

        $accordion_panels = array(
            'basic',
            'groups',
        );

        $view->accordion_panels = $accordion_panels;

        $view->allGroups = CustomUserHelper::getAllGroups();

        $this->layout->content = $view;
    }

    /**
     * Save new user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNewUserSave()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'email' => strip_tags(Input::get('email')),
                'password' => strip_tags(Input::get('password')),
                'password2' => strip_tags(Input::get('password2')),
                'details' => array_map('strip_tags', (array)Input::get('details')),
                'groups' => Input::get('groups', ''),
            ),
            array(
                'password' => array('required'),
                'password2' => array('required'),
                'email' => array('required', 'email'),
                'details' => array('required'),
                'groups' => array('required'),
            ),
            array(
                'password.required' => Lang::get('admin/users.status.error.required.password'),
                'password2.required' => Lang::get('admin/users.status.error.required.password2'),
                'email.required' => Lang::get('admin/users.status.error.required.email'),
                'email.email' => Lang::get('admin/users.status.error.email.email'),
                'details.required' => Lang::get('admin/users.status.error.required.details'),
                'groups.required' => Lang::get('admin/users.status.error.required.groups'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $data = $validator->getData();

        // update #__user table
        try
        {
            // passwords do not match
            if($data['password'] !== $data['password2'])
            {
                throw new \Exception(Lang::get('admin/users.status.error.passwords_do_not_match'));
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

            // add user to group
            if(isset($data['groups']) and $data['groups'])
            {
                foreach($data['groups'] as $group_id)
                {
                    $group = Sentry::getGroupProvider()->findById($group_id);
                    $user->addGroup($group);
                }
            }

        }
        catch (UserExistsException $e)
        {
            return Redirect::back()->with('error', Lang::get('admin/users.status.error.user.exists', array('id' => $data['email'])))->withInput();
        }
        catch (GroupNotFoundException $e)
        {
            //return Redirect::back()->with('error', Lang::get('admin/users.status.error.group.not_found', array('id' => $group_id)));
        }
        catch(Exception $e)
        {
            return Redirect::back()->with('error', $e->getMessage())->withInput();
        }

        return Redirect::action('Admin\AdminUsersController@getIndex')
            ->with('success', Lang::get('admin/users.new.status.success', array('name' => $details['first_name'] . ' ' . $details['last_name'])));
    }

    /**
     * Edit user view
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getEdit($user_id = 0)
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        if(!$user_id) return Redirect::to('/');

        $user = CustomUserHelper::getUser((int)$user_id);

        if(!$user)
        {
            return Redirect::back()->with('error', Lang::get('admin/users.status.error.user_not_found', array('id' => $user_id)));
        }

        $view = View::make('admin.users.edit');

        $view->user = $user;
        $user_in_groups = array();

        $groups = $user->getGroups();

        foreach($groups as $group)
        {
            if($user->inGroup($group))
            {
                $user_in_groups[] = $group->id;
            }
        }

        $view->user_in_groups = $user_in_groups;

        $this->layout->document_title = Lang::get('admin/users.edit.document_title');

        $this->layout->article_title = Lang::get('admin/users.edit.article_title', array('name' => $user->details->first_name . ' ' . $user->details->last_name));
        $this->layout->article_title_icon = Lang::get('admin/users.edit.article_title_icon');

        $view->languages = CustomLanguageHelper::getListOfLanguages();

        $accordion_panels = array(
            'basic',
        );

        if (APP_USER_ID  != $user_id and !$user->isSuperuser())
        {
            // allow edit permissions only for different user then myself
            $accordion_panels[] = 'groups';
        }

        $view->accordion_panels = $accordion_panels;

        $view->allGroups = CustomUserHelper::getAllGroups();

        $this->layout->content = $view;
    }

    /**
     * Update user details
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdateUserDetails()
    {
        // check for permission
        if(!$this->canEdit)
        {
            return Redirect::to('/')->with('error', Lang::get('shared.error.not_auth'));
        }

        $validator = Validator::make(
            array(
                'id' => (int)(Input::get('id')),
                'email' => strip_tags(Input::get('email')),
                'details' => array_map('strip_tags', (array)Input::get('details')),
                'groups' => Input::get('groups', ''),
            ),
            array(
                'id' => array('required', 'integer'),
                'email' => array('required', 'email'),
                'details' => array('required'),
            ),
            array(
                'id.required' => Lang::get('admin/users.status.error.required.id'),
                'id.integer' => Lang::get('admin/users.status.error.integer.id'),
                'email.required' => Lang::get('admin/users.status.error.required.email'),
                'email.email' => Lang::get('admin/users.status.error.email.email'),
                'details.required' => Lang::get('admin/users.status.error.required.details'),
                'groups.required' => Lang::get('admin/users.status.error.required.groups'),
            )
        );

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = $validator->getData();

        // update #__user table
        try
        {
            // Find the user using the user id
            $user = Sentry::getUserProvider()->findById($data['id']);

            if(!$user->isSuperUser())
            {
                // check for groups existence
                if(!$data['groups'])
                {
                    throw new Exception(Lang::get('admin/users.status.error.required.groups'));
                }

            }

            // Update the user details
            $user->email = $data['email'];

            // Update the user
            if (!$user->update())
            {
                return Redirect::back()->with('error', Lang::get('shared.error.db.update'))->withInput();
            }

            // update #__users_details database table
            if($details = $data['details'])
            {
                // append language if there is no one set
                if(!isset($details['language']) or !$details['language'])
                {
                    $details['language'] = 'en';
                }

                $user->details()->update($details);

            }

            // update groups - except for super user account and account itself
            if($data['groups'] and !($user->isSuperUser() or APP_USER_ID == $user->id))
            {
                $group_id = 0;

                $new_groups_id = $data['groups'];

                $user_groups = $user->getGroups();
                $groups = array();

                foreach($user_groups as $k=>$g)
                {
                    $groups[] = $g->id;
                }

                if($groups)
                {
                    $groups_remove = array_diff($groups, $new_groups_id);
                    $groups_add = array_diff($new_groups_id, $groups);

                    if($groups_remove)
                    {
                        foreach($groups_remove as $group_remove)
                        {
                            $group_id = $group_remove;

                            $group = Sentry::getGroupProvider()->findById($group_remove);
                            if(!$user->removeGroup($group))
                            {
                                // error
                            }
                        }
                    }

                    if($groups_add)
                    {
                        foreach($groups_add as $group_add)
                        {
                            $group_id = $group_add;

                            $group = Sentry::getGroupProvider()->findById($group_add);
                            if(!$user->addGroup($group))
                            {
                                // error
                            }
                        }
                    }
                }
            }
        }
        catch (UserExistsException $e)
        {
            return Redirect::back()->with('error', Lang::get('admin/users.status.error.user.exists', array('email' => $data['email'])))->withInput();
        }
        catch (UserNotFoundException $e)
        {
            return Redirect::back()->with('error', Lang::get('admin/users.status.error.user.not_found', array('email' => $data['email'])))->withInput();
        }
        catch (GroupNotFoundException $e)
        {
            return Redirect::back()->with('error', Lang::get('admin/users.status.error.group.not_found', array('id' => $group_id)))->withInput();
        }
        catch (Exception $e)
        {
            return Redirect::back()->with('error', $e->getMessage());
        }

        if($page = $this->page)
        {
            $redirect = Redirect::action('Admin\AdminUsersController@getIndex', array('page' => $page));
        }
        else
        {
            $redirect = Redirect::action('Admin\AdminUsersController@getIndex');
        }

        return $redirect
            ->with('success', Lang::get('admin/users.edit.status.success'));
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

        // user is trying to delete himself
        if (APP_USER_ID  == $data['id'])
        {
            return Redirect::back()->
                with('error', Lang::get('admin/users.status.error.user.delete_own'));
        }

        try
        {
            // Find the user using the user id
            $user = Sentry::getUserProvider()->findById($data['id']);

            // attempt to delete super user account
            if($user->isSuperUser())
            {
                return Redirect::back()->
                    with('error', Lang::get('admin/users.status.error.user.delete_super_user'));
            }

            /*
             * user details are deleted automatically by foreign_key
             */

            // delete user itself
            $user->delete();
        }
        catch (UserNotFoundException $e)
        {
            return Redirect::back()->with('error', Lang::get('admin/users.status.error.user.not_found', array('id' => $data['id'])));
        }

        return Redirect::back()
            ->with('success', Lang::get('admin/users.delete.status.success'));
    }

}
