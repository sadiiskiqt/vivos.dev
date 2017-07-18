@extends('atlantis-admin::admin-shell')

@section('title')
Trash | A3 Administration | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
{{-- <script src="http://a3.angel.dev.gentecsys.net/media/js/vendor/jquery.js"></script> --}}
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
        <h1 class="huge page-title">Trash</h1>
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
        <div class="float-right">
          <!-- <div class="buttons">
                  <a id="save-close-btn" class="alert button" href="#">New Page</a>
          </div> -->
        </div>
      </div>
    </div>
  </section>
  <section class="pages-list">
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <a href="#panel1" aria-selected="true">
              Pages
            </a>
          </li>
          <li class="tabs-title main">
            <a href="#panel2">
              Patterns
            </a>
          </li>
          <li class="float-right list-filter">

            <a data-open="emptyTrash" class="alert button">Empty Trash</a>
          </li>	
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            {!! DataTable::set(\Atlantis\Controllers\Admin\TrashPageDataTable::class) !!}
          </div>
          <div class="tabs-panel" id="panel2">
            {!! DataTable::set(\Atlantis\Controllers\Admin\TrashPattDataTable::class) !!}
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<footer>
  
  <div class="row">
    <div class="columns">
    </div>
  </div>
  {!! \Atlantis\Helpers\Modal::set('emptyTrash', 'Empty Trash', 'Are you sure you want to remove all items from trash?', 'Yes', '/admin/trash/empty') !!}
</footer>
@stop