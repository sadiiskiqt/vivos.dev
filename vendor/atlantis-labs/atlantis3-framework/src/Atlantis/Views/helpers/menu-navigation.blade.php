<header>
  <div class="row">
    <div class="columns">
      <div class="top-bar" id="user-menu">
        <div class="top-bar-left user-menu">
          <div class="account">
            <span class="icon icon-User left"></span>
            <div class="username">{{ auth()->user()->name }}</div>
            <div class="actions">
              <a href="/admin/users/edit/{{ auth()->user()->id }}">Settings</a> / <a href="/admin/logout">Logout</a>
            </div>
          </div>
          <a href="/" target="_blank"><h3 class="menu-text left">{{ config('atlantis.site_name') }}</h3></a>
        </div>
        <span class="float-center ham-menu" data-responsive-toggle="main-nav" data-hide-for="large" style="display:none">
          <span class="menu-icon dark" data-toggle></span>
        </span>
        <div id="main-nav" class="top-bar-right">
          <ul class="dropdown menu" data-dropdown-menu>
            @foreach($aMenuItems as $item)
            @if (!$item['is_parent'])
            <li{!! $item['active'] !!}><a{!! !empty($item['tooltip']) ? ' data-tooltip title="' . $item['tooltip'] . '"' : '' !!}{!! !empty($item['class']) ? ' class="' . $item['class'] . '"' : '' !!} href="{{ $item['url'] }}">{{ $item['name'] }}</a></li>
            @else
            <li{!! $item['active'] !!}><a{!! !empty($item['tooltip']) ? ' data-tooltip title="' . $item['tooltip'] . '"' : '' !!}{!! !empty($item['parent-class']) ? ' class="' . $item['parent-class'] . '"' : '' !!} href="{{ $item['url'] }}">{{ $item['name'] }}</a>
              <ul class="menu vertical">
                @foreach($item['child_items'] as $child_item)
                <li{!! $child_item['active'] !!}><a{!! !empty($child_item['tooltip']) ? ' data-tooltip title="' . $child_item['tooltip'] . '"' : '' !!}{!! !empty($child_item['class']) ? ' class="' . $child_item['class'] . '"' : '' !!} href="{{ $child_item['url'] }}">{{ $child_item['name'] }}</a></li>
                @endforeach
              </ul>
            </li>
            @endif
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
</header>