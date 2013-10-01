<div class="administration">
    <ul class="nav nav-tabs">
        @foreach($configuration_options as $configuration_option)
            <li><a href="#{{ $configuration_option }}" data-toggle="tab">{{ Lang::get('admin/configuration.' . $configuration_option . '.title') }}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($configuration_options as $key=>$configuration_option)
            <div class="tab-pane @if($key === 0) active @endif" id="{{ $configuration_option }}">
                @include('admin.configuration.' . $configuration_option)
            </div>
        @endforeach
    </div>
</div>