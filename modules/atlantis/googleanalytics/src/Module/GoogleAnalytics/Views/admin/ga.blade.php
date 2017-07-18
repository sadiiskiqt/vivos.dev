@extends('atlantis-admin::admin-shell')

@section('content')

<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">        
        <h1 class="huge page-title">Google Analytics</h1>
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
    {!! Form::open(array('url' => '/admin/modules/googleanalytics/update', 'data-abide' => '', 'novalidate'=> '')) !!}
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <a href="#panel1" aria-selected="true">
              Google Analytics
            </a>
          </li>
          <!-- <li class="tabs-title main">
            <a href="#panel2">
              Recently Used
            </a>
          </li> -->
          <li class="float-right list-filter">
            {!! Form::input('submit', '_update', 'Update', ['class' => 'alert button', 'id'=>'update-btn']) !!}
          </li>	
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            {!! Form::open(array('url' => '/admin/modules/googleanalytics/update', 'data-abide' => '', 'novalidate'=> '')) !!}
            <div class="row">
              <div class="columns medium-4">                          
                <label for="tracking_code">Tracking Code
                  {!! Form::input('text', 'tracking_code', old('tracking_code', $model->tracking_code), ['id'=>'tracking_code']) !!}
                </label>
              </div>
              <div class="columns medium-4 end">                          
                <label for="tag_manager_code">Tag Manager Code
                  {!! Form::input('text', 'tag_manager_code', old('tag_manager_code', $model->tag_manager_code), ['id'=>'tag_manager_code']) !!}
                </label>
              </div>
            </div>
            <div class="row">
              <div class="columns medium-4 end">                          
                <label for="active_ga">
                  {!! Form::radio('active', 'GA', $is_ga, array('id'=>'active_ga')) !!}
                  Google Analytics</label>
              </div>
            </div>
            <div class="row">
              <div class="columns medium-4 end">                          
                <label for="active_gtm">
                  {!! Form::radio('active', 'GTM', $is_gtm, array('id'=>'active_gtm')) !!}
                  Google Tag Manager</label>
              </div>
            </div>
            
          </div>
          <!--<div class="tabs-panel" id="panel2">
          </div> -->
        </div>
      </div>
    </div>
    {!! Form::close() !!}
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