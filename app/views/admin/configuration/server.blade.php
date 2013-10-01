<div class="panel panel-default">
    <div class="panel-heading">
        {{ Lang::get('admin/configuration.server.database.title') }}
        -
        {{ Lang::get('admin/configuration.server.database.driver.mysql.title') }}
    </div>
    <div class="panel-body">
        {{ Typography::alert_danger(Lang::get('admin/configuration.danger_notification')) }}
        {{ Form::open(array('action' => 'Admin\AdminConfigurationController@postUpdateConfiguration')) }}
            <div class="row">
                <div class="form-group col-lg-6">
                    {{ Form::label('database-connections-mysql-driver', Lang::get('admin/configuration.server.database.form.label.driver')) }}
                    {{ Form::select('database[connections][mysql][driver]', array('mysql' => 'MySQL'), strtolower(Config::get('database.connections.mysql.driver')), array('class' => 'form-control', 'id' => 'database-connections-mysql-driver')) }}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    {{ Form::label('database-connections-mysql-host', Lang::get('admin/configuration.server.database.form.label.host')) }}
                    {{ Form::text('database[connections][mysql][host]', Config::get('database.connections.mysql.host'), array('class' => 'form-control', 'id' => 'database-connections-mysql-host')) }}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    {{ Form::label('database-connections-mysql-username', Lang::get('admin/configuration.server.database.form.label.username')) }}
                    {{ Form::text('database[connections][mysql][username]', Config::get('database.connections.mysql.username'), array('class' => 'form-control', 'id' => 'database-connections-mysql-username')) }}
                </div>
                <div class="form-group col-lg-6">
                    {{ Form::label('database-connections-mysql-password', Lang::get('admin/configuration.server.database.form.label.password')) }}
                    {{ Form::text('database[connections][mysql][password]', Config::get('database.connections.mysql.password'), array('class' => 'form-control', 'id' => 'database-connections-mysql-password')) }}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    {{ Form::label('database-connections-mysql-prefix', Lang::get('admin/configuration.server.database.form.label.prefix')) }}
                    {{ Form::text('database[connections][mysql][prefix]', Config::get('database.connections.mysql.prefix'), array('class' => 'form-control', 'id' => 'database-connections-mysql-prefix')) }}
                </div>
            </div>
            <div class="col-lg-12">
                {{ Typography::form_button('submit', Lang::get('shared.form.button.update'), 'btn-default', 'icon-ok-sign', ($canEdit ? array() : array('disabled' => 'disabled'))) }}
            </div>
        {{ Form::close() }}
    </div>
</div>