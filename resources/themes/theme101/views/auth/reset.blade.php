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

    @if(Session::has('status'))
    <div class="callout success">
      {{ Session::get('status') }}
    </div>
    @endif

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
  {!! Form::open(["url" => "admin/password/reset"]) !!} 
  {!! Form::input("hidden","token" ,$token , array()) !!}
  <div class="row">
    <div class="columns large-3 large-centered text-center">
      <div class="input-group">
        <span class="icon icon-Mail input-group-label"></span>
        {!! Form::input("email", "email",  old('email'), array("id" => "email","class"=>"input-group-field", "placeholder" => "Email")) !!}
      </div>
      <div class="input-group">
        <span class="icon icon-Key input-group-label"></span>
        {!! Form::input("password", "password", '' , array("id" => "password","class"=>"input-group-field", "placeholder" => "Password")) !!}
      </div>
      <div class="input-group">
        <span class="icon icon-Key input-group-label"></span>
        {!! Form::input("password", "password_confirmation", '' , array("id" => "password","class"=>"input-group-field", "placeholder" => "Confirm Password")) !!}
      </div>

      {!! Form::submit('Reset Password',['class'=>'alert button expanded']) !!}

    </div>
  </div>
  <br />
  {!! Form::close() !!}
</div>  
@stop