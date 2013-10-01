<div class="col-lg-12">
    {{ Typography::alert_info('Database connection has been successfully established. You may proceed to next step.') }}
    {{ Form::open(array('action' => 'InstallController@postInstallDb')) }}
    {{ Typography::form_button('submit', Lang::get('install.form.button.install_db'), 'btn btn-primary', 'icon-ok-sign') }}
    {{ Form::close() }}
    <p><br /></p>
    <p><br /></p>
    <p><br /></p>
    <p><br /></p>
    <a href="{{ URL::action('InstallController@getRestartInstallation') }}" class="btn btn-danger"><i class="icon-refresh"></i> Restart installation</a>
</div>