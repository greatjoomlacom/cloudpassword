<div class="col-lg-10">
    <div class="panel panel-default">
        <div class="panel-heading">{{ (isset($group->name) ? $group->name : '') }}</div>
        <div class="panel-body col-lg-12">
            <div>
                <div>
                    <p>{{ Lang::get('admin/users.form.group.description') }}</p>
                    @foreach($config_permissions as $permission_name)
                        <div class="checkbox">
                            <label for="groups-{{ Str::slug($permission_name) }}">
                                <?php
                                $checked = false;
                                if(isset($groupPermissions) and in_array($permission_name, $groupPermissions))
                                {
                                    $checked = true;
                                }
                                if(isset($permissions_old_input) and in_array($permission_name, $permissions_old_input))
                                {
                                    $checked = true;
                                }
                                ?>
                                {{ Form::checkbox('permissions[]', $permission_name, $checked, array('id' => 'groups-' . Str::slug($permission_name))) }}
                                {{ Lang::get('shared.permissions.group.action.' . str_replace('.', '_', $permission_name)) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>