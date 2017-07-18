<div class="reveal" id="{!! $modal_id !!}" data-reveal>   
  <h1>Invalidate Files</h1>

  <label for="mask">Path to Files (one per line) <span class="form-error">is required.</span>
    {!! Form::textarea('inv_files', old('inv_files', $inv_files), ['rows' => 5, 'cols' => '30', 'id' => '']) !!}
  </label>

  <a id="a-inv-files" class="alert button">Invalidate</a>
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <i style="visibility:hidden" class="fa fa-refresh fa-spin fa-2x fa-fw  alert proccessing"></i>
  <span style="visibility:hidden" class=" proccessing">Please, wait while directories are saving...</span>

  <i style="visibility:hidden" class="fa fa-check fa-2x success done-inv-files" aria-hidden="true"></i>
  <span style="visibility:hidden" class=" done-inv-files">Done!</span>

  <i style="visibility:hidden" class="fa fa-exclamation fa-2x alert error-inv-files" aria-hidden="true"></i>
  <span style="visibility:hidden" class=" error-inv-files">Error!</span>

</div>

<script type="text/javascript">

  document.addEventListener("DOMContentLoaded", function (event) {
    $('#a-inv-files').click(function (ev) {
      ev.preventDefault();
      $('.proccessing').show().css('visibility', 'visible');
      $('.done-inv-files').hide();
      $('.error-inv-files').hide();

      $.post('/admin/config/invalidate-files', {
        _token: '{{ csrf_token() }}',
        files: $('textarea[name="inv_files"]').val()
      }).done(function (data) {
        $('.proccessing').hide();
        if (data.error != null) {
          $('span[class=" error-inv-files"]').text('');
          for (i = 0; i < data.error.length; i++) {
            $('span[class=" error-inv-files"]').append(data.error[i] + '<br>');
          }          
          $('span[class=" error-inv-files"]').text(data.success);
          $('.error-inv-files').show().css('visibility', 'visible');
        } else {
          $('.done-inv-files').show().css('visibility', 'visible');
        }
      });
    })
  });
</script>	