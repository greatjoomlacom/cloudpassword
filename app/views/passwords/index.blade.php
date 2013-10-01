<div class="passwords">
    <div class="admin-panel">
        @if($canEdit)
            <div class="admin-control-buttons pull-right row">
                <?php echo Typography::link_button(URL::action('PasswordsController@getNewPassword', array($category->slug)), Lang::get('passwords.admin_panel.button_add_new'), 'btn-primary', 'icon-plus'); ?>
                @if(count($passwords) > 1)
                    <?php echo Typography::link_button(URL::action('PasswordsController@getDeleteAll', array($category->slug)), Lang::get('passwords.admin_panel.button_delete_all'), 'btn-danger', 'icon-trash'); ?>
                @endif
            </div>
        @endif
        <hr class="cleaner" />
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>{{ Lang::get('passwords.table.thead.title') }}</th>
                <th>{{ Lang::get('passwords.table.thead.name') }}</th>
                <th>{{ Lang::get('passwords.table.thead.password') }}</th>
                <th class="col-lg-2">{{ Lang::get('passwords.table.thead.options') }}</th>
            </tr>
        </thead>
        @if(count($passwords) !== 0)
            <tbody>
                @foreach($passwords as $item)
                    <tr>
                        <td>
                            <div class="password-item">
                                <div>
                                    {{ $item->title }}
                                    @if($item->note)
                                        <span class="table-icon-action icon-info-sign password-note" data-app-tooltip="1" title="{{ $item->note }}"></span>
                                    @endif
                                    @if($item->url)
                                        <a href="{{ $item->url }}" target="_blank" class="no-decoration" data-app-tooltip="1" title="{{ Lang::get('passwords.table.actions.open_link') }}"><i class="table-icon-action icon-external-link"></i></a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $item->username }}
                            <?php $form_data = json_encode(array('type' => 'click', 'name' => 'promptWindowsWithValue', 'value' => $item->username, 'prompt_message' => Lang::get('passwords.copy_password.prompt_header'))); ?>
                            <a href="#" data-app-event='{{ $form_data }}' class="no-decoration"><span class="table-icon-action icon-copy inline-block" data-app-tooltip="1" title="{{ Lang::get('passwords.table.actions.copy_username') }}"></span></a>
                        </td>
                        <td>
                            ******
                            <?php $form_data = json_encode(array('type' => 'click', 'name' => 'copyToClipboard')); ?>
                            {{ Form::open(array('action' => 'PasswordsController@postCopyPassword', 'class' => 'password-icon-form')) }}
                                <a href="#" data-app-event='{{ $form_data }}'><span class="table-icon-action icon-copy" data-app-tooltip="1" title="{{ Lang::get('passwords.table.actions.copy_password') }}"></span></a>
                                {{ Form::hidden('id', (int)$item->id) }}
                            {{ Form::close() }}
                        </td>
                        <td>
                            <div class="control-buttons">
                                <?php echo str_replace('<a', '<a data-app-tooltip="1" title="' . Lang::get('passwords.control_buttons.edit') . '"', Typography::link_button(URL::action('PasswordsController@getEditPassword', array($item->id)), '', 'btn-danger btn-xs', 'icon-edit')); ?>
                                <?php $form_action = json_encode(array('type' => 'submit', 'name' => 'confirmAction')); ?>
                                {{ str_replace('<form', '<form data-app-event=\'' . $form_action . '\'', Form::open(array('action' => 'PasswordsController@postDeletePassword'))) }}
                                    {{ str_replace('<button', '<button data-app-tooltip="1" title="' . Lang::get('passwords.control_buttons.delete') . '"', Typography::form_button('submit', '', 'btn-danger btn-xs', 'icon-trash')) }}
                                    {{ Form::hidden('id', $item->id) }}
                                {{ Form::close() }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @else
            <tr>
                <td rowspan="4">{{ Lang::get('shared.error.no_data_to_display') }}</td>
            </tr>
        @endif
    </table>
</div>