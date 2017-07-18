<div class="reveal" id="{!! $modal_id !!}" data-reveal>
  {!! Form::open(['url' => 'admin/modules/install']) !!}    
  <h1>Install Module</h1>
  <p class="lead">Are you sure you want to install {{ $aModuleConfig['name'] }}</p>
  {!! Form::input('hidden', 'module_path', $aModuleConfig['path'], []) !!}
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <input type="submit" name="_install_module" value="Install" id="update-btn" class="success button">
  {!! Form::close() !!}
</div>