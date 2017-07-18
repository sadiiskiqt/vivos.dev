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
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">Themes</h1>
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
            <!-- data-status: active, disabled or dev -->
            <a href="#panel1" aria-selected="true">
              Themes ({{ $count_installed }})
            </a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            <div class="row">
              <div class="columns large-3">
                @foreach ($aThemes[1] as $k => $theme)
                <div class="module columns">
                  <h3 class="title">
                    @if ($theme['active'])
                    <span class="float-right label small">Current theme</span>
                    @endif
                    <a href="/admin/themes/details/{!! str_slug($theme['config']['name']) !!}">{{ $theme['config']['name'] }}</a>
                  </h3>
                  @if (isset($theme['config']['screenshot']) && !empty($theme['config']['screenshot']))
                  <img src="/{!! $theme['path'] . '/' . $theme['config']['screenshot'] !!}" alt="">
                  @else
                  <div class="callout">
                    <h3 class="title text-center">
                      <a>
                        <small>No Screenshot</small><br>
                        <span class="icon icon-Picture"></span><br>
                        <small>Available</small>
                      </a>
                    </h3>
                  </div>
                  @endif

                  @if (!$theme['active'])
                  <a data-open="activateTheme{!! $k !!}" class="success button small">Activate</a>
                  @endif
                </div>
                @endforeach
              </div>
              <div class="columns large-3">
                @foreach ($aThemes[2] as $k => $theme)
                <div class="module columns">
                  <h3 class="title">
                    @if ($theme['active'])
                    <span class="float-right label small">Current theme</span>
                    @endif
                    <a href="/admin/themes/details/{!! str_slug($theme['config']['name']) !!}">{{ $theme['config']['name'] }}</a>
                  </h3>
                  @if (isset($theme['config']['screenshot']) && !empty($theme['config']['screenshot']))
                  <img src="/{!! $theme['path'] . '/' . $theme['config']['screenshot'] !!}" alt="">
                  @else
                  <div class="callout">
                    <h3 class="title text-center">
                      <a>
                        <small>No Screenshot</small><br>
                        <span class="icon icon-Picture"></span><br>
                        <small>Available</small>
                      </a>
                    </h3>
                  </div>
                  @endif

                  @if (!$theme['active'])
                  <a data-open="activateTheme{!! $k !!}" class="success button small">Activate</a>
                  @endif
                </div>
                @endforeach
              </div>
              <div class="columns large-3">
                @foreach ($aThemes[3] as $k => $theme)
                <div class="module columns">
                  <h3 class="title">
                    @if ($theme['active'])
                    <span class="float-right label small">Current theme</span>
                    @endif
                    <a href="/admin/themes/details/{!! str_slug($theme['config']['name']) !!}">{{ $theme['config']['name'] }}</a>
                  </h3>
                  @if (isset($theme['config']['screenshot']) && !empty($theme['config']['screenshot']))
                  <img src="/{!! $theme['path'] . '/' . $theme['config']['screenshot'] !!}" alt="">
                  @else
                  <div class="callout">
                    <h3 class="title text-center">
                      <a>
                        <small>No Screenshot</small><br>
                        <span class="icon icon-Picture"></span><br>
                        <small>Available</small>
                      </a>
                    </h3>
                  </div>
                  @endif

                  @if (!$theme['active'])
                  <a data-open="activateTheme{!! $k !!}" class="success button small">Activate</a>
                  @endif
                </div>
                @endforeach
              </div>
              <div class="columns large-3">
                @foreach ($aThemes[4] as $k => $theme)
                <div class="module columns">
                  <h3 class="title">
                    @if ($theme['active'])
                    <span class="float-right label small">Current theme</span>
                    @endif
                    <a href="/admin/themes/details/{!! str_slug($theme['config']['name']) !!}">{{ $theme['config']['name'] }}</a>
                  </h3>
                  @if (isset($theme['config']['screenshot']) && !empty($theme['config']['screenshot']))
                  <img src="/{!! $theme['path'] . '/' . $theme['config']['screenshot'] !!}" alt="">
                  @else
                  <div class="callout">
                    <h3 class="title text-center">
                      <a>
                        <small>No Screenshot</small><br>
                        <span class="icon icon-Picture"></span><br>
                        <small>Available</small>
                      </a>
                    </h3>
                  </div>
                  @endif

                  @if (!$theme['active'])
                  <a data-open="activateTheme{!! $k !!}" class="success button small">Activate</a>
                  @endif
                </div>
                @endforeach
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
  @foreach ($aThemes[1] as $k => $theme)
  @if (!$theme['active'])
  {!! \Atlantis\Helpers\Modal::activateTheme('activateTheme' . $k, $theme['path'], $theme['config']['name']) !!}
  @endif
  @endforeach

  @foreach ($aThemes[2] as $k => $theme)
  @if (!$theme['active'])
  {!! \Atlantis\Helpers\Modal::activateTheme('activateTheme' . $k, $theme['path'], $theme['config']['name']) !!}
  @endif
  @endforeach

  @foreach ($aThemes[3] as $k => $theme)
  @if (!$theme['active'])
  {!! \Atlantis\Helpers\Modal::activateTheme('activateTheme' . $k, $theme['path'], $theme['config']['name']) !!}
  @endif
  @endforeach

  @foreach ($aThemes[4] as $k => $theme)
  @if (!$theme['active'])
  {!! \Atlantis\Helpers\Modal::activateTheme('activateTheme' . $k, $theme['path'], $theme['config']['name']) !!}
  @endif
  @endforeach
</footer>
@stop