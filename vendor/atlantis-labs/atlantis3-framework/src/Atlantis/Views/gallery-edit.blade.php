@extends('atlantis-admin::admin-shell')

@section('title')
Edit Gallery | A3 Administration | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
@parent
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/jquery-sortble/jquery-sortable.js') !!} 

{!! Html::script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js') !!} 
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/plupload.full.min.js') !!}
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/jquery.ui.plupload.js') !!}
@stop

@section('styles')
@parent
{!! Html::style('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/css/jquery.ui.plupload.css') !!}
{{-- Add styles per template --}}
@stop

@section('content')
@if (isset($invalid_item))
<div class="callout alert">
  <h5>{{ $invalid_item }}</h5>
</div>
@else
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">Edit Gallery</h1>
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
    {!! Form::open(array('url' => '/admin/media/gallery-edit/' . $gallery->id, 'data-abide' => '', 'novalidate'=> '')) !!}
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
            <a href="#panel1" aria-selected="true">{{ $gallery->name }}</a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            <div class="row">
              <div class="columns">

              </div>
              <div class="columns large-7">

                <ul class="tabs" data-tabs id="example-tabs4">
                  <li class="tabs-title is-active">
                    <a href="#panela" aria-selected="true">Choose from existing media</a>
                  </li>
                  <li class="tabs-title">
                    <a href="#panelb">or upload new images</a>
                  </li>
                </ul>
                <div class="tabs-content" data-tabs-content="example-tabs">
                  <div class="tabs-panel is-active" id="panela">
                    {!! DataTable::set(\Atlantis\Controllers\Admin\MediaAddEditDataTable::class) !!}
                  </div>
                  <div class="tabs-panel" id="panelb">


                    <div id="uploader">
                      Uploader
                    </div>
                    <div class="row">
                      <div class="columns large-8">

                        {!! Form::select('resize', $aResize, NULL, ['id' => 'resize']) !!} 

                      </div>
                      <div class="columns large-4">

                        <a href="#" class="button alert float-right" onclick="$('#uploader').plupload('start')">Upload</a>    
                      </div>
                    </div>
                  </div>
                </div>


              </div>

              <div class="columns large-5">
                <div class="row">
                  <div class="columns large-12 ">                    
                   @if ($errors->get('name'))
                   <label for="name" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('name')[0] }}</span>
                    {!! Form::input('text', 'name', old('name', $gallery->name), ['class' => 'is-invalid-input', 'id'=>'name']) !!}
                  </label>
                  @else
                  <label for="name">Name <span class="form-error">is required.</span>
                    {!! Form::input('text', 'name', old('name', $gallery->name), ['required'=>'required', 'id'=>'name']) !!}
                  </label>
                  @endif
                </div> 

                <div class="columns large-12 ">
                  <label for="description">Description
                    {!! Form::textarea('description', old('description', $gallery->description), ['rows' => 4, 'cols' => '30', 'id' => 'description']) !!}
                  </label> 
                </div>
                <div class="columns large-12 ">
                  <label for="description">Gallery Images
                    <span class="icon icon-Help top" data-tooltip title="First Image in Gallery will be used as Featured Image"></span>
                  </label>
                  <div class="callout gal-container" id="gal-container">
                    @foreach ($aImages as $im)
                    <span class="item">
                      <img data-id="{!! $im['id'] !!}" src="{!! $im['src'] !!}">
                      <a class="rmv-btn" data-remove="{!! $im['id'] !!}"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>
                      <a class="edit-btn" title="edit" target="_blank" href="/admin/media/media-edit/{!! $im['id'] !!}"><i class="fa fa fa-pencil" aria-hidden="true"></i></a>
                      <input type="hidden" name="imgs[]" value="{!! $im['id'] !!}">
                    </span>
                    @endforeach
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
@endif
@stop

@section('js')
@parent
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function(event) {
    $("#gal-container").sortable({
      containerSelector: 'div',
      itemSelector : 'span',
      tolerance : -10,
      placeholder: 'placeholder item' //<img src="http://placehold.it/150x150">
    });


    $(function () {
         $("#uploader").plupload({
        runtimes: 'html5,flash,silverlight,html4',
        url: "/admin/media/add-to-gallery",
        headers: {
          "x-csrf-token": "{{ csrf_token() }}"
        },
        // Maximum file size
        max_file_size: "{!! intval(config('atlantis.allowed_max_filesize')) !!}mb",
        chunk_size: '1mb',
        // Specify what files to browse for
        filters: [
        {title: "Image files", extensions: "{{ implode(',', config('atlantis.allowed_image_extensions')) }}"},
        {title: "Zip files", extensions: "{{ implode(',', config('atlantis.allowed_others_extensions')) }}"}
        ],
        // Rename files by clicking on their titles
        rename: true,
        // Sort files
        sortable: true,
        // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
        dragdrop: true,
        // Views to activate
        views: {
          list: false,
          thumbs: true, // Show thumbs
          active: 'thumbs'
        },
        buttons: {
          start :false,
          stop:false
        },multipart_params : {
          'gallery_id' : '{{$gallery->id}}',
        },

        BeforeUploadoad: function(up, file) {

        },
        preinit : { 
          UploadFile: function(up, file) {
            up.setOption('multipart_params', {
              'gallery_id' : '{{$gallery->id}}',
              'resize' : $('#resize[name="resize"]').val()
            });                
          },
          fileUploaded : function(up, file, response) {
            console.log(response);
            var obj = jQuery.parseJSON(response.response);
            //console.log(obj.success);
            if(obj.success){
              var id = obj.success.image_id;
              var src = obj.success.thumbnail
              var img = $('<img />', {
                'data-id': id,
                'src': src
              });
              var rmvBtn = '<a class="rmv-btn" data-remove="id"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>';
              var editBtn = '<a class="edit-btn" title="edit" target="_blank" href="/admin/media/media-edit/' + id + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
              var imgIds = '<input type="hidden" name="imgs[]" value="' + id + '">';

              item = img.wrap('<span class="item"></span>').parent();
              //img.css('opacity', 0);
              item.appendTo($('#gal-container'));
              $(rmvBtn).appendTo(item);
              $(editBtn).appendTo(item);
              $(imgIds).appendTo(item);
              //var flyingTo = $(galleryContainer).children().last();
              //var clonedThumb = $(this).closest('tr').find('img');
              //flyToElement(clonedThumb, flyingTo);
              /*setTimeout(function() {
                img.css('opacity', 1);
              }, 200);*/
            }
          }
        },

        // Flash settings
        flash_swf_url: '/plupload/js/Moxie.swf',
        // Silverlight settings
        silverlight_xap_url: '/plupload/js/Moxie.xap'
      });
    });
    $('#uploader').on('start', function (event, args) {
     // $('#uploader').setOption('multipart_params', $("#media-form").serializeObject());
   });

  });
</script>
@stop