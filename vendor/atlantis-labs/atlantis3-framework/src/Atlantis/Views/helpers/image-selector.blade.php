
<div class="row gal-selector" id="gal-selector-{{$rand_id}}">	
  <div class="columns">
    @if ($multi_images)
    <a class="button" data-open="image-selector-modal">Add/Edit Files</a>
    @else
    <a class="button" data-open="image-selector-modal">Add/Edit Files</a>		
    @endif
    <div class="callout gal-container image-preview">
      @foreach ($images as $im)		
      @if (!empty($im['thumbnail']))
      <img data-id="{!! $im['id'] !!}" src="{!! $im['thumbnail'] !!}">			
      @elseif ($im !== FALSE)
      <span class="item">
        <em data-id="{!! $im['id'] !!}" class="icon icon-File application">
          <em class="name">
            <br>ID: {!! $im['id'] !!}<br>{!! $im['filename'] !!}
          </em>
        </em>
      </span>
      @endif
      @endforeach
    </div>
  </div>	


  <div class="reveal large" id="image-selector-modal" data-reveal>
    <button class="close-button" data-close aria-label="Close modal" type="button">
      <span aria-hidden="true">&times;</span>
    </button>

    <div class="columns">
      <h3>Select File</h3>
    </div>


    <div class="columns large-7" style="min-height: 80vh;">
      <ul class="tabs" data-tabs id="example-tabs4">
        <li class="tabs-title is-active">
          <a href="#panela" aria-selected="true">Choose from existing media</a>
        </li>
        <li class="tabs-title">
          <a href="#panelb">or upload new files</a>
        </li>
      </ul>
      <div class="tabs-content" data-tabs-content="example-tabs">
        <div class="tabs-panel is-active" id="panela">
          {!! DataTable::set(\Atlantis\Controllers\Admin\MediaWithFilesDataTable::class) !!}
        </div>
        <div class="tabs-panel" id="panelb">


          <div class="uploader">
            Uploader
          </div>
          <div class="row">
            <div class="columns large-8">

              {!! Form::select('resize', $aResize, $resize_option, ['id' => 'resize']) !!} 

            </div>
            <div class="columns large-4">
              <a class="button alert float-right" onclick="$('.uploader').plupload('start')">Upload</a>    
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="columns large-5">
      <label for="description">Files
        <span class="icon icon-Help top" data-tooltip title="First Image in Gallery will be used as Featured Image"></span>
      </label>

      @if ($multi_images)
      <div class="callout gal-container" id="gal-container">
        @if (count($images))
        @foreach ($images as $im)
        @if (!empty($im['thumbnail']))
        <span class="item">
          <img data-id="{!! $im['id'] !!}" src="{!! $im['thumbnail'] !!}">
          <a class="rmv-btn" title="remove" data-remove="{!! $im['id'] !!}"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>
          <a class="edit-btn" title="edit" target="_blank" href="/admin/media/media-edit/{!! $im['id'] !!}"><i class="fa fa fa-pencil" aria-hidden="true"></i></a>
          <input type="hidden" name="imgs[]" value="{!! $im['id'] !!}">
        </span>
        @elseif ($im !== FALSE)
        <span class="item">
          <em data-id="{!! $im['id'] !!}" class="icon icon-File application"><em class="name"><br>ID: {!! $im['id'] !!}<br>{!! $im['filename'] !!}</em></em>
          <a class="rmv-btn" title="remove" data-remove="{!! $im['id'] !!}"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>
          <a class="edit-btn" title="edit" target="_blank" href="/admin/media/media-edit/{!! $im['id'] !!}"><i class="fa fa fa-pencil" aria-hidden="true"></i></a>
          <input type="hidden" name="imgs[]" value="{!! $im['id'] !!}">
        </span>
        @endif
        @endforeach
        @endif

      </div>
      @else
      <div class="callout gal-container" id="gal-container" data-single-image>


        @if (count($images) !=0 )
        @foreach ($images as $im)
        @if (!empty($im['thumbnail']))
        <span class="item">
          <img data-id="{!! $im['id'] !!}" src="{!! $im['thumbnail'] !!}">
          <a class="rmv-btn" title="remove" data-remove="{!! $im['id'] !!}"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>
          <a class="edit-btn" title="edit" target="_blank" href="/admin/media/media-edit/{!! $im['id'] !!}"><i class="fa fa fa-pencil" aria-hidden="true"></i></a>
          <input type="hidden" name="imgs[]" value="{!! $im['id'] !!}">
        </span>
        @elseif ($im !== FALSE)
        <span class="item">
          <em data-id="{!! $im['id'] !!}" class="icon icon-File application"><em class="name"><br>ID: {!! $im['id'] !!}<br>{!! $im['filename'] !!}</em></em>
          <a class="rmv-btn" title="remove" data-remove="{!! $im['id'] !!}"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>
          <a class="edit-btn" title="edit" target="_blank" href="/admin/media/media-edit/{!! $im['id'] !!}"><i class="fa fa fa-pencil" aria-hidden="true"></i></a>
          <input type="hidden" name="imgs[]" value="{!! $im['id'] !!}">
        </span>
        @endif
        @endforeach
        @endif

      </div>
      @endif

      <div class="row">
        <div class="columns">
          <a class="button alert select-image-done">Done</a>
        </div>	
      </div>
    </div>		
  </div>
