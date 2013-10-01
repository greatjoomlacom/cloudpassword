<div class="col-lg-10">
    <p>Bellow you can create <b>one</b> super user account with unlimited privileges. This account is protected and it is not possible to delete it later at all.</p>
    <br />
    {{ Form::open(array('action' => 'InstallController@postCreateAnAccount', 'class' => 'form-horizontal')) }}
        <div class="form-group">
            {{ Form::label('email', Lang::get('account.edit.form.label.email') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-2 control-label')) }}
            <div class="col-lg-6">
                {{ Form::text('email', Input::old('email'), array('class' => 'form-control focus-first', 'id' => 'email')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('detail-first-name', Lang::get('account.edit.form.label.first_name') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-2 control-label')) }}
            <div class="col-lg-6">
                {{ Form::text('details[first_name]', Input::old('details.first_name'), array('class' => 'form-control', 'id' => 'detail-first-name')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('details-last-name', Lang::get('account.edit.form.label.last_name') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-2 control-label')) }}
            <div class="col-lg-6">
                {{ Form::text('details[last_name]', Input::old('details.last_name'), array('class' => 'form-control', 'id' => 'details-last-name')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('password', Lang::get('account.edit.form.label.password') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-2 control-label')) }}
            <div class="col-lg-6">
                {{ Form::password('password', array('class' => 'form-control', 'id' => 'password')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('password2', Lang::get('account.edit.form.label.password2') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-2 control-label')) }}
            <div class="col-lg-6">
                {{ Form::password('password2', array('class' => 'form-control', 'id' => 'password2')) }}
            </div>
        </div>
        @if(count($languages) > 1)
            <div class="form-group">
                {{ Form::label('details-app-language', Lang::get('account.edit.form.label.language') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-2 control-label')) }}
                <div class="col-lg-6">
                    <select name="details[language]" class="form-control" id="details-app-language">
                        @foreach($languages as $code=>$language)
                            <option data-app-prepend='<i class="flag flag-{{ $code }}"></i> ' value="{{ $code }}" <?php if($app_locale === Input::get('details.language')): ?>selected="selected" <?php endif; ?>>{{ $language }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class="cleaner"></div>
        <p class="required_note col-lg-offset-7">
            {{ Lang::get('shared.form.required_mark_footer') }}
        </p>
        <div class="col-lg-offset-2">
            {{ Typography::form_button('submit', Lang::get('install.form.button.save_account'), 'btn btn-primary', 'icon-ok-sign') }}
        </div>
    {{ Form::close() }}
    <p><br /></p>
    <p><br /></p>
    <p><br /></p>
    <p><br /></p>
    <a href="{{ URL::action('InstallController@getRestartInstallation') }}" class="btn btn-danger"><i class="icon-refresh"></i> Restart installation</a>
</div>