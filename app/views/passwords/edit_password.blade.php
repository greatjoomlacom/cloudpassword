<div class="passwords">
    {{ Form::model($password, array('action' => 'PasswordsController@postEditPasswordSave')) }}
        <div class="row">
            <div class="form-group col-lg-6">
                {{ Form::label('category_id', Lang::get('passwords.form.label.category')) }}
                {{ Form::select('category_id', $categories, $category->id, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                {{ Form::label('title', Lang::get('passwords.form.label.title')) }}
                {{ Form::text('title', null, array('class' => 'form-control focus-first', 'placeholder' => Lang::get('passwords.form.placeholder.title'))) }}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                {{ Form::label('username', Lang::get('passwords.form.label.username') . ' ' . Lang::get('shared.form.required_mark')) }}
                {{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => Lang::get('passwords.form.placeholder.username'))) }}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <div class="row">
                    <div class="col-lg-12">
                        {{ Form::label('password', Lang::get('passwords.form.label.password') . ' ' . Lang::get('shared.form.required_mark')) }}
                        {{ Form::text('password', null, array('class' => 'form-control', 'placeholder' => Lang::get('passwords.form.placeholder.password'))) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-offset-6 password-generator-container">
                        <?php $generator_layer = json_encode(array('type' => 'click', 'name' => 'toggleLayer')); ?>
                        {{ HTML::link('#', Lang::get('passwords.generator.form_link'), array('data-app-event' => $generator_layer, 'data-app-linked-to' => 'toggle-layer')) }}

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                {{ Form::label('url', Lang::get('passwords.form.label.url')) }}
                {{ Form::text('url', null, array('class' => 'form-control', 'placeholder' => Lang::get('passwords.form.placeholder.url'))) }}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                {{ Form::label('note', Lang::get('passwords.form.label.note')) }}
                {{ Form::textarea('note', null, array('class' => 'form-control', 'placeholder' => Lang::get('passwords.form.placeholder.note'))) }}
            </div>
        </div>
        {{ Form::hidden('id', (int)$password->id) }}
        <div class="row col-lg-12">
            {{ Typography::form_button('submit', Lang::get('shared.form.button.update'), 'btn-default', 'icon-ok-sign') }}
            {{ Typography::form_button('reset', Lang::get('shared.form.button.reset'), 'btn-default', 'icon-refresh') }}
            {{ Typography::link_button(URL::action('PasswordsController@getCategory', array($category->slug)), Lang::get('shared.form.button.cancel'), 'btn-default', 'icon-remove-sign') }}
        </div>
        <p class="required_note">
            {{ Lang::get('shared.form.required_mark_footer') }}
        </p>
    {{ Form::close() }}
</div>


<div class="toggle-layer shadow ui-widget-content">
    <?php $passwordGeneratorCipher = json_encode(array('type' => 'submit', 'name' => 'passwordGeneratorCipher')); ?>
    {{ str_replace('<form', '<form data-app-event=\'' . $passwordGeneratorCipher . '\'', Form::open(array('action' => 'PasswordsController@postRandomPassword'))) }}
        <div class="row">
            <div class="col-lg-5">
                {{ Form::text('password_length', rand(5, 20), array('class' => 'form-control col-lg-12 text-center', 'title' => Lang::get('passwords.generator.input_generate_title'))) }}
            </div>
            <div class="col-lg-7">
                {{ Form::button(Lang::get('passwords.generator.link_generate'), array('type' => 'submit', 'class' => 'btn btn-primary')) }}
            </div>
        </div>
    {{ Form::close() }}
</div>
