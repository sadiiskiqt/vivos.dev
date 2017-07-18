@extends('atlantis-admin::admin-shell')

@section('title')
Themes | A3 Administration | {{ config('atlantis.site_name') }}
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
@if ($themeConfig == NULL)
<div class="callout alert">
  <h5>This theme is not valid</h5>
</div>
@else
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">{{ $themeConfig['name'] }}</h1>
      </div>
    </div>
  </section>
  <section class="modules-list">
    <div class="row">
      <div class="columns small-12">        
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
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <a href="#panel1" aria-selected="true">
              Details
            </a>
          </li>
          <li class="float-right list-filter">
          <a href="/admin/themes" class="button hollow">Themes list</a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            <div class="row">
              <div class="columns large-12">  

                @if (isset($themeConfig['name']))
                <h3>{{ $themeConfig['name'] }}</h3>
                @else
                <h5>'name'  not found in your theme's config file</h5>
                @endif

              </div>

              @if (isset($themeConfig['screenshot']) && !empty($themeConfig['screenshot']))
              <div class="columns large-4 ">
                <img src="/{!! $themeConfig['_theme_path'] . '/' . $themeConfig['screenshot'] !!}">                
              </div>
              @endif
              <div class="columns large-6 end">
                @if (isset($themeConfig['version']))
                <label for="">Version
                  <p>{{ $themeConfig['version'] }}</p>
                </label> 
                @endif
                
                @if (isset($themeConfig['author']))
                <label for="">Author
                  @if (isset($themeConfig['author_url']))
                  <p><a href="{{ $themeConfig['author_url'] }}" target="_blank">{{ $themeConfig['author'] }}</a></p>  
                  @else
                  <p>{{ $themeConfig['author'] }}</p>
                  @endif    
                </label>
                @endif
                @if (isset($themeConfig['description']))

                <label for="">Description
                  <p>{{ $themeConfig['description'] }}</p>
                </label>

                @endif

                @if (isset($themeConfig['pattern_outputs']))                  
                <label for="">Patterns variables
                  @foreach ($themeConfig['pattern_outputs'] as $var => $desc)
                  <p><span class="label secondary">{{ $var }}</span> : {{ $desc }}</p>
                  @endforeach                  
                </label>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<footer>
  <!--<div class="helper">
    <button type="button" class="icon icon-Bulb" data-panel-toggle="tips-panel"></button>
    <div class="right-panel side-panel" id="tips-panel" data-atlantis-panel>
      <ul class="accordion" data-accordion>
        <li class="accordion-item is-active" data-accordion-item>
          <a href="#" class="accordion-title">Tip 2</a>
          <div class="accordion-content" data-tab-content>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex possimus labore numquam assumenda et consectetur rem minima quis commodi nam atque corporis qui, exercitationem alias voluptatem magnam ad. Esse, ipsum.
          </div>
        </li>
        <li class="accordion-item" data-accordion-item>
          <a href="#" class="accordion-title">Tip 1</a>
          <div class="accordion-content" data-tab-content>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic, accusantium, laudantium? Veniam a officiis, consequatur. Voluptatibus, consectetur, nam temporibus in fugiat assumenda distinctio vitae modi architecto beatae provident voluptates magnam.
          </div>
        </li>
      </ul>
    </div>
  </div>-->
  <div class="row">
    <div class="columns">
    </div>
  </div> 
</footer>
@endif
@stop