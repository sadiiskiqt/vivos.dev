<div class="reveal" id="{!! $modal_id !!}" data-reveal>
  {!! Form::open(['url' => '/admin/modules/download-install']) !!}    
  <h1>{{ $module['name'] }}</h1>  
  <p>{{ $module['description'] }}</p>
  {!! Form::input('hidden', 'path', old('path', $module['path']), []) !!}
  {!! Form::input('hidden', 'namespace', old('namespace', $module['namespace']), []) !!}
  {!! Form::input('hidden', 'version', old('version', $module['version']), []) !!}
  
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <input type="submit" name="_download_install" value="Download and Install" id="update-btn" class="alert button">
  {!! Form::close() !!}
</div>