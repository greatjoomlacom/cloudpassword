<div class="administration admin-groups">
    {{ Form::open(array('action' => $form->action, 'class' => 'form-horizontal')) }}
    <ul class="nav nav-tabs">
        @foreach($accordion_panels as $accordion_panel)
        <li><a href="#{{ $accordion_panel }}" data-toggle="tab">{{ Lang::get('admin/groups.' . $accordion_panel . '.title') }}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($accordion_panels as $key=>$accordion_panel)
        <div class="tab-pane<?php if ($key === 0): ?> active<?php endif; ?>" id="{{ $accordion_panel }}">
            @include('admin.groups.' . $accordion_panel)
        </div>
        @endforeach
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            @if(isset($group->id))
                {{ Typography::form_button('submit', Lang::get('shared.form.button.update'), 'btn-default', 'icon-ok-sign') }}
            @else
                {{ Typography::form_button('submit', Lang::get('shared.form.button.save'), 'btn-default', 'icon-ok-sign') }}
            @endif
            {{ Typography::form_button('reset', Lang::get('shared.form.button.reset'), 'btn-default', 'icon-refresh') }}
            {{ Typography::link_button(URL::action('Admin\AdminGroupsController@getIndex'), Lang::get('shared.form.button.cancel'), 'btn-default', 'icon-remove-sign') }}
        </div>
    </div>
    <div class="cleaner"></div>
    <p class="required_note">
        {{ Lang::get('shared.form.required_mark_footer') }}
    </p>
    @if(isset($group->id))
        {{ Form::hidden('id', (int)$group->id) }}
    @endif
    {{ Form::close() }}
</div>