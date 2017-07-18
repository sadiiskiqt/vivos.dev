{!! Form::open(['url' => $url, 'method' => 'GET']) !!}
<div class="search-bar">
  {!! Form::input('search', 'search', old('search', $search_string), ['placeholder' => 'search']) !!}
  <button type="submit">Search</button>
</div>
{!! Form::close() !!}