</div>
@section('scripts')
@parent
{!! Html::style('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/css/jquery.ui.plupload.css') !!}

{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/jquery-sortble/jquery-sortable.js') !!} 
{!! Html::script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js') !!} 
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/plupload.full.min.js') !!}
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/jquery.ui.plupload.js') !!}


@stop

@section('js')
@parent

<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function(event) {
  @if ($multi_images)
          var multi_selection = true;
  var max_file_count = 0;
  @else
          var multi_selection = false;
  var max_file_count = 1;
  @endif

          $("#gal-container").sortable({
          containerSelector: 'div',
          itemSelector : 'span.item',
          tolerance : - 10,
          placeholder: 'placeholder item'
  });
  $(document).on('click', '.select-image-done', function(ev) {
  var items = $(this).closest('.gal-selector').find('#gal-container .item').clone();
  $.each(items, function(i, item) {
  $(items[i]).find('[type="hidden"]').remove();
  $(item).wrap('<span class="item "></span>');
  });

  $(this).closest('.gal-selector').find('.image-preview').html(items).addClass('callout gal-container');
  $(this).closest('.gal-selector').find('.reveal').foundation('close');
  });
  $(function () {
     $(".uploader").plupload({
  runtimes: 'html5,flash,silverlight,html4',
          url: "/admin/media/media-add",
          headers: {
          "x-csrf-token": "{{ csrf_token() }}"
          },
          max_file_size: "{!! intval(config('atlantis.allowed_max_filesize')) !!}mb",
          chunk_size: '1mb',
          multi_selection: multi_selection,
          max_file_count: max_file_count,
          filters: [
          {title: "Image files", extensions: "{{ implode(',', config('atlantis.allowed_image_extensions')) }}"},
          {title: "Zip files", extensions: "{{ implode(',', config('atlantis.allowed_others_extensions')) }}"}
          ],
          rename: true,
          sortable: true,
          dragdrop: true,
          views: {
          list: false,
                  thumbs: true,
                  active: 'thumbs'
          },
          buttons: {
          start :false,
                  stop:false
          },
          multipart_params : {
          "filename" : "",
                  "tags" : "",
                   "credit" : "",
                   "alt" : "",
                   "weight" : "1",
                   "css" : "",
                   "anchor_link" : "",
                   "resize" : "",
                   "caption" : "",
                   "description" : "",
          },
          BeforeUpload: function(up, file) {

          },
          preinit : {
          UploadFile: function(up, file) {
          up.setOption('multipart_params', {
          'resize' : $('#resize[name="resize"]').val(),
                  'filename' : '',
                  'tags' : '',
                   'credit' : '',
                   'alt' : '',
                   'weight' : '1',
                   'css' : '',
                   'anchor_link' : '',
                   'resize' : '',
                   'caption' : '',
                   'description' : ''
          });
          },
                  fileUploaded : function(up, file, response) {
                  var obj = jQuery.parseJSON(response.response);
                  if (response.status == 200){
                  var id = obj.id;
                  var src = obj.thumbnail_path;
                  if (obj.thumbnail_path != "") {
                  var img = $('<img />', {
                  'data-id': id,
                          'src': src
                  });
                  } else {
                  var name = '<em class="name"><br>ID: ' + id + '<br>' + obj.target_name + '</em>';
                  var img = $('<span />', {
                  'data-id': id,
                          class: 'icon icon-File '
                  });
                  img = img.append(name);
                  }
                  var rmvBtn = '<a class="rmv-btn" title="remove" data-remove="'+id+'"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>';
                  var editBtn = '<a class="edit-btn" title="edit" target="_blank" href="/admin/media/media-edit/' + id + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                  var imgIds = '<input type="hidden" name="imgs[]" value="' + id + '">';
                  item = img.wrap('<span class="item"></span>').parent();
                  item.appendTo($('#gal-container'));
                  $(rmvBtn).appendTo(item);
                  $(editBtn).appendTo(item);
                  $(imgIds).appendTo(item);
                  }
                  }
          },
          flash_swf_url: '/plupload/js/Moxie.swf',
          silverlight_xap_url: '/plupload/js/Moxie.xap'
  });
  });
  $('#uploader').on('start', function (event, args) {

  });
  });
</script>

@stop
