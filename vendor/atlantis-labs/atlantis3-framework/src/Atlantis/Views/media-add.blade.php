@extends('atlantis-admin::admin-shell')

@section('title')
Add Media | A3 Administration | {{ config('atlantis.site_name') }}
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
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">Add Media</h1>
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
    {!! Form::open(['url' => 'admin/media/media-add', 'data-abide' => '', 'novalidate'=> '', 'id'=> 'media-form']) !!}
    <div class="row">
      <div class="columns">
        <div class="float-right">
          <div class="buttons">
            <a href="/admin/media" class="back button tiny top primary" title="Go to Media" data-tooltip>
              <span class=" back icon icon-Goto"></span>
            </a>
            <a href="#" class="button alert" onclick="$('#uploader').plupload('start')">Upload</a>
            {{-- Form::input('submit', '_save_close', 'Save & Close', ['class' => 'alert button', 'id'=>'save-close-btn']) --}}
            {{-- Form::input('submit', '_update', 'Update', ['class' => 'alert button', 'id'=>'update-btn']) --}}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <!-- data-status: active, disabled or dev -->
            <a href="#panel1" aria-selected="true">New Media</a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">

            <div class="row">
            <div class="columns large-7">
                <div class="row">
                  <div class="columns medium-4">
                    <label for="filename">Name
                      {!! Form::input('text', 'filename', old('filename'), ['id'=>'filename']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="tags">Tags
                      {!! Form::input('text', 'tags', old('tags'), ['class' => 'inputtags', 'id' => 'tags']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="credit">Credit
                      {!! Form::input('text', 'credit', old('credit'), ['id'=>'credit']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="alt">Alt
                      {!! Form::input('text', 'alt', old('alt'), ['id'=>'alt']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="weight">Weight
                      {!! Form::input('number', 'weight', old('weight', 1), ['min'=>'1']) !!}
                    </label>
                  </div>                  
                  <div class="columns medium-4">
                    <label for="css">CSS<span class="icon icon-Help top" data-tooltip title="For img src tag"></span>
                      {!! Form::input('text', 'css', old('css'), ['id'=>'css']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="anchor_link">Anchor Link<span class="icon icon-Help top" data-tooltip title="Wrap img src tag"></span>
                      {!! Form::input('text', 'anchor_link', old('anchor_link'), ['id'=>'anchor_link']) !!}
                    </label>
                    <label for="resize">Resize
                      {!! Form::select('resize', $aResize, NULL, ['id' => 'resize']) !!} 
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="caption">Caption
                      {!! Form::textarea('caption', old('caption'), ['rows' => 4, 'cols' => '30', 'id' => 'caption']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="description">Description
                      {!! Form::textarea('description', old('description'), ['rows' => 4, 'cols' => '30', 'id' => 'description']) !!}
                    </label>
                  </div>
                </div>
              </div>
              <div class="columns large-5">
                 <div id="uploader">
                          <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
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

  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
@stop

@section('js')
@parent
<script type="text/javascript">
// Initialize the widget when the DOM is ready
document.addEventListener("DOMContentLoaded", function (event) {
  $.fn.serializeObject = function()
  {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
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
        url: "/admin/media/media-add",
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
        },
        multipart_params : $('#media-form').serializeObject(),

        BeforeUpload: function(up, file) {

        },
        preinit : { 
          UploadFile: function(up, file) {
            up.setOption('multipart_params', $('#media-form').serializeObject());                
          }
        },

        // Flash settings
        flash_swf_url: '/plupload/js/Moxie.swf',
        // Silverlight settings
        silverlight_xap_url: '/plupload/js/Moxie.xap'
      });
});
$('#uploader').on('start', function (event, args) {
  $('#uploader').setOption('multipart_params', $("#media-form").serializeObject());
});
$('#uploader').on('complete', function (event, args) {
  window.location.href="/admin/media";
});

});
</script>
@stop