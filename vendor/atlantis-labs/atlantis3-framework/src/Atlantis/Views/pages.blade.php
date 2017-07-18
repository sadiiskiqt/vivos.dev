@extends('atlantis-admin::admin-shell')

@section('title')
Pages | A3 Administration | {{ config('atlantis.site_name') }}
@stop

@section('styles')
@parent
{{-- Add styles per template --}}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
{{-- <script src="http://a3.angel.dev.gentecsys.net/media/js/vendor/jquery.js"></script> --}}
@stop

@section('js')
@parent
{{-- Add js per template --}}
{{--  <script>
  $(document).ready(function () { ... --}}
@stop

@section('content')
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">        
        <h1 class="huge page-title">Pages</h1>
        @if (isset($msgInfo))
        <div class="callout warning">
          @foreach($msgInfo as $mInfo)
          <h5>{{ $mInfo }}</h5>
          @endforeach
        </div>
        @endif
        @if (isset($msgSuccess))
        <div class="callout success">
          @foreach($msgSuccess as $mSuccess)
          <h5>{{ $mSuccess }}</h5>
          @endforeach
        </div>
        @endif
        @if (isset($msgError))
        <div class="callout alert">
          @foreach($msgError as $mError)
          <h5>{{ $mError }}</h5>
          @endforeach
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
          <?php
          $cat_tab = ' ';
          $page_tab = ' ';
          if (\Session::get('tab_panel') != NULL) :

            if (\Session::get('tab_panel') == 'categories') :
              $cat_tab = ' is-active ';
            else :
              $page_tab = ' is-active ';
            endif;

          else :
            $page_tab = ' is-active ';
          endif;
          ?>
          <li class="tabs-title{{ $page_tab }}main">
            <a href="#panel1">
              All Pages
            </a>
          </li>
          <li class="tabs-title{{ $cat_tab }}main">
            <a href="#panel2">
              Categories
            </a>
          </li>
          <li class="float-right list-filter">
            <a id="save-close-btn" class="alert button" href="/admin/pages/add">New Page</a>
            <a id="save-close-btn" class="alert button" href="/admin/categories/add">New Category</a>
          </li>	
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel{{ $page_tab }}" id="panel1">
            {!! DataTable::set(\Atlantis\Controllers\Admin\PagesDataTable::class) !!}
          </div>
          <div class="tabs-panel{{ $cat_tab }}" id="panel2">
            {!! DataTable::set(\Atlantis\Controllers\Admin\CategoriesDataTable::class) !!}
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<footer>
  {{-- @include('atlantis-admin::help-sections/pages') --}}
  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
@stop