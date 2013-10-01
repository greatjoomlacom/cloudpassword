<?php

namespace CustomHelpers;

use Sentry,
    Cartalyst\Sentry\Users\UserNotFoundException,
    User,
    Group;

class CustomUserHelper {

    /**
     * Get user
     * @return null
     */
    public static function getUser($id = 0)
    {
        try
        {
            if(!$id or !is_int($id))
            {
                // get currently logged in user
                if(!Sentry::check())
                {
                    $user = null;
                }
                else
                {
                    // Get the current active/logged in user with details
                    $user = Sentry::getUser();
                }
            }
            else
            {
                $user = Sentry::getUserProvider()->findById($id);
            }

        }
        catch (UserNotFoundException $e)
        {
            // User wasn't found, should only happen if the user was deleted
            // when they were already logged in or had a "remember me" cookie set
            // and they were deleted.
            $user = null;
        }

        return $user;
    }

    /**
     * Check if user is super user with full permissions
     * @return bool
     */
    public static function isSuperUser()
    {
        $isAdmin = false;

        if(defined('APP_USER'))
        {
            $user = static::getUser();
            if($user)
            {
                return (boolean)$user->isSuperUser();
            }
        }

        return $isAdmin;
    }

    /**
     * Check if user has permission for specified list of actions
     * @param array $permissions
     * @return bool
     */
    public static function hasAccess($permissions = array())
    {
        // user is super user
        if(static::isSuperUser()) return true;

        // no permissions
        if(!$permissions) return false;

        if (!is_array($permissions))
        {
            $permissions = (array)$permissions;
        }

        try
        {
            if($user = static::getUser())
            {
                if ($user->hasAccess($permissions))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        catch (UserNotFoundException $e)
        {
            return false;
        }

        return false;
    }

    /**
     * Check if user has permission for some of listed actions
     * @param array $permissions
     * @return bool
     */
    public static function hasAnyAccess($permissions = array())
    {
        // user is super user
        if(static::isSuperUser()) return true;

        // no permissions
        if(!$permissions) return false;

        if (!is_array($permissions))
        {
            $permissions = (array)$permissions;
        }

        try
        {
            if($user = static::getUser())
            {
                if ($user->hasAnyAccess($permissions))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        catch (UserNotFoundException $e)
        {
            return false;
        }

        return false;
    }

    /**
     * Get all users
     * @return array
     */
    public static function getAllUsers()
    {
        $users = User::findAll();
        return $users;
    }

    /**
     * Get all groups except super admin
     * @param bool $include_super_user
     * @return mixed
     */
    public static function getAllGroups($include_super_user = false)
    {
        $groups = Group::findAll();

        if(!$include_super_user)
        {
            foreach($groups as $key=>$group)
            {
                if($group->id == 1000)
                {
                    unset($groups[$key]);
                }
            }
        }

        return $groups;
    }
}
