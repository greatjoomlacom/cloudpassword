<div class="admin-boxes">
    @if(isset($dashboard_items) and $dashboard_items)
        @foreach($dashboard_items as $item)
            @if(isset($item) and $item)
                @foreach($item as $item)
                    <div class="box ui-hovered-class ui-widget-content ui-corner-all">
                        <a href="{{ $item->link }}" class="no-decoration">
                            <h3>{{ Lang::get("menu.items.admin.$item->context") }}</h3>
                            {{-- <i class="box-image icon-user"></i> --}}
                            <span class="box-image {{ $item->icon }}"></span>
                        </a>
                    </div>
                @endforeach
            @endif
        @endforeach
    @endif
    <div class="clearfix"></div>
</div>