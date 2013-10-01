{{ Form::open(array('action' => 'PasswordsController@postDeleteAll')) }}
    {{ Typography::alert_danger(Lang::get('passwords.delete_all.confirmation_text', array('category_name' => $category->name)), false) }}
    {{ Typography::form_button('submit', Lang::get('passwords.delete_all.submit_button'), 'btn-danger', 'icon-exclamation-sign') }}
    {{ Typography::link_button(URL::action('PasswordsController@getCategory', array($category->slug)), Lang::get('shared.form.button.cancel'), 'btn-default', 'icon-remove-sign') }}
    {{ Form::hidden('category_id', $category->id) }}
{{ Form::close() }}