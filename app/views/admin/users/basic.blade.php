<div class="col-lg-10">
    <div class="form-group">
        {{ Form::label('email', Lang::get('account.edit.form.label.email') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-3 control-label')) }}
        <div class="col-lg-6">
            {{ Form::text('email', (isset($user->email) ? $user->email : Input::old('email')), array('class' => 'form-control focus-first', 'id' => 'email')) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('detail-first-name', Lang::get('account.edit.form.label.first_name') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-3 control-label')) }}
        <div class="col-lg-6">
            {{ Form::text('details[first_name]', (isset($user->details->first_name) ? $user->details->first_name : Input::old('details.first_name')), array('class' => 'form-control', 'id' => 'detail-first-name')) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('details-last-name', Lang::get('account.edit.form.label.last_name') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-3 control-label')) }}
        <div class="col-lg-6">
            {{ Form::text('details[last_name]', (isset($user->details->last_name) ? $user->details->last_name : Input::old('details.last_name')), array('class' => 'form-control', 'id' => 'details-last-name')) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('password', Lang::get('account.form.label.password') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-3 control-label')) }}
        <div class="col-lg-6">
            {{ Form::password('password', array('value' => '', 'class' => 'form-control', 'id' => 'password')) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('password2', Lang::get('account.form.label.password2') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-3 control-label')) }}
        <div class="col-lg-6">
            {{ Form::password('password2', array('value' => '', 'class' => 'form-control', 'id' => 'password2')) }}
        </div>
    </div>
    @if(count($languages) > 1)
        <div class="form-group">
            {{ Form::label('details-app-language', Lang::get('account.edit.form.label.language') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-3 control-label')) }}
            <div class="col-lg-6">
                <select name="details[language]" class="form-control" id="details-app-language">
                    @foreach($languages as $code=>$language)
                        <option data-app-prepend='<i class="flag flag-{{ $code }}"></i> ' value="{{ $code }}" <?php if(isset($user->details->locale) and $app_locale === $user->details->locale): ?>selected="selected" <?php endif; ?>>{{ $language }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
</div>