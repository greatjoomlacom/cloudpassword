<div class="set_master_password">
    {{ Form::open(array('action' => 'Admin\AdminConfigurationController@postMasterPasswordSet')) }}
        <div class="row">
            <div class="form-group col-lg-12">
                {{ Form::label('passwd_file_path', Lang::get('admin/configuration.security.master_password.set.form.label.passwd_file_path') . ' ' . Lang::get('shared.form.required_mark')) }}
                @if($passwd_file_path and File::exists($passwd_file_path))
                    <p class="form-control-static">{{ $passwd_file_path }}</p>
                @else
                    {{ Form::text('passwd_file_path', $passwd_file_path, array('class' => 'form-control focus-first col-lg-6')) }}
                @endif
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                {{ Form::label('passwd_area_title', Lang::get('admin/configuration.security.master_password.set.form.label.passwd_area_title') . ' ' . Lang::get('shared.form.required_mark')) }}
                {{ Form::text('passwd_area_title', Lang::get('admin/configuration.security.master_password.set.form.value.passwd_area_title'), array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-3">
                {{ Form::label('passwd_user', Lang::get('admin/configuration.security.master_password.set.form.label.passwd_user') . ' ' . Lang::get('shared.form.required_mark')) }}
                {{ Form::text('passwd_user', 'master', array('class' => 'form-control')) }}
            </div>
            <div class="form-group col-lg-3">
                {{ Form::label('passwd_password', Lang::get('admin/configuration.security.master_password.set.form.label.passwd_password') . ' ' . Lang::get('shared.form.required_mark')) }}
                {{ Form::text('passwd_password', null, array('class' => 'form-control')) }}
            </div>
        </div>
        {{ Typography::form_button('submit', Lang::get('admin/configuration.security.master_password.set.form.button.save'), 'btn-primary', 'icon-ok-sign') }}
        {{ Typography::link_button(URL::action('Admin\AdminConfigurationController@getIndex'), Lang::get('shared.form.button.cancel'), 'btn-primary', 'icon-remove-sign') }}
        <p class="required_note">
            {{ Lang::get('shared.form.required_mark_footer') }}
        </p>
    {{ Form::close() }}
    <p><br /></p>
    @if($passwd_file_path and File::exists($passwd_file_path))
        {{ Form::open(array('action' => 'Admin\AdminConfigurationController@postMasterPasswordClear')) }}
            <p>{{ Lang::get('admin/configuration.security.master_password.clear.description') }}</p>
            <div class="col-lg-12">
                {{ Typography::form_button('submit', Lang::get('admin/configuration.security.master_password.clear.form.button.submit'), 'btn-danger', 'icon-check-empty') }}
            </div>
        {{ Form::close() }}
    @endif

</div>