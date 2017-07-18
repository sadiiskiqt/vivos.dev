@extends('atlantis-admin::admin-shell')

@section('title')
Media | A3 Administration | {{ config('atlantis.site_name') }}
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
        <h1 class="huge page-title">Media</h1>
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
          
          <?php
          $media_tab = ' ';
          $gall_tab = ' ';
          if (\Session::get('tab_panel') != NULL) :

            if (\Session::get('tab_panel') == 'gallery') :
              $gall_tab = ' is-active ';
            else :
              $media_tab = ' is-active ';
            endif;

          else :
            $media_tab = ' is-active ';
          endif;
          ?>
          
          <li class="tabs-title{{ $media_tab }}main">
            <a href="#panel1" aria-selected="true">
              Media
            </a>
          </li>
          <li class="tabs-title{{ $gall_tab }}main">
            <a href="#panel2">
              Galleries
            </a>
          </li>
          <li class="float-right list-filter">
            <a class="alert button" href="/admin/media/media-add">Add Media</a>
            <a class="alert button" href="/admin/media/gallery-add">Add Gallery</a>
          </li>	
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel{{ $media_tab }}" id="panel1">
            {!! DataTable::set(\Atlantis\Controllers\Admin\MediaDataTable::class) !!}
          </div>
          <div class="tabs-panel{{ $gall_tab }}" id="panel2">
            {!! DataTable::set(\Atlantis\Controllers\Admin\GalleriesDataTable::class) !!}
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
</footer>
@stop