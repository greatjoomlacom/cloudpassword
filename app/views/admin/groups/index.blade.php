<div class="admin-users">
    <div class="admin-panel">
        {{ $filter }}
        @if($canEdit)
            <div class="admin-control-buttons pull-right row">
                <?php echo Typography::link_button(URL::action('Admin\AdminGroupsController@getNew'), Lang::get('shared.control_buttons.add_new'), 'btn-primary', 'icon-plus'); ?>
            </div>
        @endif
        <hr class="cleaner" />
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>{{ $ordering_name }}</th>
                @if($canEdit)
                    <th class="col-lg-2">{{ Lang::get('shared.admin_table.thead.options') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if(count($groups))
                @foreach($groups as $item)
                    <tr>
                        <td>
                            {{ strip_tags($item->name) }} @if(Config::get('shared.groups.show_count_of_users') and $count_of_users = count($item->users))<span class="label label-default">{{ Lang::choice('admin/groups.table.count_of_users', $count_of_users, array('count' => $count_of_users)) }}</span>@endif
                        </td>
                        @if($canEdit)
                            <td>
                                <div class="control-buttons">
                                    <?php echo str_replace('<a', '<a data-app-tooltip="1" title="' . Lang::get('shared.admin_table.action.edit') . '"', Typography::link_button(URL::action('Admin\AdminGroupsController@getEdit', array($item->id)), '', 'btn-default btn-xs', 'icon-edit')); ?>
                                    @if((int)$item->id !== 1000 and (int)$item->id !== 1)
                                        <?php $form_action = json_encode(array('type' => 'submit', 'name' => 'confirmAction')); ?>
                                            {{ str_replace('<form', '<form data-app-event=\'' . $form_action . '\'', Form::open(array('action' => 'Admin\AdminGroupsController@postDelete'))) }}
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
                    <td rowspan="4">{{ Lang::get('shared.error.no_data_to_display') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    <?php echo $groups->links(); ?>
</div>