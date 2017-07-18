<h3>Search Results for "{{ $search_string }}"</h3>
@foreach ($results as $url => $name)
<p>
  {!! Html::link($url, $name, array('id' => 'linkid')) !!}
</p>
@endforeach