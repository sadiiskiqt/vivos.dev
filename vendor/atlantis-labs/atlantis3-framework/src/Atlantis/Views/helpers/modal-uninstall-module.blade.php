<div class="reveal" id="{!! $modal_id !!}" data-reveal>
  {!! Form::open(['url' => 'admin/modules/uninstall/' . $module_id]) !!}    
  <h1>Uninstall Module</h1>
  <p class="lead">Are you sure you want to uninstall {{ $module_name }}</p>
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <input type="submit" name="_uninstall_module" value="Uninstall" id="update-btn" class="alert button">
  {!! Form::close() !!}
</div>