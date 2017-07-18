<div class="reveal" id="{!! $modal_id !!}" data-reveal>
  {!! Form::open(['url' => $formUrl]) !!}    
  <h1>Clone Page</h1>
  <label for="clone_name">Clone Name
  {!! Form::input('text', 'clone_name', old('clone_name', $clone_name), ['id'=>'clone_name']) !!}
  </label>
  <label for="clone_name">Clone Url
  {!! Form::input('text', 'clone_url', old('clone_url', $clone_url), ['id'=>'clone_name']) !!}
  </label>
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <input type="submit" name="_clone" value="Clone" id="update-btn" class="alert button">
  {!! Form::close() !!}
</div>