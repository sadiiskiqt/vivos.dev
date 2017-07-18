<div class="reveal" id="{!! $modal_id !!}" data-reveal>
  {!! Form::open(['url' => 'admin/themes/activate-theme']) !!}    
  <h1>Activate Theme</h1>
  <p class="lead">Are you sure you want to activate {{ $theme_name }}</p>
  {!! Form::input('hidden', 'theme_path', $path, []) !!}
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <input type="submit" name="_activate_theme" value="Activate" id="update-btn" class="success button">
  {!! Form::close() !!}
</div>