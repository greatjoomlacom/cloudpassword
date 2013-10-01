<?php $form_data = json_encode(array('type' => 'click', 'name' => 'submitFormNext')); ?>
<a href="#" data-app-event='{{ $form_data }}'>{{ Lang::get('admin/' . $context . '.table.thead.' . $subject . '') }} <span class="<?php if (!isset($ordering['direction']) or $ordering['direction'] === 'ASC'): ?>icon-sort-by-alphabet<?php else: ?>icon-sort-by-alphabet-alt<?php endif; ?>"></span></a>
{{ Form::open(array('action' => 'IndexController@postSetOrdering')) }}
    @if(isset($ordering))
        {{ Form::hidden('ordering[' . $context . '][subject]', ($ordering[$context]['subject'] ? $ordering[$context]['subject'] : 'name')) }}
        {{ Form::hidden('ordering[' . $context . '][direction]', ($ordering[$context]['direction'] ? ($ordering[$context]['direction'] === 'ASC' ? 'DESC' : 'ASC') : 'ASC')) }}
    @endif
{{ Form::close() }}