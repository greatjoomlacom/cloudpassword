<button type="{{ $type }}"<?php if (isset($data) and $data): ?> data-app-ui-button='<?php echo json_encode($data); ?>'<?php endif; ?>@if($attributes) {{ $attributes }}@endif>{{ $value }}</button>
