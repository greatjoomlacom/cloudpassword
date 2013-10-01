<div class="col-lg-10">
    <div class="form-group">
        {{ Form::label('name', Lang::get('admin/groups.form.label.name') . ' ' . Lang::get('shared.form.required_mark'), array('class' => 'col-lg-3 control-label')) }}
        <div class="col-lg-6">
            {{ Form::text('name', (isset($group->name) ? $group->name : Input::old('name')), array('class' => 'form-control focus-first', 'id' => 'name')) }}
        </div>
    </div>
</div>