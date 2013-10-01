<div class="admin-users">
    <div class="admin-panel">
        {{ $filter }}
        @if($canEdit)
            <div class="admin-control-buttons pull-right row">
                <?php echo Typography::link_button(URL::action('Admin\AdminUsersController@getNewUser'), Lang::get('shared.control_buttons.add_new'), 'btn-primary', 'icon-plus'); ?>
            </div>
        @endif
        <hr class="cleaner" />
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="col-lg-6">{{ $ordering_name }}</th>
                <th class="col-lg-1 text-center">{{ Lang::get('admin/users.table.thead.email') }}</th>
                <th class="col-lg-3">{{ Lang::get('admin/users.table.thead.dateinfo') }}</th>
                <th class="col-lg-3 text-center">{{ Lang::get('admin/users.table.thead.language') }}</th>
                <th class="col-lg-3">{{ Lang::get('admin/users.table.thead.groups') }}</th>
                @if($canEdit)
                    <th class="col-lg-2">{{ Lang::get('shared.admin_table.thead.options') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if(count($users))
                @foreach($users as $item)
                    <tr>
                        <td>
                            {{ strip_tags($item->details->first_name . ' ' . $item->details->last_name) }}
                        </td>
                        <td class="text-center">
                            <a href="mailto: {{ HTML::email($item->email) }}" data-app-tooltip="1" title="{{ HTML::email($item->email) }}" class="no-decoration"><i class="icon-envelope-alt"></i></a>
                        </td>
                        <td>
                            <div>{{ $item->created_at }}</div>
                            @if($item->updated_at and $item->updated_at !== '0000-00-00 00:00:00')
                                <div>{{ $item->updated_at }}</div>
                            @endif
                        </td>
                        <td class="text-center"><span title="{{ Lang::get('language.name', array(), $item->details->language) }}" class="flag flag-{{ $item->details->language }}" data-app-tooltip="1"></span></td>
                        <td>
                            @if($groups = $item->getGroups())
                                @foreach($groups as $group)
                                    <div>{{ $group->name }}</div>
                                @endforeach
                            @endif
                        </td>
                        @if($canEdit)
                            <td>
                                <div class="control-buttons">
                                    <?php echo str_replace('<a', '<a data-app-tooltip="1" title="' . Lang::get('shared.admin_table.action.edit') . '"', Typography::link_button(URL::action('Admin\AdminUsersController@getEdit', array($item->id)), '', 'btn-default btn-xs', 'icon-edit')); ?>
                                    @if(APP_USER_ID != $item->id)
                                        <?php $form_action = json_encode(array('type' => 'submit', 'name' => 'confirmAction')); ?>
                                            {{ str_replace('<form', '<form data-app-event=\'' . $form_action . '\'', Form::open(array('action' => 'Admin\AdminUsersController@postDelete'))) }}
                                            {{ str_replace('<button', '<button data-app-tooltip="1" title="' . Lang::get('shared.admin_table.action.delete') . '"', Typography::form_button('submit', '', 'btn-danger btn-xs', 'icon-trash')) }}
                                            {{ Form::hidden('id', (int)$item->id) }}
                                        {{ Form::close() }}
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6">{{ Lang::get('shared.error.no_data_to_display') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    <?php echo $users->links(); ?>
</div>