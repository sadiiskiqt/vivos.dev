<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      @section('title')
      @show
    </title>
    
    @section('styles')
    {!! Html::style('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/css/normalize.css') !!}
    {!! Html::style('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/css/app.css') !!}
    @foreach($_styles as $style) 
    {!! $style !!}
    @endforeach
    @show
  </head>
  <body class="dashboard">
    {!! MenuNavigation::set() !!}
    @yield('content')
    
    <div class="row column text-center">
        <a href="http://www.atlantis-cms.com/" target="_blank">
            <img class="a3-logo" width="96" src="/vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/images/logo-atlcms.png" alt="atlantis cms logo">
        </a>
    </div>
    @section('scripts')
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/vendor/jquery.min.js') !!}
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/foundation.min.js') !!}    
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/app.js') !!}
    @foreach( $_scripts as $script )   
    {!! $script !!}
    @endforeach 
    @show
    
    @section('js')    
    @foreach( $_js as $js )
    {!! $js !!}
    @endforeach     
    @show
    
  </body>
</html>