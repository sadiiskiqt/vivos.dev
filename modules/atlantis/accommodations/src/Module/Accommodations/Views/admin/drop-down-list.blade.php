@extends('atlantis-admin::admin-shell')

  @section('content')
  
  <main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">        
        <h1 class="huge page-title">Drop Down List</h1>
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
                  <a id="save-close-btn" class="alert button" href="#">New</a>
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
              Drop Down List
            </a>
          </li>
          <!-- <li class="tabs-title main">
            <a href="#panel2">
              Recently Used
            </a>
          </li> -->
          <li class="float-right list-filter">
            <a href="/admin/modules/accommodations/index" class="back button tiny top primary"
               title="Go to Rooms list" data-tooltip>
              <span class=" back icon icon-Goto"></span>
            </a>
            <a id="save-close-btn" class="alert button hollow" href="/admin/modules/accommodations/add-room">Add Room</a>
            <a id="save-close-btn" class="alert button" href="/admin/modules/accommodations/add-select-option">Create Drop Down Filter</a>
            <a id="save-close-btn" class="alert button hollow" href="/admin/modules/accommodations/checkbox-list">Checkbox Filter List</a>

          </li>	
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          {{--<div class="tabs-panel is-active" id="panel1">--}}
            {{--Accommodations--}}
          {{--</div>--}}
          <!--<div class="tabs-panel" id="panel2">
          </div> -->

          <div class="tabs-panel is-active" id="panel1">
            {!! DataTable::set(\Module\Accommodations\Controllers\Admin\DropDownDataTable::class) !!}
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<footer>
  <div class="helper">
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
  </div>
  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
  
    
    
  @stop