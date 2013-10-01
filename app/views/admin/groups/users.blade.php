<div class="col-lg-8 margin-center">
    <table class="table table-hover">
        <tbody>
            @if($group->users)
                @foreach($group->users as $item)
                    <tr>
                        <td>
                            <?php echo HTML::mailto($item->email); ?>
                        </td>
                        <td>
                            <div class="control-buttons">
                                <?php echo str_replace('<a', '<a data-app-tooltip="1" title="' . Lang::get('shared.admin_table.action.edit') . '"', Typography::link_button(URL::action('Admin\AdminUsersController@getEdit', array($item->id)), '', 'btn-default btn-xs', 'icon-edit')); ?>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                No assigned users.
            @endif
        </tbody>
    </table>
</div>