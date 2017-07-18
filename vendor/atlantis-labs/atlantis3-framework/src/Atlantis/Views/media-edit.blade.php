@extends('atlantis-admin::admin-shell')

@section('title')
Edit Media | A3 Administration | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/tagsInput/jquery.tagsinput.min.js') !!}

{!! Html::script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js') !!}
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/plupload.full.min.js') !!}
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/jquery.ui.plupload.js') !!}
@stop

@section('styles')
@parent
{!! Html::style('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/css/jquery.ui.plupload.css') !!}
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
        <h1 class="huge page-title">Edit Media</h1>
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
    {!! Form::open(['url' => 'admin/media/media-edit/' . $media->id, 'data-abide' => '', 'novalidate'=> '', 'id'=> 'media-form']) !!}
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
            <a href="#panel1" aria-selected="true">{!! !empty($media->filename) ? $media->filename : $media->original_filename !!}</a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">

            <div class="row">
              <div class="columns large-7">
                <div class="row">
                  <div class="columns medium-4">
                    <label for="filename">Name
                      {!! Form::input('text', 'filename', old('filename', $media->filename), ['id'=>'filename']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="tags">Tags
                      {!! Form::input('text', 'tags', old('tags', $tags), ['class' => 'inputtags', 'id' => 'tags']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="credit">Credit
                      {!! Form::input('text', 'credit', old('credit', $media->credit), ['id'=>'credit']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="alt">Alt
                      {!! Form::input('text', 'alt', old('alt', $media->alt), ['id'=>'alt']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="weight">Weight
                      {!! Form::input('number', 'weight', old('weight', $media->weight), ['min'=>'1']) !!}
                    </label>
                  </div>                  
                  <div class="columns medium-4">
                    <label for="css">CSS<span class="icon icon-Help top" data-tooltip title="For img src tag"></span>
                      {!! Form::input('text', 'css', old('css', $media->css), ['id'=>'css']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="anchor_link">Anchor Link<span class="icon icon-Help top" data-tooltip title="Wrap img src tag"></span>
                      {!! Form::input('text', 'anchor_link', old('anchor_link', $media->anchor_link), ['id'=>'anchor_link']) !!}
                    </label>
                    <label for="resize">Resize
                      {!! Form::select('resize', $aResize, $selected_resize, ['id' => 'resize']) !!} 
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="caption">Caption
                      {!! Form::textarea('caption', old('caption', $media->caption), ['rows' => 4, 'cols' => '30', 'id' => 'caption']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="description">Description
                      {!! Form::textarea('description', old('description', $media->description), ['rows' => 4, 'cols' => '30', 'id' => 'description']) !!}
                    </label>
                  </div>
                </div>
              </div>
              <div class="columns large-5">
                <div id="thumb">
                  <label for="description">File</label>
                  @if (empty($media->thumbnail))
                  <a href="{!! $filePath . $media->original_filename !!}">{{ $media->original_filename }}</a>
                  @else
                  <img src="{!! $filePath . $media->thumbnail !!}">
                  @endif
                  <br> <br><a data-toggle="uploader" class="button alert">Change File</a>
                </div>
                <div id="uploader" class="hidden" data-toggler=".hidden">
                  <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
                </div>
              </div>             
            </div>
            <div class="row">
              <div class="columns medium-4">
                <a target="blank" href="{!! Atlantis\Helpers\Tools::getFilePath() . $media->original_filename !!}">{{ config('atlantis.user_media_upload') . $media->original_filename }}</a>
              </div>
            </div>
            @if (!empty($media->tablet_name))
            <div class="row">
              <div class="columns medium-4">
                <a target="blank" href="{!! Atlantis\Helpers\Tools::getFilePath() . $media->tablet_name !!}">{{ config('atlantis.user_media_upload') . $media->tablet_name }}</a>
              </div>
            </div>
            @endif
            @if (!empty($media->phone_name))
            <div class="row">
              <div class="columns medium-4">
                <a target="blank" href="{!! Atlantis\Helpers\Tools::getFilePath() . $media->phone_name !!}">{{ config('atlantis.user_media_upload') . $media->phone_name }}</a>
              </div>
            </div>
            @endif
            @if (!empty($media->thumbnail))
            <div class="row">
              <div class="columns medium-4">
                <a target="blank" href="{!! Atlantis\Helpers\Tools::getFilePath() . $media->thumbnail !!}">{{ config('atlantis.user_media_upload') . $media->thumbnail }}</a>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    {!! Form::close() !!}
  </section>
</main>
<footer>

  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
@endif
@stop

@section('js')
@parent
@if (!isset($invalid_item))
<script type="text/javascript">
// Initialize the widget when the DOM is ready
document.addEventListener("DOMContentLoaded", function (event) {
  $('[data-toggle="uploader"]').click(function (ev) {
    $('#thumb').hide();
  });

  $.fn.serializeObject = function ()
  {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
      if (o[this.name] !== undefined) {
        if (!o[this.name].push) {
          o[this.name] = [o[this.name]];
        }
        o[this.name].push(this.value || '');
      } else {
        o[this.name] = this.value || '';
      }
    });
    return o;
  };

  $(function () {
       $("#uploader").plupload({
        // General settings
        runtimes: 'html5,flash,silverlight,html4',
        url: "/admin/media/media-edit/{{ $media->id }}",
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
          stop: false
        },
        multi_selection: false,
        prevent_duplicates: true,
        max_file_count: 1,
        multipart_params: $('#media-form').serializeObject(),
        preinit: {
          UploadFile: function (up, file) {
            up.setOption('multipart_params', $('#media-form').serializeObject());
          },
          FileUploaded :function (up, file, info) {
            var response = jQuery.parseJSON(info.response);
            
            if (response.success != null) {
              
            } else if (response.error != null) {
              var error = {
                code:-601,
                message: response.error,
                file:file,
                response:response,
                status:response.status,
                responseHeaders:response.responseHeaders
              }
            up.trigger('Error', error);
              
              
            }
          },
        },
        init: {
          Error: function (up, err) {
           console.log(err);
         }
       },
        // Flash settings
        flash_swf_url: '/plupload/js/Moxie.swf',
        // Silverlight settings
        silverlight_xap_url: '/plupload/js/Moxie.xap'
      });
  });
  $('#uploader').on('start', function (event, args) {
    console.log($('#uploader').getOption(['multipart_params']));
    $('#uploader').setOption('multipart_params', $("#media-form").serializeObject());
  });

});
</script>
@endif
@stop