<!DOCTYPE html>
<html>
    <head>
        <title>@if($document_title_prefix){{ $document_title_prefix }} - @endif{{ $document_title }}@if($document_title_suffix) - {{ $document_title_suffix }}@endif</title>

        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        {{ HTML::style('shared/jqueryui/css/' . Config::get('shared.ui.theme') . '/jquery-ui-1.10.3.custom.min.css') }}
        {{ HTML::style('shared/bootstrap/css/bootstrap.min.css') }}
        {{ HTML::style('shared/bootstrap/css/bootstrap-theme.min.css') }}
        {{ HTML::style('shared/font-awesome/css/font-awesome.css') }}
        {{ HTML::style('http://fonts.googleapis.com/css?family=Cinzel') }}
        {{ HTML::style('http://fonts.googleapis.com/css?family=Oxygen:400,700&subset=latin,latin-ext') }}
        {{ HTML::style('shared/css/app.css') }}

        <link href="{{ Request::root() }}/shared/images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />

    </head>
    <body>
        <div class="col-lg-12 header ui-widget-header">
            <div class="col-lg-5 logo">
                <h1><a href="{{ URL::to('/') }}" class="no-decoration">{{ Config::get('shared.sitename') }}</a></h1>
            </div>
            @if(isset($headermenu) and $headermenu)
                <div class="visible-lg col-lg-7 header-menu">
                    @include('header.menu')
                </div>
            @endif
            <div class="clearfix"></div>
        </div>
        <div class="maincontent">
            @if(\CustomHelpers\CustomUserHelper::hasAnyAccess(array('categories'), 'categories.view'. 'categories.edit'))
            <div class="col-lg-3">
                @include('sidebar.menu')
            </div>
            <div class="col-lg-9">
            @else
                <div class="col-lg-12">
            @endif
                @include('shared.messages')
                @if(isset($article_title) and $article_title)
                    <div class="article_title well well-sm">
                        <h3>
                            @if(isset($article_title_icon) and $article_title_icon)
                                <i class="hidden-sm pull-left {{ $article_title_icon }}"></i>
                            @endif
                            {{ $article_title }}
                        </h3>
                    </div>
                @endif
                @if(isset($content) and $content)
                    <div class="article_content">
                        {{ $content }}
                    </div>
                @endif
            </div>
        </div>
        <div class="footer ui-widget-header navbar-fixed-bottom">
            @include('shared.footer')
        </div>
        {{ HTML::script('shared/jquery/jquery-2.0.2.min.js') }}
        {{-- Bootstrap goes first to prevent jQuery UI library collapses. --}}
        {{ HTML::script('shared/bootstrap/js/bootstrap.min.js') }}
        {{ HTML::script('shared/jqueryui/js/jquery-ui-1.10.3.custom.min.js') }}

        <?php
        $shared_languages = array(
            'confirmation' => array(
                'delete_one' => Lang::get('shared.script.confirmation.delete_one'),
                'delete_all' => Lang::get('shared.script.confirmation.delete_all'),
            ),
        );
        ?>
        <script type="text/javascript">
            if (typeof jQuery === 'function')
            {
                (function($) {
                    language = $.parseJSON('<?php echo addslashes(json_encode($shared_languages)); ?>');
                })(jQuery);
            }
        </script>

        {{ HTML::script('shared/js/app.js') }}
    </body>
</html>