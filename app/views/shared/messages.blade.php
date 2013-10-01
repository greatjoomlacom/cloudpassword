<?php
$messages = array(
    'error' => array(), 'success' => array(), 'message' => array()
);

if(isset($errors) and $errors)
{
    $messages['error'] = array_merge($messages['error'], (array)$errors->all());
}

if ($success = Session::get('error', ''))
{
    $messages['error'] = array_merge($messages['error'], (array)$success);
}

if ($success = Session::get('success', ''))
{
    $messages['success'] = array_merge($messages['success'], (array)$success);
}
if ($message = Session::get('message', ''))
{
    $messages['message'] = array_merge($messages['message'], (array)$message);
}
?>
@if($messages['error'] or ($messages['success'] or $messages['message']))
    <div class="error_messages">
        @if($messages['error'])
            @foreach($messages['error'] as $error)
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon-exclamation-sign"></i> {{ $error }}
                </div>
            @endforeach
        @endif
        @if($messages['success'])
            @foreach($messages['success'] as $success)
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon-ok-sign"></i> {{ $success }}
                </div>
            @endforeach
        @endif
        @if($messages['message'])
            @foreach($messages['message'] as $message)
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon-info-sign"></i> {{ $message }}
                </div>
            @endforeach
        @endif
    </div>
@endif