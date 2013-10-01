{{ Form::open(array('action' => 'Admin\AdminCategoriesController@postDeleteAll')) }}
    {{ Typography::alert_danger(Lang::get('admin/categories.delete_all.confirmation_text')) }}
    {{ Typography::form_button('submit', Lang::get('admin/categories.delete_all.submit_button'), 'btn-danger', 'icon-exclamation-sign') }}
    {{ Typography::link_button(URL::action('Admin\AdminCategoriesController@getIndex'), Lang::get('shared.form.button.cancel'), 'btn-default', 'icon-remove-sign') }}
{{ Form::close() }}