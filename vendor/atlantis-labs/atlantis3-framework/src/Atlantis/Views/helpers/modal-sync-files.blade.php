<div class="reveal" id="{!! $modal_id !!}" data-reveal>   
  <h1>Sync Files</h1>
  {!! Form::select('sync_type', $sync_type, NULL, ['id' => 'select-sync-type']) !!}

  <label for="mask">Add Directories (one per line) <span class="form-error">is required.</span>
    {!! Form::textarea('sync_dirs', old('sync_dirs', $dirs), ['rows' => 5, 'cols' => '30', 'id' => 'textarea-sync-dirs']) !!}
  </label>

  <a id="a-sync" class="alert button">Sync Files</a>
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>

  <i style="visibility:hidden" class="fa fa-refresh fa-spin fa-2x fa-fw  alert proccessing-sync-files"></i>
  <span style="visibility:hidden" class=" proccessing-sync-files">Please, wait while directories are saving...</span>

  <i style="visibility:hidden" class="fa fa-check fa-2x success done-sync-files" aria-hidden="true"></i>
  <span style="visibility:hidden" class=" done-sync-files">Done!</span>

  <i style="visibility:hidden" class="fa fa-exclamation fa-2x alert error-sync-files" aria-hidden="true"></i>
  <span style="visibility:hidden" class=" error-sync-files">Error!</span>

</div>

<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function (event) {
    $('#a-sync').click(function (ev) {
      ev.preventDefault();
      $('.proccessing-sync-files').show().css('visibility', 'visible');
      $('.done-sync-files').hide();
      $('.error-sync-files').hide();
      disableSyncModal(true);

      syncing('{{ csrf_token() }}', $('select[name="sync_type"]').val(), $('textarea[name="sync_dirs"]').val(), null, null);

    })

    function syncing(token, sync_type, dirs, files, total) {

      $.post('/admin/config/sync-files-v2', {
        _token: token,
        sync_type: sync_type,
        dirs: dirs,
        files: JSON.stringify(files)
      }).done(function (data) {

        if (data.error != null) {
          $('.proccessing-sync-files').hide();
          $('span[class=" error-sync-files"]').text('');
          for (i = 0; i < data.error.length; i++) {
            $('span[class=" error-sync-files"]').append(data.error[i] + '<br>');
          }
          $('.error-sync-files').show().css('visibility', 'visible');
          disableSyncModal(false);
        } else if (data.files != null) {
        
          if (total == null) {
            total = data.files.length;
          }
          
          curent = data.files.length;
          if (curent == 0) {
            $('span[class=" proccessing-sync-files"]').text('Finalizing...');
          } else {
          $('span[class=" proccessing-sync-files"]').text('Syncing... (' + (total - curent) + '/' + total + ')');
        }
          
          console.log(data);
          return syncing(token, sync_type, dirs, data.files, total);
          
        } else if (data.success != null) {
          $('.proccessing-sync-files').hide();
          $('span[class=" done-sync-files"]').text(data.success);
          $('.done-sync-files').show().css('visibility', 'visible');
          disableSyncModal(false);
          console.log(data);
        } else {
          $('.proccessing-sync-files').hide();
          $('span[class=" error-sync-files"]').text('Oops, something went wrong!');          
          $('.error-sync-files').show().css('visibility', 'visible');
          disableSyncModal(false);
        }
      });

    }

    function disableSyncModal(disabled) {
      $('#textarea-sync-dirs').prop('disabled', disabled);
      $('#select-sync-type').prop('disabled', disabled);
      $('#a-sync').attr('disabled', disabled);
    }

  });
</script> 