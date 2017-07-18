<div class="list-filter">
  @if (!empty($aBulkActions))
  {!! Form::open(array('url' => $bulk_action_url, 'class' => 'bulk-action form_bulk_action', 'data-table-id' => $table_id)) !!}
  
    <select name="action" class="bulk">
      @foreach($aBulkActions as $action)
      <option value="{{ $action['key'] }}">{{ $action['name'] }}</option>
      @endforeach
    </select>
    <input type="hidden" name="bulk_action_ids">
    <input type="submit" value="Apply" class="button alert apply disabled form_bulk_button">
  {!! Form::close() !!}
  @endif

  {!! Form::select(NULL, $lengthMenu, $admin_items_per_page, ['class' => 'show-count', 'data-table-id' => $table_id]) !!} 

  <div class="search icon icon-Search">
    <input type="text" class="search-in-table" data-table-id="{{$table_id}}">
  </div>
</div>
<table class="{{ $tableClass }}" id="{{ $table_id }}">
  <thead>
    <tr>
      @foreach($columns as $column)
      <th class="{!! $column['class-th'] !!}">{!! $column['title'] !!}</th>
      @endforeach
    </tr>
  </thead>       
</table>