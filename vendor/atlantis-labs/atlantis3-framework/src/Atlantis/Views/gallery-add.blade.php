@extends('atlantis-admin::admin-shell')

@section('title')
Add Gallery | A3 Administration | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
@parent
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/jquery-sortble/jquery-sortable.js') !!} 
@stop

@section('styles')
@parent
{{-- Add styles per template --}}
@stop

@section('content')
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">Add Gallery</h1>
        @if (isset($msgInfo))
        <div class="callout warning">
          <h5>{!! $msgInfo !!}</h5>
        </div>
        @endif
        @if (isset($msgSuccess))
        <div class="callout success">
          <h5>{!! $msgSuccess !!}</h5>
        </div>
        @endif
        @if (isset($msgError))
        <div class="callout alert">
          <h5>{!! $msgError !!}</h5>
        </div>
        @endif
      </div>
    </div>
  </section>
  <section class="editscreen">
    {!! Form::open(array('url' => '/admin/media/gallery-add','class' => 'myform', 'data-abide' => '', 'novalidate'=> '')) !!}
    <div class="row">
      <div class="columns">
        <div class="float-right">
          <div class="buttons">
          <a href="/admin/media" class="back button tiny top primary" title="Go to Media" data-tooltip>
              <span class=" back icon icon-Goto"></span>
            </a>
            {!! Form::input('submit', '_save_close', 'Save & Close', ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
            {!! Form::input('submit', '_update', 'Update', ['class' => 'alert button', 'id'=>'update-btn']) !!}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <!-- data-status: active, disabled or dev -->
            <a href="#panel1" aria-selected="true">New Gallery</a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">

            <div class="row">
              <div class="columns">

              </div>
              <div class="columns large-7">
                {!! DataTable::set(\Atlantis\Controllers\Admin\MediaAddEditDataTable::class) !!}
              </div>

              <div class="columns large-5">
                <div class="row">
                  <div class="columns large-12 ">                    
                   @if ($errors->get('name'))
                   <label for="name" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('name')[0] }}</span>
                    {!! Form::input('text', 'name', old('name'), ['class' => 'is-invalid-input', 'id'=>'name']) !!}
                  </label>
                  @else
                  <label for="name">Name <span class="form-error">is required.</span>
                    {!! Form::input('text', 'name', old('name'), ['required'=>'required', 'id'=>'name']) !!}
                  </label>
                  @endif              
                  <!--<label for="description">Gallery type
                    <select name="gallert_type" id="">
                      <option value="">Header slider</option>
                      <option value="">Carousel</option>
                      <option value="">MegaFolio Pro</option>
                      <option value="">Light box gallery</option>
                    </select>
                  </label>-->  
                </div>

                <div class="columns large-12 ">
                  <label for="description">Description
                    {!! Form::textarea('description', old('description'), ['rows' => 4, 'cols' => '30', 'id' => 'description']) !!}
                  </label> 
                </div>
                <div class="columns large-12 ">
                  <label for="description">Gallery Images
                    <span class="icon icon-Help top" data-tooltip title="First Image in Gallery will be used as Featured Image"></span>
                  </label>
                  <div class="callout gal-container" id="gal-container">
                    
                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>
      </div>
    </div>
  </div>
  {!! Form::close() !!}
</section>
</main>
<footer>
  {{-- @include('atlantis-admin::help-sections/gallery') --}}
  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
@stop

<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function(event) {
    $("#gal-container").sortable({
      containerSelector: 'div',
      itemSelector : 'span',
      tolerance : -10,
      placeholder: 'placeholder item' //<img src="http://placehold.it/150x150">
    });
  });
</script>