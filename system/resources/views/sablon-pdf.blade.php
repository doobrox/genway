<!DOCTYPE html>
<html>
    <head>
        <title>{!! $subiect !!}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            * {
                font-family: "DejaVu Sans", sans-serif;
            }
            html, body {
                font-size: 12px;
            }
        </style>

        <style type="text/css">
            {!! $styles !!}
        </style>
    </head>
    <body>
        {!! str_replace("&nbsp;", " ", $continut) !!}
        {!! $scripts !!}
    </body>
</html>
