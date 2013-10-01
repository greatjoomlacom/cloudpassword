<div class="languages">
    @if(isset($languages) and count($languages))
        <table class="table table-hover">
            <thead>
            <tr>
                <th>{{ Lang::get('admin/categories.table.thead.name') }}</th>
                <th>{{ Lang::get('admin/categories.table.thead.options') }}</th>
            </tr>
            </thead>
            <tbody>
                @foreach($languages as $code=>$item)
                <tr>
                    <td>
                        <span class="flag flag-{{ $code }}"></span>
                        {{ strip_tags($item) }}
                        @if($app_locale === $code)
                            <span class="label label-info">{{ Lang::get('shared.label.default') }}</span>
                        @endif
                    </td>
                    <td class="col-lg-2">
                        <div class="control-buttons">
                            @if(count($languages) > 1 and $app_locale !== $code)
                                {{ Form::open(array('action' => 'Admin\AdminLanguagesController@postSwitchLanguage')) }}
                                    {{ str_replace('<button', '<button data-app-tooltip="1" title="' . Lang::get('shared.control_buttons.default') . '"', Typography::form_button('submit', '', 'btn-default btn-xs', 'icon-random')) }}
                                    {{ Form::hidden('code', $code) }}
                                {{ Form::close() }}
                            @endif
                            <?php echo str_replace('<a', '<a data-app-tooltip="1" title="' . Lang::get('shared.control_buttons.edit') . '"', Typography::link_button(URL::action('Admin\AdminLanguagesController@getEditLanguage', array($code)), '', 'btn-default btn-xs', 'icon-edit')); ?>
                            @if($code !== 'en')
                                <?php $form_action = json_encode(array('type' => 'submit', 'name' => 'confirmAction')); ?>
                                {{ str_replace('<form', '<form data-app-event=\'' . $form_action . '\'', Form::open(array('action' => 'Admin\AdminLanguagesController@postDeleteLanguage'))) }}
                                    {{ str_replace('<button', '<button data-app-tooltip="1" title="' . Lang::get('shared.control_buttons.delete') . '"', Typography::form_button('submit', '', 'btn-default btn-xs', 'icon-trash')) }}
                                    {{ Form::hidden('code', $code) }}
                                {{ Form::close() }}
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
    <p>{{ Lang::get('admin/categories.error.no_categories') }}</p>
    @endif
</div>