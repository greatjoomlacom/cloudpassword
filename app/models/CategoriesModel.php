<?php

class CategoriesModel extends Eloquent {
    /**
     * Table name
     * @var string
     */
    protected $table = 'categories';

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
     * @return static
     */
    public static function findWhereUserId($id)
    {
        return static::where('user_id', '=', APP_USER_ID)->find($id);
    }

    /**
     * Remove all categories
     * Because of foreign keys within table we have to delete all rows step by step
     */
    public static function removeAllCategories()
    {
        $items = new static();
        $items = $items->whereUserId()->get();

        if(count($items))
        {
            foreach($items as $item)
            {
                try
                {
                    if($item->user_id == APP_USER_ID)
                    {
                        if(!$item->delete())
                        {
                            throw new ErrorException(Lang::get('admin/categories.delete_all.error'));
                        }
                    }
                }
                catch(ErrorException $e)
                {
                    return $e->getMessage();
                }
            }
        }
    }
}