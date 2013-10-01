<?php

use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;

class User extends SentryUserModel {

    protected $with = array('details', 'groups');

    /**
     * Users details
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function details()
    {
        return $this->hasOne('UsersDetailsModel');
    }

    /**
     * Find all users and paginate
     * @return array
     */
    public static function findAll()
    {
        $User = new static;
        $items = $User->newQuery()->get()->all();
        return $items;
    }

    /**
     * Modify created_at column
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        if(!$value or $value === '0000-00-00 00:00:00') return $value;

        $date = new DateTime($value);
        return $date->format(Config::get('shared.datetime.format'));
    }

    /**
     * Modify updated_at column
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        if(!$value or $value === '0000-00-00 00:00:00') return $value;

        $date = new DateTime($value);
        return $date->format(Config::get('shared.datetime.format'));
    }

}