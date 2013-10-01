<div class="col-lg-12">
    <p class="alert alert-info"><i class="icon-info-sign"></i> This application supports only MySQL databases.</p>
    <p>The only thing we have to know for now is a database connection.<br />Please fill the form bellow and click on <i>{{ Lang::get('install.form.button.install') }}</i>.</p>
    <br />
    {{ Form::open(array('action' => 'InstallController@postEstablishConnection')) }}
    <div class="row">
        <div class="form-group col-lg-6">
            {{ Form::label('database-connections-mysql-driver', Lang::get('admin/configuration.server.database.form.label.driver')) }}
            {{ Form::select('database[connections][mysql][driver]', array('mysql' => 'MySQL'), strtolower(Config::get('database.connections.mysql.driver')), array('class' => 'form-control', 'id' => 'database-connections-mysql-driver')) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6">
            {{ Form::label('database-connections-mysql-host', Lang::get('admin/configuration.server.database.form.label.host')) }}
            {{ Form::text('database[connections][mysql][host]', Config::get('database.connections.mysql.host', Input::old('database.connections.mysql.host')), array('class' => 'form-control focus-first', 'id' => 'database-connections-mysql-host')) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6">
            {{ Form::label('database-connections-mysql-database', Lang::get('admin/configuration.server.database.form.label.database')) }}
            {{ Form::text('database[connections][mysql][database]', Config::get('database.connections.mysql.database', Input::old('database.connections.mysql.database')), array('class' => 'form-control', 'id' => 'database-connections-mysql-database')) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6">
            {{ Form::label('database-connections-mysql-username', Lang::get('admin/configuration.server.database.form.label.username')) }}
            {{ Form::text('database[connections][mysql][username]', Config::get('database.connections.mysql.username', Input::old('database.connections.mysql.username')), array('class' => 'form-control', 'id' => 'database-connections-mysql-username')) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6">
            {{ Form::label('database-connections-mysql-password', Lang::get('admin/configuration.server.database.form.label.password')) }}
            {{ Form::text('database[connections][mysql][password]', Config::get('database.connections.mysql.password', Input::old('database.connections.mysql.password')), array('class' => 'form-control', 'id' => 'database-connections-mysql-password')) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6">
            {{ Form::label('database-connections-mysql-prefix', Lang::get('admin/configuration.server.database.form.label.prefix')) }}
            {{ Form::text('database[connections][mysql][prefix]', (Config::get('database.connections.mysql.prefix') ? Config::get('database.connections.mysql.prefix') : (Input::old('database.connections.mysql.prefix') ? Input::old('database.connections.mysql.prefix') : CustomHelpers\CustomSecurityHelper::random_key(5, true, true) . '_')), array('class' => 'form-control', 'id' => 'database-connections-mysql-prefix')) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6">
            {{ Form::label('database-connections-mysql-charset', Lang::get('admin/configuration.server.database.form.label.charset')) }}
            {{ Form::text('database[connections][mysql][charset]', Config::get('database.connections.mysql.charset', Input::old('database.connections.mysql.charset')), array('class' => 'form-control', 'id' => 'database-connections-mysql-charset')) }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6">
            {{ Form::label('database-connections-mysql-collation', Lang::get('admin/configuration.server.database.form.label.collation')) }}
            {{ Form::text('database[connections][mysql][collation]', Config::get('database.connections.mysql.collation', Input::old('database.connections.mysql.collation')), array('class' => 'form-control', 'id' => 'database-connections-mysql-collation')) }}
        </div>
    </div>
    <div class="col-lg-12">
        {{ Typography::form_button('submit', Lang::get('install.form.button.establish_database_connection'), 'btn btn-primary', 'icon-ok-sign') }}
    </div>
    {{ Form::close() }}
    <p><br /></p>
    <p><br /></p>
    <p><br /></p>
    <p><br /></p>
    <a href="{{ URL::action('InstallController@getRestartInstallation') }}" class="btn btn-danger"><i class="icon-refresh"></i> Restart installation</a>
</div>