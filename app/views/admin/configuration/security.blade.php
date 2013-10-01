<div class="panel panel-default">
    <div class="panel-heading">
        {{ Lang::get('admin/configuration.security.recrypt.title') }}
    </div>
    <div class="panel-body">
        {{ Form::open(array('action' => 'Admin\AdminConfigurationController@postReCryptAllPasswords')) }}
            <p>{{ Lang::get('admin/configuration.security.recrypt.description') }}</p>
            {{ Typography::form_button('submit', Lang::get('admin/configuration.security.recrypt.form.button.submit'), 'btn btn-default', 'icon-refresh', ($canEdit ? array() : array('disabled' => 'disabled'))) }}
        {{ Form::close() }}
    </div>
</div>
@if((boolean)Config::get('shared.security.master_password.enabled'))
    <div class="panel panel-default">
        <div class="panel-heading">
            {{ Lang::get('admin/configuration.security.master_password.title') }}
        </div>
        <div class="panel-body">
            <p>{{ Lang::get('admin/configuration.security.master_password.description') }}</p>
            {{ Typography::link_button(URL::action('Admin\AdminConfigurationController@getMasterPasswordSet'), Lang::get('admin/configuration.security.master_password.form.button.submit'), 'btn btn-default', 'icon-gear', ($canEdit ? array() : array('disabled' => 'disabled'))) }}
        </div>
    </div>
@endif