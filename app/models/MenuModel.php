<?php

use \CustomHelpers\CustomUserHelper;

class MenuModel extends Eloquent {

    /**
     * Table name
     * @var string
     */
    protected $table = 'menu';

    /**
     * Get menu items
     * @param string $position
     * @return array
     */
    public function getMenuItems($position = 'header')
    {
        $menu_items = array();

        if(!defined('APP_USER_ID') or !APP_USER_ID)
        {
            return $menu_items;
        }

        $user = CustomUserHelper::getUser();

        $menu = $this->where('position', 'LIKE', $position)->orderBy('group', 'DESC')->orderBy('ordering')->get();

        foreach($menu as $menu_item)
        {
            if ($menu_item->link !== '/')
            {
                $menu_item->link =  Session::get('locale') . '/' . $menu_item->link;
            }
            else
            {
                $menu_item->link =  Session::get('locale');
            }

            // check for group view
            if($access = json_decode($menu_item->access) and !CustomUserHelper::isSuperUser())
            {
                // check for access
                if(!$user->hasAnyAccess($access))
                {
                    continue;
                }
            }

            $menu_items[$menu_item->group][$menu_item->id] = new stdClass();

            $menu_items[$menu_item->group][$menu_item->id]->context = $menu_item->context;
            $menu_items[$menu_item->group][$menu_item->id]->type = $menu_item->type;
            $menu_items[$menu_item->group][$menu_item->id]->link = '';
            $menu_items[$menu_item->group][$menu_item->id]->icon = $menu_item->icon;

            switch($menu_item->type)
            {
                case 'link':
                default:
                    if($menu_item->link)
                    {
                        $menu_items[$menu_item->group][$menu_item->id]->link = URL::to($menu_item->link);

                        // homepage
                        if (Request::is($menu_item->link))
                        {
                            $menu_items[$menu_item->group][$menu_item->id]->active = true;
                        }
                        elseif (Request::is($menu_item->link . '/*'))
                        {
                            $menu_items[$menu_item->group][$menu_item->id]->active = true;
                        }

                    }
                    break;
            }

            $menu_items[$menu_item->group][$menu_item->id]->title = Lang::get($menu_item->title);
        }

        return $menu_items;
    }

    /**
     * Get category menu  items
     * @return array
     */
    public function getCategoryMenuItems()
    {
        $menu_items = array();

        if(!defined('APP_USER_ID') or !APP_USER_ID)
        {
            return $menu_items;
        }

        // add categories table
        $categoryModel = CategoriesModel::where('user_id', '=', APP_USER_ID)->orderBy('name')->get();

        foreach($categoryModel as $item)
        {
            $tmp_menu_items = new stdClass();

            $tmp_menu_items->type = 'link';
            $tmp_menu_items->link = URL::to(Session::get('locale'), array('passwords', 'category', $item->slug));
            $tmp_menu_items->name = $item->name;

            if (Request::is('*/passwords/category/' . $item->slug))
            {
                $tmp_menu_items->active = true;
            }

            $menu_items[] = $tmp_menu_items;
        }

        return $menu_items;
    }
}