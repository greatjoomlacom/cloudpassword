<div class="admin-filter-form col-lg-4">
    {{ Form::open(array('action' => 'IndexController@postSetFilter', 'class' => 'form-inline')) }}
        <div class="input-group row">
            <?php
            $filter = (array)Session::get('filter');
            $default = (isset($filter[$context]['text']) ? $filter[$context]['text'] : '');
            ?>
            {{ Form::text('filter[' . $context . '][text]', $default, array('class' => 'form-control', 'placeholder' => Lang::get('shared.form.filter.placeholder.title'))) }}
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit" title="{{ Lang::get('shared.form.filter.advanced.button.submit_title') }}"><i class="icon-search"></i></button>
                <?php $reset_event = json_encode(array('type' => 'reset', 'name' => 'clearFormValuesAndSubmit')); ?>
                <button class="btn btn-default" type="button" data-app-event='{{ $reset_event }}' title="{{ Lang::get('shared.form.filter.advanced.button.clear') }}"><i class="icon-check-empty"></i></button>
            </span>
        </div>
    {{ Form::close() }}
    @if($advanced)
        <div class="row admin-filter-advanced">
            <?php $form_data = json_encode(array('type' => 'click', 'name' => 'toggleNextContainer')); ?>
            <a href="#" data-app-event='{{ $form_data }}'>{{ Lang::get('shared.form.filter.advanced.title') }} <?php if (isset($filter) and $filter): ?> ({{ Lang::get('shared.form.filter.advanced.status_active') }})<?php endif; ?></a>
            <div class="admin-filter-advanced-content">
                {{ Form::open(array('action' => 'IndexController@postSetFilter')) }}
                    @foreach($advanced as $name=>$data)
                        <div class="form-group">
                            {{ Form::label('filter-' . $name, Lang::get('admin/users.filter.label.' . $name)) }}
                            {{ Form::select('filter[' . $context . '][' . $name . ']', $data, (isset($filter[$context][$name]) ? $filter[$context][$name] : null), array('class' => 'form-control', 'id' => 'filter-' . $name)) }}
                        </div>
                    @endforeach
                    <div class="form-group">
                        <button class="btn btn-default" type="submit"><i class="icon-filter"></i> {{ Lang::get('shared.form.filter.advanced.button.submit') }}</button>
                        <?php $reset_event = json_encode(array('type' => 'reset', 'name' => 'clearFormValuesAndSubmit')); ?>
                        <button class="btn btn-default" type="reset" data-app-event='{{ $reset_event }}'><i class="icon-check-empty"></i> {{ Lang::get('shared.form.filter.advanced.button.clear') }}</button>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    @endif
</div>