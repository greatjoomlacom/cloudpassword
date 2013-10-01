<div class="login-container">
    <div class="login-form margin-center shadow col-md-3">
        <?php $form_data = json_encode(array('type' => 'submit', 'name' => 'ajaxLogin')); ?>
        {{ str_replace('<form', "<form data-login-event='$form_data'", Form::open(array('action' => 'AccountController@postLogin'))) }}
            <div class="form-group">
                {{ Form::label('email', Lang::get('account.login.form.email')) }}
                {{ Form::text('email', null, array('class' => 'form-control focus-first', 'placeholder' => 'Your Username or Email address')) }}
            </div>
            <div class="form-group">
                {{ Form::label('password', Lang::get('account.login.form.password')) }}
                {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Your Password')) }}
            </div>
            @if(count($languages) > 1)
                <div class="form-group">
                    <select name="locale" class="form-control" id="locale">
                        @foreach($languages as $code=>$language)
                            <option data-app-prepend='<i class="flag flag-{{ $code }}"></i> ' value="{{ $code }}"<?php if ($locale === $code): ?> selected="selected" <?php endif; ?>>{{ $language }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-xs-2"></div>
            <div class="col-xs-8 container">
                {{ Form::button(Lang::get('account.login.form.submit'), array('type' => 'submit', 'class' => 'btn btn-block btn-default')) }}
            </div>
            <div class="col-xs-2"></div>
        {{ Form::close() }}
        <hr class="cleaner" />
    </div>
    <div class="login-footer col-md-3 margin-center text-center">
        Cloud Password
    </div>
</div>

@if(Config::get('shared.mode') === 'demo')
    <div style="margin: 5em auto 0 auto; width: 500px;" class="well">
        <p><b>Accounts:</b></p>
        <p>Username: user@user.com<br />Password: user@user.com</p>
        <p>Username: managerview@user.com<br />Password: managerview@user.com</p>
    </div>
@endif