<?php

class UsersDetailsModel extends Eloquent {

    /**
     * Table name
     * @var string
     */
    protected $table = 'users_details';

    /**
     * Timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fillable fields
     * @var array
     */
    protected $fillable = array(
        'user_id',
        'first_name',
        'last_name',
        'language',
    );

}