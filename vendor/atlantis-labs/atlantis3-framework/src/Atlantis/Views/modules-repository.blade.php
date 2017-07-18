@extends('atlantis-admin::admin-shell')

@section('title')
Modules Repository | A3 Administration | {{ config('atlantis.site_name') }}
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
        <h1 class="huge page-title">Modules Repository</h1>
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
  <section class="pages-list editscreen">
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <a href="#panel1" aria-selected="true">
              Modules
            </a>
          </li>
          <!-- <li class="tabs-title main">
            <a href="#panel2">
              Recently Used
            </a>
          </li> -->
          <li class="float-right list-filter">

            <a href="/admin/modules" class="back button tiny top primary" title="Go to Modules" data-tooltip>
              <span class=" back icon icon-Goto"></span>
            </a>

            <a data-open="configBlog" class="alert button">Add Key</a>
          </li> 
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            {!! DataTable::set(\Atlantis\Controllers\Admin\ModulesRepositoryDataTable::class, [], 'data-table-script-mod-repo') !!}
          </div>
          <!--<div class="tabs-panel" id="panel2">
          </div> -->
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

  <div class="reveal" id="configBlog" data-reveal>
    {!! Form::open(['url' => 'admin/modules/update-keys']) !!}    
    <h1>Keys</h1>


    <label for="modules_keys">Keys (One per line)
      {!! Form::textarea('modules_keys', old('modules_keys', !is_null(config('atlantis.modules_keys')) ? implode("\n", config('atlantis.modules_keys')) : ''), ['id'=>'modules_keys']) !!}
    </label>


    <button class="close-button" data-close aria-label="Close modal" type="button">
      <span aria-hidden="true">&times;</span>
    </button>
    <input type="submit" name="_update_config" value="Update" id="update-btn" class="success button">
    {!! Form::close() !!}
  </div>

</footer>
@stop