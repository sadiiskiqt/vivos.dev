@extends('atlantis-admin::admin-shell')

@section('title')
Edit Pattern | A3 Administration | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/foundation-datepicker.min.js') !!}
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/tagsInput/jquery.tagsinput.min.js') !!}
@stop

@section('styles')
@parent
{{-- Add styles per template --}}
@stop

@section('content')
@if (isset($invalid_item))
<div class="callout alert">
  <h5>{{ $invalid_item }}</h5>
</div>
@else
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">Edit Pattern</h1>
      </div>
    </div>
  </section>
  <section class="editscreen pattern">
    {!! Form::open(['url' => 'admin/patterns/edit/' . $oPattern->id, 'data-abide' => '', 'novalidate'=> '']) !!}
    <div class="row">
      <div class="columns">
        @if ($oPattern->active == 0)
        <div class="warning callout">
          <h5>This is revision {{ $oPattern->version_id }} and it's not the active version</h5>
          <a href="/admin/patterns/make-active-version/{{ $oPattern->id }}/{{ $oPattern->version_id }}/{{ $oPattern->language }}">Make this version active</a>
        </div>
        @endif
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
          <div class="buttons">
            <a href="/admin/patterns" class="back button tiny top primary" title="Go to Patterns" data-tooltip>
              <span class=" back icon icon-Goto"></span>
            </a>
            {!! Form::input('submit', '_save_close', 'Save & Close', ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
            {!! Form::input('submit', '_update', 'Update', ['class' => 'alert button', 'id'=>'update-btn']) !!}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <!-- data-status: active, disabled or dev -->
            <a href="#panel1" aria-selected="true" data-status="{{ $data_status }}">
              {{ $oPattern->name }}
            </a>
            <span class="actions">
              <a data-open="clonePattern" data-tooltip title="Clone Pattern" class="icon icon-Files top"></a>
              <a data-open="deletePattern" data-tooltip title="Delete Pattern" class="icon icon-Delete top"></a>
            </span>
          </li>
          <li class="tabs-title">
            <a href="#panel2" >Versions</a>
          </li>
          <li class="float-left pattern-resource">
            {!! Form::select('type', $aTypes, $oPattern->type, ['id' => '']) !!} 
          </li>
        </ul>
        <div class="tabs-content" data-equalizer data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1" data-equalizer-watch>
            <div class="row">
              <div class="columns large-6 medium-8">
                {!! \Editor::set('text', $oPattern->text, ['rows' => 20, 'class' => '']) !!}
                <br>
                <div class="">
                  <label for="">Pattern Specific Attributes <a id="create-attr-row" class="button alert small float-right" href="javascript:void(0)">Add New Field</a></label>
                  <table class="dataTable attributes">
                    <thead>
                      <tr>
                        <th>name</th>
                        <th>value</th>
                        <th class="no-sort id"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($oFields as $k => $field)
                      <tr>
                        <td>
                          <input class="visually-hidden" type="text" name="attr[{{ $k }}][name]" value="{{ $field->key }}">
                          <span class="text edittable">{{ $field->key }}</span>
                        </td>
                        <td>
                          <input class="visually-hidden" type="text" name="attr[{{ $k }}][value]" value="{{ $field->value }}">
                          <span class="text edittable">{{ $field->value }}</span>
                        </td>
                        <td class="id">
                          <a href="#" data-tooltip title="Delete Attribute" class="icon icon-Delete top"></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <div class="reveal new-attr-modal" id="exampleModal1" data-reveal>
                    <h5>Awesome. I Have It.</h5>
                    <label for="">Attribute Name <input type="text"></label>
                    <label for="">Attribute Value <input type="text"></label>

                    <button class="button success small" data-close aria-label="Close modal" type="button">

                      <button class="close-button" data-close aria-label="Close modal" type="button">
                        <span aria-hidden="true">&times;</span>
                      </button>
                  </div>

                </div>
              </div>
              <div class="columns large-3 medium-4">
                <div class="row">
                  <div class="columns large-12">
                    @if ($errors->get('name'))
                    <label for="name" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('name')[0] }}</span>
                      <span class="icon icon-Help top" data-tooltip title="This is the name used to indentify the pattern in the CMS"></span>
                      {!! Form::input('text', 'name', old('name', $oPattern->name), ['class' => 'is-invalid-input']) !!}                      
                    </label>
                    @else
                    <label for="name">Pattern Name <span class="form-error">is required.</span>
                      <span class="icon icon-Help top" data-tooltip title="This is the name used to indentify the pattern in the CMS"></span> 
                      {!! Form::input('text', 'name', old('name', $oPattern->name), ['required' => '']) !!}                      
                    </label>
                    @endif
                  </div>
                  <div class="columns  large-12 ">                    
                    @if ($errors->get('url'))
                    <label for="url" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('url')[0] }}</span>
                      <span class="icon icon-Help top" data-tooltip title="Only if Resource is chosen above. Example : /patterns/feed/3"></span>
                      {!! Form::input('text', 'url', old('url', $oPattern->url), ['class' => 'is-invalid-input']) !!}                    
                    </label>
                    @else
                    <label for="1">Resource URL <span class="icon icon-Help top" data-tooltip title="Only if Resource is chosen above. Example : /patterns/feed/3"></span>
                      {!! Form::input('text', 'url', old('url', $oPattern->url), []) !!}
                    </label>
                    @endif                    
                  </div>
                  <div class="columns large-12">
                    @if ($errors->get('outputs'))                   
                    <label for="outputs" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('outputs')[0] }}</span>
                      <span class="icon icon-Help top" data-tooltip title="Use this field when embedding the pattern in your template file. Don't include $ sign here."></span>
                      {!! Form::select('outputs', $variables, $oPattern->outputs, ['id' => '', 'class' => 'is-invalid-input']) !!}
                    </label>
                    @else                    
                    <label for="outputs">Pattern output in <span class="form-error">is required.</span>
                      <span class="icon icon-Help top" data-tooltip title="Use this field when embedding the pattern in your template file. Don't include $ sign here."></span>
                      {!! Form::select('outputs', $variables, $oPattern->outputs, ['id' => '', 'required' => '']) !!}
                    </label>
                    @endif                    
                  </div>
                  <div class="columns large-6">
                    @if ($errors->get('view'))
                    <label for="view" class="is-invalid-label">Pattern View <span class="icon icon-Help top" data-tooltip title="layout/default/pattern/"></span>
                      {!! Form::select('view', $aViews, $oPattern->view, ['id' => '', 'class' => 'is-invalid-input']) !!}
                      <span class="form-error is-visible">{{ $errors->get('view')[0] }}</span>
                    </label>
                    @else
                    <label for="view">Pattern View <span class="icon icon-Help top" data-tooltip title="layout/default/pattern/"></span>
                      {!! Form::select('view', $aViews, $oPattern->view, ['id' => '']) !!} 
                    </label>
                    @endif
                  </div>
                  <div class="columns large-6 ">
                    @if ($errors->get('weight'))
                    <label for="weight" class="is-invalid-label">Weight
                      {!! Form::input('number', 'weight', old('weight', $oPattern->weight), ['class' => 'is-invalid-input']) !!}
                      <span class="form-error is-visible">{{ $errors->get('weight')[0] }}</span>
                    </label>
                    @else 
                    <label for="weight">Weight <span class="form-error">is required.</span>
                      {!! Form::input('number', 'weight', old('weight', $oPattern->weight), ['required' => '']) !!}
                    </label>
                    @endif
                  </div>
                  <div class="columns large-6">
                    <label for="">Language <span class="icon icon-Help top" data-tooltip title="To create version in alternative language, please select from the dropdown."></span>
                      {!! Form::select('language', $aLang, $oPattern->language, ['id' => '']) !!} 
                    </label>
                  </div>
                  <div class="columns large-6">
                    <label for="">Status
                      {!! Form::select('status', $aStatuses, $oPattern->status, ['id' => '']) !!} 
                    </label>
                  </div>
                  <div class="columns large-12">
                    <label for="">Pattern Tags
                      {!! Form::input('text', 'tags', old('tags', $tags), ['class' => 'inputtags']) !!}
                    </label>
                  </div>
                  <div class="columns large-12">
                    @if ($errors->get('mask'))
                    <label for="mask" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('mask')[0] }}</span>
                      {!! Form::textarea('mask', old('mask', $masks), ['rows' => 10, 'cols' => '30', 'id' => '', 'class' => 'is-invalid-input']) !!}
                    </label>
                    @else
                    <label for="mask">URL Mask (one per line) <span class="form-error">is required.</span>
                      {!! Form::textarea('mask', old('mask', $masks), ['rows' => 10, 'cols' => '30', 'id' => '','required' => '']) !!}
                    </label>
                    @endif

                  </div>
                  <div class="columns large-12">
                    <ul class="accordion" data-accordion>                      
                      @if ($errors->get('start_date') || $errors->get('end_date'))
                      <li class="accordion-item is-active" data-accordion-item>
                        <a href="#" class="accordion-title redtext">Expiration <small class="form-error is-visible">Invalid field</small></a>
                        @else
                      <li class="accordion-item" data-accordion-item>
                        <a href="#" class="accordion-title">Expiration</a>
                        @endif
                        <div class="accordion-content" data-tab-content>
                          <p>Leave blank to disable expiration.</p>
                          <div class="row">
                            <div class="columns small-6">
                              @if ($errors->get('start_date'))
                              <label for="start_date" class="is-invalid-label">From</label>
                              <span class="fa fa-calendar dtp-wrapper">
                                {!! Form::input('text', 'start_date', old('start_date', $start_date), ['class' => 'dtp is-invalid-input', 'id'=>'start_date']) !!}
                              </span>
                              <span class="form-error is-visible">{{ $errors->get('start_date')[0] }}</span>
                              @else
                              <label for="from">From</label>
                              <span class="fa fa-calendar dtp-wrapper">
                                {!! Form::input('text', 'start_date', old('start_date', $start_date), ['class' => 'dtp']) !!}
                              </span>
                              @endif
                            </div>
                            <div class="columns small-6">
                              @if ($errors->get('end_date'))
                              <label for="end_date" class="is-invalid-label">To</label>
                              <span class="fa fa-calendar dtp-wrapper">
                                {!! Form::input('text', 'end_date', old('end_date', $end_date), ['class' => 'dtp is-invalid-input', 'id'=>'end_date']) !!}
                              </span>
                              <span class="form-error is-visible">{{ $errors->get('end_date')[0] }}</span>
                              @else
                              <label for="from">To</label>
                              <span class="fa fa-calendar dtp-wrapper">
                                {!! Form::input('text', 'end_date', old('end_date', $end_date), ['class' => 'dtp']) !!}
                              </span>
                              @endif
                            </div>
                            <br>	
                          </div>
                        </div>
                      </li>
                      <!-- <li class="accordion-item" data-accordion-item>
                        <a href="#" class="accordion-title">Additional Actions</a>
                        <div class="accordion-content" data-tab-content>
                          <button class="button alert small">Action</button>
                          <button class="button warning small">Action</button>
                          <button class="button success small">Action</button>
                        </div>
                      </li> -->
                    </ul>
                  </div>
                </div>
              </div>
              <aside class="columns large-3 medium-12 show-for-large" data-equalizer-watch>
                <div class="listing widget"> 
                  <div class="list-container">
                    <h4 class="widget-title">Latest Edited Patterns</h4>
                    <ul>
                      @foreach($oLatestPatterns as $latest)
                      <li>
                        <span class="id">{{ $latest->id }}</span>
                        <a class="item" href="/admin/patterns/edit/{{ $latest->id }}">{{ $latest->name }}</a>
                        <span class="actions">                          
                          <a data-open="deletePattern{{ $latest->id }}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="Delete Pattern" class="icon icon-Delete top "></a>
                          <a data-open="clonePattern{{ $latest->id }}" data-tooltip title="Clone Pattern" class="icon icon-Files top"></a>
                        </span>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </aside>
            </div>
          </div>
          {!! Form::close() !!}
          <div class="tabs-panel" id="panel2">
            {!! DataTable::set(\Atlantis\Controllers\Admin\PatternVersionsDataTable::class, ['pattern_id' => $oPattern->id, 'lang' => $oPattern->language]) !!}
          </div>
        </div>
      </div>
    </div>    
  </section>
</main>
<footer>
{{-- @include('atlantis-admin::help-sections/patterns') --}}
  <div class="row">
    <div class="columns">
    </div>
  </div>
  @foreach($oLatestPatterns as $latest)
  {!! Atlantis\Helpers\Modal::set('deletePattern' . $latest->id, 'Delete Pattern', 'Are you sure you want to delete ' . $latest->name, 'Delete', '/admin/patterns/delete-pattern/' . $latest->id) !!}
  {!! Atlantis\Helpers\Modal::setClonePattern('clonePattern' . $latest->id, '/admin/patterns/clone-pattern/' . $latest->id, $latest->name . '-clone') !!}    
  @endforeach
  {!! \Atlantis\Helpers\Modal::set('deletePattern', 'Delete Pattern', 'Are you sure you want to delete ' . $oPattern->name, 'Delete', '/admin/patterns/delete-pattern/' . $oPattern->id) !!}
  {!! \Atlantis\Helpers\Modal::setClonePattern('clonePattern', '/admin/patterns/clone-pattern/' . $oPattern->id, $oPattern->name . '-clone') !!}
</footer>
@endif
@stop