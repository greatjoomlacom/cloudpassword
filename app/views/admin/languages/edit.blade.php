<?php use CustomHelpers\CustomPathHelper; ?>
<div class="administration administration-languages">
    @if(isset($items) and count($items))
        <div data-app-ui-accordion='<?php echo json_encode(array('active' => false, 'collapsible' => true, 'heightStyle' => 'content')); ?>'>
            @foreach($items as $filepath=>$items)
                <h3>{{ $filepath }}</h3>
                <div>
                    {{ Form::open(array('action' => 'Admin\AdminLanguagesController@postUpdateLanguageFile')) }}
                        @foreach($items as $key=>$item)
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    {{ Form::label($key, $key) }}
                                    {{ Form::text(str_replace(CustomPathHelper::clean(app_path('lang') . DIRECTORY_SEPARATOR . $code . DIRECTORY_SEPARATOR), '', CustomPathHelper::stripExt($filepath)) . '[' . str_replace('.', '][', $key) . ']', $item, array('class' => 'form-control', 'id' => $key)) }}
                                </div>
                            </div>
                        @endforeach
                        {{ Form::hidden('code', $code) }}
                        <div class="col-lg-12">
                            {{ Typography::form_button('submit', Lang::get('admin/languages.update.form.button.update'), 'btn-default', 'icon-refresh', (!$canEdit ? array('disabled' => 'disabled') : array())) }}
                        </div>
                    {{ Form::close() }}
                </div>
            @endforeach
        </div>
    @endif
    <p><br /></p>
    {{ Typography::link_button(URL::action('Admin\AdminLanguagesController@getIndex'), Lang::get('shared.form.button.back'), 'btn btn-default', 'icon-circle-arrow-left') }}
</div>