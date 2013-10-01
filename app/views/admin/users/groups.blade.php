<div class="col-lg-10">
    <div class="panel panel-default">
        <div class="panel-body col-lg-12">
            @foreach($allGroups as $group)
                <div>
                    <div class="checkbox">
                        <label for="groups-{{ Str::slug($group->name) }}">
                            <?php
                            $checked = null;
                            if(isset($user_in_groups) and in_array($group->id, $user_in_groups))
                            {
                                $checked = true;
                            }
                            elseif(in_array((int)$group->id, (array)Input::old('groups', array())))
                            {
                                $checked = true;
                            }
                            ?>
                            <input name="groups[]" value="{{ $group->id }}" type="checkbox" id="groups-{{ Str::slug($group->name) }}" <?php if ($checked): ?> checked="checked"<?php endif; ?> />
                            {{ $group->name }}
                        </label>
                    </div>
                    <?php $groupPermissions = $group->getPermissions(); ?>
                    @if($groupPermissions)
                        <div>
                            <p>{{ Lang::get('admin/users.form.group.description') }}</p>
                            <ul>
                                @foreach($groupPermissions as $permission_name => $permission_value)
                                    <li>{{ Lang::get('shared.permissions.group.action.' . str_replace('.', '_', $permission_name)) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>