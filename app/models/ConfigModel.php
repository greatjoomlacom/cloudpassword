<?php

class ConfigModel extends Eloquent {

    /**
     * Table name
     * @var string
     */
    protected $table = 'config';

    /**
     * Use timestamps
     * @var bool
     */
    public $timestamps = true;

    /**
     * Fillable items
     * @var array
     */
    protected $fillable = array(
        'context',
        'name',
        'value',
        'comment',
    );

}