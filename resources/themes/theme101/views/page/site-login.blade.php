@extends('atlantis::' . config('atlantis.frontend_shell_view'))

@section('headTags')
@parent
{{-- Add custom tags in <head> per template --}}
{{-- <meta name="test"> --}}
@stop

@section('tracking_header')
@parent
{{-- Add tracking header per template --}}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
{{-- <script src="http://a3.angel.dev.gentecsys.net/media/js/vendor/jquery.js"></script> --}}
@stop

@section('styles')
@parent
{{-- Add styles per template --}}
@stop

@section('js')
@parent
{{-- Add js per template --}}
{{--  <script>
  $(document).ready(function () { ... --}}
@stop

@section('content')
@parent
<h1>Page Protected Login</h1>

{!! $content !!}

@if( $errors->all() ) 
  @foreach($errors->all() as $error)
    <div class="alert alert-danger">
      {{ $error }}
    </div>
  @endforeach
@endif


<div id='loginForm'>
  {!! Form::open(["url" => config('page-protected.route_login')]) !!} 
    {!! Form::input("text", "username", '' , array("id" => "username")) !!}
      <br />
      {!! Form::input("password", "password", '' , array("id" => "password")) !!}
      {!! Form::submit('Login') !!}
  {!! Form::close() !!}
</div>  
@stop