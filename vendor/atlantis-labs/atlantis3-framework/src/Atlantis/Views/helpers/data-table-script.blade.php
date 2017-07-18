<script>
  $(document).ready(function () {
    var atlTable{{ $table_id }} = $('#{{ $table_id }}').DataTable({
      language: {
        "decimal": "",
        "emptyTable": "No data available in table",
        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
        "infoEmpty": "Showing 0 to 0 of 0 entries",
        "infoFiltered": "(filtered from _MAX_ total entries)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Show _MENU_ entries",
        "loadingRecords": "Loading...",
        "processing": "Processing...",
        "search": "Search:",
        "zeroRecords": "No matching records found",
        "paginate": {
          "first": "First",
          "last": "Last",
          "next": "Next",
          "previous": "Previous"
        },
        "aria": {
          "sortAscending": ": activate to sort column ascending",
          "sortDescending": ": activate to sort column descending"
        }
      },
      dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
      pageLength: {!! $admin_items_per_page !!},
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ $url }}",
        "type": "POST",
        "data": {
          "_token" : "{{ csrf_token() }}",
          "namespaceClass": "{{ $namespaceClass }}",
          @foreach($postParams as $post_key => $post_value)
          "{{ $post_key }}": "{{ $post_value }}",
          @endforeach
        },
        //success: function() {
          //atlantisUtilities.init("atlCheckbox");
        //}
      },
      columns: [
      @foreach($columns as $column)
      {"data": "{{ $column['key'] }}"},
      @endforeach
      ],
      columnDefs: [
      @foreach($columns as $k => $column)
      {className: "{{ $column['class-td'] }}", "targets": [{{ $k }}]},
      @endforeach
      { targets: 'no-sort', orderable: false }
      ],
      autoWidth: false,
      searching: true,
      info: false,
      order: [
      @foreach($columns as $k => $column)
      @if ($column['order']['sorting'] == TRUE)
      [{{ $k }}, "{{ strtolower($column['order']['order']) }}"]
      @endif
      @endforeach
      ]
    });

atlTable{{ $table_id }}.on( 'draw.dt', function () {
  atlantisUtilities.init('atlCheckbox');
  $('.dataTable').foundation();
});

$('.search-in-table[data-table-id="{{ $table_id }}"]').on('keyup', function (ev) {
  atlTable{{ $table_id }}.search($(this).val()).draw();
});

$('.show-count[data-table-id="{{ $table_id }}"]').on( 'change', function() {
  atlTable{{ $table_id }}.page.len($(this).val()).draw();
});

});
</script>