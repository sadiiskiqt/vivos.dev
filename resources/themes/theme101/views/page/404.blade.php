@extends('atlantis::' . config('atlantis.frontend_shell_view'))


@section('content')
@parent

@if (Session::has('data') && count(Session::get('data.results')) > 0)
<h1>Did you mean:</h1> 
@foreach (Session::get('data.results') as $res)
<p>
  <a target="blank" href="{!! $res['url'] !!}">{{ $res['name'] }}</a>
</p>
@endforeach
@else
<h1>Sorry, can't find this page</h1>
@endif

{!! $content !!}

@stop