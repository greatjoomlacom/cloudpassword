<ul class="nav nav-pills pull-right">
    <li<?php if (Request::is($locale . '$')): ?> class="active"<?php endif; ?>><a href="{{ URL::to('/') }}">{{ Lang::get('menu.header.home.title') }}</a></li>
    @foreach($headermenu as $header_name=>$header_items)
        <li>
            {{ link_to('#' . Str::slug(Lang::get('menu.header.' . $header_name . '.title')), Lang::get('menu.header.' . $header_name . '.title'), array('data-toggle' => 'dropdown')) }}
            @if(isset($header_items) and $header_items)
                <ul class="dropdown-menu pull-right">
                    @foreach($header_items as $item)
                        <li @if(isset($item->active)) class="active" @endif>
                            {{ link_to($item->link, $item->title) }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>