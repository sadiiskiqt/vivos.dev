<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title'){!! $_page->seo_title !!}</title>

    @section('headTags')
    @foreach($_headTags as $tag)
    {!! $tag !!}
    @endforeach
    @show

    @section('styles')
    <link media="all" type="text/css" rel="stylesheet" href="/resources/themes/theme101/assets/css/app.css">
    @foreach($_styles as $style)
    {!! $style !!}
    @endforeach
    @show
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="/resources/themes/theme101/assets/js/parallax.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function () {
    var scene = document.getElementById('scene');
    var parallax = new Parallax(scene, {invertX: false, invertY: true, limitX: false, scalarX: 2, scalarY: 8});
    });
    </script>

  </head>
  <body class="{!! $body_class !!}">

    {!! MenuNavigation::setShortcutBar() !!}

    @section('tracking_header')
    {!! $tracking_header !!}
    @show

    @if (isset($patt_header))
    {!! $patt_header !!}
    @endif

    @section('content')
    @show

    @if (isset($patt_footer))
    {!! $patt_footer !!}
    @endif

    @section('js')
    @foreach( $_js as $js )
    {!! $js !!}
    @endforeach
    @show

    @section('scripts')
    @foreach($_scripts as $script)
    {!! $script !!}
    @endforeach
    @show

  </body>
</html>
