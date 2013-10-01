<div class="sidebar-menu ui-widget-content ui-corner-all">
    <h3 class="ui-widget-header ui-corner-all sidebar-menu-header">{{ Lang::get('menu.sidebar.categories.title') }}</h3>
    @if($sidebar_menu)
        <ul class="nav nav-pills nav-stacked">
            @foreach($sidebar_menu as $item)
                <li class="@if(isset($item->active) and $item->active)
                active
                @endif">
                    <a href="{{ $item->link }}">{{ $item->name }}</a>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-center"><br />{{ Lang::get('menu.sidebar.categories.no_items_to_display', array('link' => URL::action('Admin\AdminCategoriesController@getNewItem'))) }}<br /><br></p>
    @endif
</div>