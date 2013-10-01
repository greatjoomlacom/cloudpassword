<!DOCTYPE html>
<html>
    <head>
        <title>@if($document_title_prefix){{ $document_title_prefix }} - @endif{{ $document_title }}@if($document_title_suffix) - {{ $document_title_suffix }}@endif</title>

        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        {{ HTML::style('shared/bootstrap/css/bootstrap.min.css') }}
        {{ HTML::style('shared/font-awesome/css/font-awesome.css') }}
        {{ HTML::style('shared/css/login.css') }}
        {{ HTML::style('shared/css/app.css') }}

        <link href="{{ Request::root() }}/shared/images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />

    </head>
    <body>
        @include('account.login')
        {{ HTML::script('shared/jquery/jquery-2.0.2.min.js') }}
        {{-- Bootstrap goes first to prevent jQuery UI library collapses. --}}
        {{ HTML::script('shared/bootstrap/js/bootstrap.min.js') }}
        {{ HTML::script('shared/jqueryui/js/jquery-ui-1.10.3.custom.min.js') }}
        {{ HTML::script('shared/js/app.js') }}
        {{ HTML::script('shared/js/login.js') }}
    </body>
</html>