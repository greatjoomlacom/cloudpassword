<?php

class PasswordsModel extends Eloquent {

    /**
     * Table name
     * @var string
     */
    protected $table = 'passwords';

    /**
     * Use timestamps
     * @var bool
     */
    public $timestamps = true;

    /**
     * Guarded fields
     * @var array
     */
    protected $guarded = array(
        'id'
    );

    /**
     * Item belongs to category
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('CategoriesModel', 'category_id');
    }

    /**
     * Add user_id "where" SQL to the query
     * @return static
     */
    public function whereUserId()
    {
        return static::where('user_id', '=', APP_USER_ID);
    }

    /**
     * Find item related to logged in user id
     * @param $id
     * @param array $columns
     * @return static
     */
    public static function findWhereUserId($id, $columns = array())
    {
        return static::where('user_id', '=', APP_USER_ID)->find($id);
    }

}