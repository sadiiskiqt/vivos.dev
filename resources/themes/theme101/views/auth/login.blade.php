@extends('atlantis::' . config('atlantis.frontend_shell_view'))


@section('styles')
@parent
{!! Html::style('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/css/app.css') !!}
@stop
@section('content')




<div class="row">
  <div class="columns large-3 large-centered text-center">
    <div style="height:120px;line-height:120px;"></div>
    <img src="/vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/images/atlantis_logo.png" alt="">
    <br><br>
    @if( $errors->all() ) 
    @foreach($errors->all() as $error)
    <div class="callout alert">
      {{ $error }}
    </div>
    @endforeach
    @endif
  </div>
</div>


<div id='loginForm'>
  {!! Form::open(["url" => "admin" . $urlQuery]) !!} 
  <div class="row">
    <div class="columns large-3 large-centered text-center">
      <div class="input-group">
        <span class="icon icon-User input-group-label"></span> 
        {!! Form::input("text", "username", '' , array("id" => "username","class"=>"input-group-field", "placeholder" => "Username")) !!}
      </div>
      <div class="input-group">
        <span class="icon icon-Key input-group-label"></span>
        {!! Form::input("password", "password", '' , array("id" => "password","class"=>"input-group-field", "placeholder" => "Password")) !!}
      </div>
      
      {!! Form::submit('Login',['class'=>'alert button expanded']) !!}
      <a href="admin/password/email">Lost your password?</a>

    </div>
  </div>
  <br />
  {!! Form::close() !!}
</div>  
@stop