@extends('atlantis-admin::admin-shell')

@section('title')
Modules | A3 Administration | {{ config('atlantis.site_name') }}
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
        <h1 class="huge page-title">Modules</h1>
      </div>
    </div>
  </section>
  <section class="modules-list">
    <div class="row">
      <div class="columns small-12">
        @if ($needUpdate)
        <div class="warning callout">
          <h5>Some of installed modules needs to be updated to work properly</h5>
          <a href="/admin/modules/update-modules-setup/">Update</a>
        </div>
        @endif
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
              Installed Modules ({{ $count_installed }})
            </a>
          </li>
          
          @if ($canEditModules)
          <li class="tabs-title"><a href="#panel2">Available Modules ({{ $count_notinstalled }})</a></li>

          <li class="float-left list-filter">
            <a id="save-close-btn" class="alert button" href="/admin/modules/repository">Repository</a>
          </li> 
          @endif
          
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            <div class="row">
              <div class="columns large-4">
                @foreach ($aModules[1] as $module)
                <div id="module-{!! $module['id'] !!}" class="module columns has-submenu" data-toggler=".expanded">

                  <div class="title {{ $module['active'] == 1 ? 'active' : 'disabled'}}">
                    <h3>

                      @if (($module['adminURL'] != NULL || !empty($module['adminURL'])) && $module['active'] == 1)
                      <a href="/{!! $module['adminURL'] !!}"><span class="{!! $module['icon'] !!}"></span>{{ $module['name'] }}</a>
                      @else
                      <a><span class="{!! $module['icon'] !!}"></span>{{ $module['name'] }}</a>
                      @endif  

                      @if ($module['active'] == 0)
                        <div class="label alert">not active</div>
                      @endif
                      <div class="label">version {{ $module['version'] }}</div>
                      @if (isset($module['api']) && isset($module['api']['new_version']))
                      <div class="label warning">new version {{ $module['api']['new_version'] }}</div>
                      @endif
                      @if (isset($module['protected']) && $module['protected'])
                      <div class="label success">protected</div>
                      @endif
                    </h3>
                    <div class="actions">
                      @if ($canEditModules)

                      @if (isset($module['api']))
                      <a data-open="updatelModule{!! $module['id'] !!}" class="top" data-tooltip title="Update {{ $module['name'] }}"><span class="icon icon-DownloadCloud"></span></a>
                      @endif

                      @if ($module['active'] == 1)
                      <a href="/admin/modules/deactivate-module/{!! $module['id'] !!}" class="top" data-tooltip title="Deactivate {{ $module['name'] }}"><span class="icon icon-Pause"></span></a>
                      @else
                      <a href="/admin/modules/activate-module/{!! $module['id'] !!}" class="top" data-tooltip title="Activate {{ $module['name'] }}"><span class="icon icon-Play"></span></a>
                      @endif
                      <a data-open="unistallModule{!! $module['id'] !!}" class="top" data-tooltip title="Uninstall {{ $module['name'] }}"><span class="icon icon-Delete"></span></a>
                      @endif
                      <a class="expander" data-toggle="module-{!! $module['id'] !!}"></a>
                    </div>
                  </div>
                  <div class="submenu">{{ $module['description'] }}</div>
                </div>
                @endforeach
              </div>
              <div class="columns large-4">
                @foreach ($aModules[2] as $module)
                <div id="module-{!! $module['id'] !!}" class="module columns has-submenu" data-toggler=".expanded">
                  <div class="title {{ $module['active'] == 1 ? 'active' : 'disabled'}}">
                    <h3>
                      @if (($module['adminURL'] != NULL || !empty($module['adminURL'])) && $module['active'] == 1)
                      <a href="/{!! $module['adminURL'] !!}"><span class="{!! $module['icon'] !!}"></span>{{ $module['name'] }}</a>
                      @else
                      <a href="#"><span class="{!! $module['icon'] !!}"></span>{{ $module['name'] }}</a>
                      @endif  
                      @if ($module['active'] == 0)
                        <div class="label alert">not active</div>
                      @endif
                      <div class="label">version {{ $module['version'] }}</div>
                      @if (isset($module['api']) && isset($module['api']['new_version']))
                      <div class="label warning">new version {{ $module['api']['new_version'] }}</div>
                      @endif
                      @if (isset($module['protected']) && $module['protected'])
                      <div class="label success">protected</div>
                      @endif
                      
                    </h3>
                    <div class="actions">
                      @if ($canEditModules)

                      @if (isset($module['api']))
                      <a data-open="updatelModule{!! $module['id'] !!}" class="top" data-tooltip title="Update {{ $module['name'] }}"><span class="icon icon-DownloadCloud"></span></a>
                      @endif

                      @if ($module['active'] == 1)
                      <a href="/admin/modules/deactivate-module/{!! $module['id'] !!}" class="top" data-tooltip title="Deactivate {{ $module['name'] }}"><span class="icon icon-Pause"></span></a>
                      @else
                      <a href="/admin/modules/activate-module/{!! $module['id'] !!}" class="top" data-tooltip title="Activate {{ $module['name'] }}"><span class="icon icon-Play"></span></a>
                      @endif
                      <a data-open="unistallModule{!! $module['id'] !!}" class="top" data-tooltip title="Uninstall {{ $module['name'] }}"><span class="icon icon-Delete"></span></a>
                      @endif
                      <a class="expander" data-toggle="module-{!! $module['id'] !!}"></a>
                    </div>
                  </div>
                  <div class="submenu">{{ $module['description'] }}</div>
                </div>
                @endforeach
              </div>
              <div class="columns large-4">
                @foreach ($aModules[3] as $module)
                <div id="module-{!! $module['id'] !!}" class="module columns has-submenu" data-toggler=".expanded">
                  <div class="title {{ $module['active'] == 1 ? 'active' : 'disabled'}}">
                    <h3>
                      @if (($module['adminURL'] != NULL || !empty($module['adminURL'])) && $module['active'] == 1)
                      <a href="/{!! $module['adminURL'] !!}"><span class="{!! $module['icon'] !!}"></span>{{ $module['name'] }}</a>
                      @else
                      <a href="#"><span class="{!! $module['icon'] !!}"></span>{{ $module['name'] }}</a>
                      @endif
                      
                      @if ($module['active'] == 0)
                        <div class="label alert">not active</div>
                      @endif

                      <div class="label">version {{ $module['version'] }}</div>
                      @if (isset($module['api']) && isset($module['api']['new_version']))
                      <div class="label warning">new version {{ $module['api']['new_version'] }}</div>
                      @endif
                      @if (isset($module['protected']) && $module['protected'])
                      <div class="label success">protected</div>
                      @endif
                     
                    </h3>
                    <div class="actions">
                      @if ($canEditModules)

                      @if (isset($module['api']))
                      <a data-open="updatelModule{!! $module['id'] !!}" class="top" data-tooltip title="Update {{ $module['name'] }}"><span class="icon icon-DownloadCloud"></span></a>
                      @endif

                      @if ($module['active'] == 1)
                      <a href="/admin/modules/deactivate-module/{!! $module['id'] !!}" class="top" data-tooltip title="Deactivate {{ $module['name'] }}"><span class="icon icon-Pause"></span></a>
                      @else
                      <a href="/admin/modules/activate-module/{!! $module['id'] !!}" class="top" data-tooltip title="Activate {{ $module['name'] }}"><span class="icon icon-Play"></span></a>
                      @endif
                      <a data-open="unistallModule{!! $module['id'] !!}" class="top" data-tooltip title="Uninstall {{ $module['name'] }}"><span class="icon icon-Delete"></span></a>
                      @endif
                      <a class="expander" data-toggle="module-{!! $module['id'] !!}"></a>
                    </div>
                  </div>
                  <div class="submenu">{{ $module['description'] }}</div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="tabs-panel" id="panel2">
            <div class="row">
              @if (count($aNotInstalledModules[1]) == 0 && count($aNotInstalledModules[2]) == 0 && count($aNotInstalledModules[3]) == 0)
              <h5 class="text-center">There are no available modules for installation.</h5>
              @endif              
              <div class="columns large-4">
                @foreach ($aNotInstalledModules[1] as $module)
                <div id="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}" class="module columns has-submenu" data-toggler=".expanded">
                  <div class="title">
                    <h3>
                      <a href="#"><span class="{!! $module['icon'] !!}"></span>{{ $module['name'] }}</a>
                    </h3>
                    <div class="actions">
                      @if ($canEditModules)
                      <a data-open="installModule{!! str_replace('\\', '', $module['moduleNamespace']) !!}" data-tooltip title="Install {{ $module['name'] }}" class="top"><span class="icon icon-CD"></span></a>
                      @endif
                      <a class="expander" data-toggle="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}"></a>
                    </div>
                  </div>                 
                  <div class="submenu">{{ $module['description'] }}</div>
                </div>
                @endforeach
              </div>
              <div class="columns large-4">
                @foreach ($aNotInstalledModules[2] as $module)
                <div id="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}" class="module columns has-submenu" data-toggler=".expanded">
                  <div class="title">
                    <h3>
                      <a href="#"><span class="{!! $module['icon'] !!}"></span>{{ $module['name'] }}</a>
                    </h3>
                    <div class="actions">
                      @if ($canEditModules)
                      <a data-open="installModule{!! str_replace('\\', '', $module['moduleNamespace']) !!}" data-tooltip title="Install {{ $module['name'] }}" class="top"><span class="icon icon-CD"></span></a>
                      @endif
                      <a class="expander" data-toggle="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}"></a>
                    </div>
                  </div>                 
                  <div class="submenu">{{ $module['description'] }}</div>
                </div>
                @endforeach
              </div>
              <div class="columns large-4">
                @foreach ($aNotInstalledModules[3] as $module)
                <div id="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}" class="module columns has-submenu" data-toggler=".expanded">
                  <div class="title">
                    <h3>
                      <a href="#"><span class="{!! $module['icon'] !!}"></span>{{ $module['name'] }}</a>
                    </h3>
                    <div class="actions"> @if ($canEditModules)
                      <a data-open="installModule{!! str_replace('\\', '', $module['moduleNamespace']) !!}" data-tooltip title="Install {{ $module['name'] }}" class="top"><span class="icon icon-CD"></span></a>
                      @endif
                      <a class="expander" data-toggle="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}"></a>
                    </div>
                  </div>                 
                  <div class="submenu">{{ $module['description'] }}</div>
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
 {{-- @include('atlantis-admin::help-sections/modules') --}}
  <div class="row">
    <div class="columns">
    </div>
  </div>
  @foreach ($aModules[1] as $module)
  {!! \Atlantis\Helpers\Modal::uninstallModule('unistallModule' . $module['id'], $module['id'], $module['name']) !!}
  @if (isset($module['api']))
  {!! \Atlantis\Helpers\Modal::updateModuleModal('updatelModule' . $module['id'], $module) !!}
  @endif
  @endforeach

  @foreach ($aModules[2] as $module)
  {!! \Atlantis\Helpers\Modal::uninstallModule('unistallModule' . $module['id'], $module['id'], $module['name']) !!}
  @if (isset($module['api']))
  {!! \Atlantis\Helpers\Modal::updateModuleModal('updatelModule' . $module['id'], $module) !!}
  @endif
  @endforeach

  @foreach ($aModules[3] as $module)
  {!! \Atlantis\Helpers\Modal::uninstallModule('unistallModule' . $module['id'], $module['id'], $module['name']) !!}
  @if (isset($module['api']))
  {!! \Atlantis\Helpers\Modal::updateModuleModal('updatelModule' . $module['id'], $module) !!}
  @endif
  @endforeach

  @foreach ($aNotInstalledModules[1] as $module)
  {!! \Atlantis\Helpers\Modal::installModule('installModule' . str_replace('\\', '', $module['moduleNamespace']), $module) !!}
  @endforeach
  @foreach ($aNotInstalledModules[2] as $module)
  {!! \Atlantis\Helpers\Modal::installModule('installModule' . str_replace('\\', '', $module['moduleNamespace']), $module) !!}
  @endforeach
  @foreach ($aNotInstalledModules[3] as $module)
  {!! \Atlantis\Helpers\Modal::installModule('installModule' . str_replace('\\', '', $module['moduleNamespace']), $module) !!}
  @endforeach
</footer>
@stop