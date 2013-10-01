<div class="user-details">
    <div class="col-lg-10">
        {{ Form::open(array('action' => 'AccountController@postSaveAccount', 'class' => 'form-horizontal')) }}
            <div class="form-group">
                {{ Form::label('email', Lang::get('account.edit.form.label.email') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-2 control-label')) }}
                <div class="col-lg-6">
                    {{ Form::text('email', $user->email, array('class' => 'form-control', 'id' => 'email')) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('detail-first-name', Lang::get('account.edit.form.label.first_name') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-2 control-label')) }}
                <div class="col-lg-6">
                    {{ Form::text('details[first_name]', $user->details->first_name, array('class' => 'form-control', 'id' => 'detail-first-name')) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('details-last-name', Lang::get('account.edit.form.label.last_name') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-2 control-label')) }}
                <div class="col-lg-6">
                    {{ Form::text('details[last_name]', $user->details->last_name, array('class' => 'form-control', 'id' => 'details-last-name')) }}
                </div>
            </div>
            @if(count($languages) > 1)
                <div class="form-group">
                    {{ Form::label('details-app-locale', Lang::get('account.edit.form.label.locale') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-2 control-label')) }}
                    <div class="col-lg-6">
                        <select name="details[locale]" class="form-control" id="details-app-locale">
                            @foreach($languages as $code=>$language)
                                <option data-app-prepend='<i class="flag flag-{{ $code }}"></i> ' value="{{ $code }}" <?php if($app_locale === $user->details->locale): ?>selected="selected" <?php endif; ?>>{{ $language }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="col-lg-offset-2 col-lg-6">
                {{ Typography::form_button('submit', Lang::get('shared.form.button.update'), 'btn-default', 'icon-ok-sign') }}
                {{ Typography::form_button('reset', Lang::get('shared.form.button.reset'), 'btn-default', 'icon-refresh') }}
                {{ Typography::link_button(URL::to('/'), Lang::get('shared.form.button.cancel'), 'btn-default', 'icon-remove-sign') }}
            </div>
            <div class="cleaner"></div>
            <p class="required_note">
                {{ Lang::get('shared.form.required_mark_footer') }}
            </p>
            {{ Form::hidden('id', (int)$user->id) }}
        {{ Form::close() }}
    </div>
</div>