<div class="panel panel-default">
    <div class="panel-heading">
        {{ Lang::get('admin/configuration.default.site.title') }}
    </div>
    <div class="panel-body">
        {{ Form::open(array('action' => 'Admin\AdminConfigurationController@postUpdateConfiguration')) }}
            <div class="row">
                <div class="form-group col-lg-6">
                    {{ Form::label('shared-sitename', Lang::get('admin/configuration.default.site.form.label.sitename')) }}
                    {{ Form::text('shared[sitename]', Config::get('shared.sitename'), array('class' => 'form-control', 'id' => 'shared-sitename')) }}
                </div>
            </div>
            @if(count($languages) > 1)
                <div class="row">
                    <div class="form-group col-lg-6">
                        {{ Form::label('app-locale', Lang::get('admin/configuration.default.site.form.label.language')) }}
                        <select name="app[locale]" class="form-control" id="app-locale">
                            @foreach($languages as $code=>$language)
                                <option data-app-prepend='<i class="flag flag-{{ $code }}"></i> ' value="{{ $code }}" <?php if($app_locale === $code): ?>selected="selected" <?php endif; ?>>{{ $language }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="col-lg-12">
                {{ Typography::form_button('submit', Lang::get('shared.form.button.update'), 'btn-default', 'icon-ok-sign', ($canEdit ? array() : array('disabled' => 'disabled'))) }}
            </div>
        {{ Form::close() }}
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        {{ Lang::get('admin/configuration.default.layout.title') }}
    </div>
    <div class="panel-body">
        {{ Form::open(array('action' => 'Admin\AdminConfigurationController@postUpdateConfiguration')) }}
        <div class="row">
            <div class="form-group col-lg-6">
                {{ Form::label('shared-ui-theme', Lang::get('admin/configuration.default.layout.form.label.ui.theme')) }}
                {{ Form::select('shared[ui][theme]', Config::get('shared.ui.themes'), Config::get('shared.ui.theme'), array('class' => 'form-control', 'id' => 'shared-ui-theme')) }}
            </div>
        </div>
        <div class="col-lg-12">
            {{ Typography::form_button('submit', Lang::get('shared.form.button.update'), 'btn-default', 'icon-ok-sign', ($canEdit ? array() : array('disabled' => 'disabled'))) }}
        </div>
        {{ Form::close() }}
    </div>
</div>