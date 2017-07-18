@if (config('atlantis.show_shortcut_bar') && auth()->user() != NULL && auth()->user()->hasRole('admin-login'))
<div class="a3admin_admin-shortcut">

  <div class="a3admin_row">
    <div class="a3admin_columns">
      <div class="a3admin_top-bar" id="user-menu">
        <div class="a3admin_top-bar-left a3admin_user-menu">
          <div class="a3admin_account a3admin_left">
            <div class="a3admin_username">Welcome, {{ auth()->user()->name }}</div>
            <div class="a3admin_actions">
              <a href="/admin/users/edit/{{ auth()->user()->id }}">Settings</a> / <a href="/admin/logout">Logout</a>
            </div>
          </div>
          <h3 class="a3admin_menu-text a3admin_left">{{ config('atlantis.site_name') }}</h3>
        </div>
        <div id="main-nav" class="a3admin_top-bar-right">
          <button id="a3admin_nav-toggle" type="button" data-toggle="" class="">
            <span class="a3admin_toggle-pin"></span>
            <span class="a3admin_toggle-pin"></span>
            <span class="a3admin_toggle-pin"></span>
          </button>
          <ul class="a3admin_dropdown a3admin_menu" data-dropdown-menu>
            <li><a target="_blank" href="/admin/pages/edit/{!! $page->id !!}">Edit this Page</a></li>
            <li><a target="_blank" href="/admin/pages">Pages</a></li>
            <li><a target="_blank" href="/admin/patterns">Patterns</a></li>
            <li><a target="_blank" href="/admin/modules">Modules</a></li>
            <li><a target="_blank" href="/admin/media">Media</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>       
</div>
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function(event) {
    (function() {
      document.getElementById('a3admin_nav-toggle').onclick = function (ev) {
        if (this.classList.contains('active')) {

        this.classList.remove('active');
        document.getElementsByClassName('a3admin_dropdown')[0].classList.remove('active');
      }
      else {
          this.classList.add('active');
        document.getElementsByClassName('a3admin_dropdown')[0].classList.add('active');       
      }
    }
  })()
})
</script>

@endif