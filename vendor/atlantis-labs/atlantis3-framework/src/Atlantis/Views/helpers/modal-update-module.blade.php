<div class="reveal" id="{!! $modal_id !!}" data-reveal>
  {!! Form::open(['url' => '/admin/modules/update']) !!}    
  <h1>Update {{ $module['name'] }}</h1>
  <label for="version">Select version
    {!! Form::select('version', $aVersions, NULL, ['id' => 'version']) !!}
  </label>
  {!! Form::input('hidden', 'current_version', $module['version'], []) !!}
  {!! Form::input('hidden', 'path', $module['path'], []) !!}
  {!! Form::input('hidden', 'namespace', $module['namespace'], []) !!}
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <input type="submit" name="_update" value="Update" id="update-btn" class="alert button">
  {!! Form::close() !!}
</div>