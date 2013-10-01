<div class="categories">
    @if($canEdit)
        <div class="admin-panel">
            <div class="admin-control-buttons pull-right row">
                <?php echo Typography::link_button(URL::action('Admin\AdminCategoriesController@getNewItem'), Lang::get('admin/categories.admin_panel.button_add_new'), 'btn-primary', 'icon-plus'); ?>
                @if(count($categories) > 1)
                    <?php echo Typography::link_button(URL::action('Admin\AdminCategoriesController@getDeleteAll'), Lang::get('admin/categories.admin_panel.button_delete_all'), 'btn-danger', 'icon-trash'); ?>
                @endif
            </div>
            <hr class="cleaner" />
        </div>
    @endif
    @if(isset($categories) and count($categories))
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ Lang::get('admin/categories.table.thead.name') }}</th>
                    @if($canEdit)
                        <th class="col-lg-2">{{ Lang::get('admin/categories.table.thead.options') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
            @foreach($categories as $item)
            <tr>
                <td>
                    {{ strip_tags($item->name) }}
                    @if($item->note)
                        <span class="table-icon-action icon-info-sign password-note" data-app-tooltip="1" title="{{ strip_tags($item->note) }}"></span>
                    @endif
                </td>
                @if($canEdit)
                    <td>
                        <div class="control-buttons">
                            <?php echo str_replace('<a', '<a data-app-tooltip="1" title="' . Lang::get('admin/categories.control_buttons.edit') . '"', Typography::link_button(URL::action('Admin\AdminCategoriesController@getEditCategory', array($item->slug)), '', 'btn-danger btn-xs', 'icon-edit')); ?>
                            <?php $form_action = json_encode(array('type' => 'submit', 'name' => 'confirmAction')); ?>
                            {{ str_replace('<form', '<form data-app-event=\'' . $form_action . '\'', Form::open(array('action' => 'Admin\AdminCategoriesController@postDeleteCategory'))) }}
                                {{ str_replace('<button', '<button data-app-tooltip="1" title="' . Lang::get('admin/categories.control_buttons.delete') . '"', Typography::form_button('submit', '', 'btn-danger btn-xs', 'icon-trash')) }}
                                {{ Form::hidden('id', (int)$item->id) }}
                            {{ Form::close() }}
                        </div>
                    </td>
                @endif
            </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>{{ Lang::get('admin/categories.error.no_categories') }}</p>
    @endif
</div>