<script>
  // instance, using default configuration.
  CKEDITOR.replace('{{ $attributes["id"] }}', {
    'allowedContent': true,
    'enterMode': CKEDITOR.ENTER_BR,
    'autoGrow_minHeight': '{{ $height }}',
    'height': '{{ $height }}'

  });

  //CKEDITOR.on('instanceReady', function (ev) {
    // Ends self-closing tags the HTML4 way, like <br>.
    //ev.editor.dataProcessor.writer.selfClosingEnd = '>';
  //});

</script>