<?php

use Cartalyst\Sentry\Groups\Eloquent\Group as SentryGroupModel;

class Group extends SentryGroupModel {

    /**
     * Find all users
     * @return array
     */
    public static function findAll()
    {
        $User = new static;
        $items = $User->newQuery()->get()->all();
        return $items;
    }
}