<div class="categories">
    {{ Form::model($category, array('action' => 'Admin\AdminCategoriesController@postEditItemSave')) }}
        <div class="row">
            <div class="form-group col-lg-6">
                {{ Form::label('name', Lang::get('admin/categories.form.label.name')) }}
                {{ Form::text('name', null, array('class' => 'form-control focus-first', 'placeholder' => Lang::get('admin/categories.form.placeholder.name'))) }}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                {{ Form::label('note', Lang::get('admin/categories.form.label.note')) }}
                {{ Form::textarea('note', null, array('class' => 'form-control', 'placeholder' => Lang::get('admin/categories.form.placeholder.note'))) }}
            </div>
        </div>
        {{ Form::hidden('id', (int)$category->id) }}
        <div class="col-lg-12">
            {{ Typography::form_button('submit', Lang::get('shared.form.button.update'), 'btn-default', 'icon-ok-sign') }}
            {{ Typography::form_button('reset', Lang::get('shared.form.button.reset'), 'btn-default', 'icon-refresh') }}
            {{ Typography::link_button(URL::action('Admin\AdminCategoriesController@getIndex'), Lang::get('shared.form.button.cancel'), 'btn-default', 'icon-remove-sign') }}
        </div>
        <p class="required_note">
            {{ Lang::get('shared.form.required_mark_footer') }}
        </p>
    {{ Form::close() }}
</div>