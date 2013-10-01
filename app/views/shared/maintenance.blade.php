<!DOCTYPE html>
<html>
    <head>
        <title>{{ Lang::get('maintenance.document_title') }}</title>

        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        {{ HTML::style('shared/bootstrap/css/bootstrap.min.css') }}
        {{ HTML::style('shared/bootstrap/css/bootstrap-theme.min.css') }}
        {{ HTML::style('shared/css/maintenance.css') }}

        <link href="{{ Request::root() }}/shared/images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />

    </head>
    <body>
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="jumbotron col-lg-6">
                <div class="container">
                    <h1>{{ Lang::get('maintenance.header') }}</h1>
                    <p>{{ Lang::get('maintenance.description') }}</p>
                </div>
            </div>
        </div>
    </body>
</html>