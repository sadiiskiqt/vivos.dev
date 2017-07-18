@extends('atlantis-admin::admin-shell')

@section('title')
Add Page | A3 Administration | {{ config('atlantis.site_name') }}
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

@section('js')
@parent
<script type="text/javascript">
  $(document).ready(function () {
    if (typeof $.fn.makeURL != 'undefined') {
      $("#page_name").makeURL("page_url");
    }
  });
</script>
@stop

@section('content')
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">Add Page</h1>
      </div>
    </div>
  </section>
  <section class="editscreen">
    {!! Form::open(['url' => 'admin/pages/add', 'data-abide' => '', 'novalidate'=> '']) !!}
    <div class="row">
      <div class="columns">
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
            <a href="/admin/pages" class="back button tiny top primary" title="Go to Pages" data-tooltip>
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
            <a href="#panel1" aria-selected="true">New Page</a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">

            <div class="row">
              <div class="columns large-9">
                <div class="row">
                  <div class="columns medium-4">
                    @if ($errors->get('name'))
                    <label for="page_name" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('name')[0] }}</span>
                      <span class="icon icon-Help top" data-tooltip title="This is the name used to indentify the pattern in the CMS."></span>
                      {!! Form::input('text', 'name', old('name'), ['class' => 'is-invalid-input', 'id'=>'page_name', 'required'=>'required']) !!}
                    </label>
                    @else
                    <label for="page_name">Page Name <span class="form-error">is required.</span>
                      <span class="icon icon-Help top" data-tooltip title="This is the name used to indentify the pattern in the CMS."></span>
                      {!! Form::input('text', 'name', old('name'), ['id'=>'page_name', 'required'=>'required']) !!}
                    </label>
                    @endif
                  </div>
                  <div class="columns medium-4">
                    <label for="seo_title">Seo Title <span class="form-error">is required.</span>
                      <span class="icon icon-Help top" data-tooltip title="This title appears between the <title> tags"></span>
                      {!! Form::input('text', 'seo_title', old('seo_title'), []) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    @if ($errors->get('url'))
                    <label for="page_url" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('url')[0] }}</span>
                      <span class="icon icon-Help top" data-tooltip title="The url address of the page"></span>
                      {!! Form::input('text', 'url', old('url'), ['class' => 'is-invalid-input', 'id'=>'page_url', 'required'=>'required']) !!}
                    </label>
                    @else
                    <label for="page_url">Page URL <span class="form-error">is required.</span>
                      <span class="icon icon-Help top" data-tooltip title="The url address of the page"></span>
                      {!! Form::input('text', 'url', old('url'), ['id'=>'page_url', 'required'=>'required']) !!}
                    </label>
                    @endif
                  </div>
                  <div class="columns medium-4">
                    <label for="">
                      Meta Keywords
                      <small id="meta_keywords_info">255 characters left</small>
                      <span class="icon icon-Help top" data-tooltip title="If you leave this field blank, the global keywords will be used."></span>
                      {!! Form::input('text', 'meta_keywords', old('meta_keywords'), ['id'=>'meta_keywords']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="">
                      Meta Description 
                      <small id="meta_description_info">255 characters left</small>
                      <span class="icon icon-Help top" data-tooltip title="If you leave this field blank, the global description will be used."></span>
                      {!! Form::input('text', 'meta_description', old('meta_description'), ['id'=>'meta_description']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="">Page Tags
                      {!! Form::input('text', 'tags', old('tags'), ['class' => 'inputtags']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    @if ($errors->get('template'))
                    <label for="page_template" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('template')[0] }}</span>
                      <span class="icon icon-Help top" data-tooltip title="Which display template the page should use."></span>
                      {!! Form::select('template', $aTemplates, $default_template, ['class' => 'is-invalid-input', 'id' => 'page_template', 'required'=>'required']) !!}    
                    </label>
                    @else                    
                    <label for="page_template">Page Template <span class="form-error">is required.</span>
                      <span class="icon icon-Help top" data-tooltip title="Which display template the page should use."></span>
                      {!! Form::select('template', $aTemplates, $default_template, ['id' => 'page_template', 'required'=>'required']) !!}    
                    </label>
                    @endif
                  </div>
                  <div class="columns medium-3 xxlarge-2">
                    <label for="categories_id">Page Category

                      <select id="categories_id" name="categories_id">
                        @foreach ($aCategories as $cat_key => $cat_val)                        
                        <option data-action="{!! $cat_val['category_action'] !!}" data-string="{!! $cat_val['category_string'] !!}" data-template="{!! $cat_val['category_view'] !!}" value="{!! $cat_key !!}">{!! $cat_val['category_name'] !!}</option>
                        @endforeach                        
                      </select>    

                    </label>
                  </div>
                  <div class="columns medium-3 xxlarge-2">
                    @if ($errors->get('path'))
                    <label for="path" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('path')[0] }}</span>
                      {!! Form::select('path', $aParent, NULL, ['id' => 'path']) !!}  
                    </label>
                    @else
                    <label for="path">Parent Document
                      {!! Form::select('path', $aParent, NULL, ['id' => 'path']) !!}  
                    </label>
                    @endif
                  </div>
                  <div class="columns medium-3 xxlarge-2">
                    <label for="">Page Status
                      <span class="icon icon-Help top" data-tooltip title="Select publishing status for this page."></span>
                      {!! Form::select('status', $aStatuses, 1, ['id' => '']) !!} 
                    </label>
                  </div>
                  <div class="columns medium-3 xxlarge-2">
                    <label for="">Language
                      <span class="icon icon-Help top" data-tooltip title="To create version in alternative language, please select from the dropdown."></span>
                      {!! Form::select('language', $aLang, config('atlantis.default_language'), ['id' => '']) !!} 
                    </label>
                  </div>
                  <div class="columns end">
                    {!! \Editor::set('page_body', NULL, ['rows' => 35, 'class' => '']) !!}                     
                  </div>
                </div>
              </div>
              <div class="columns large-3">
                <aside>
                  <ul class="accordion" data-accordion>
                    <li class="accordion-item is-active" data-accordion-item>
                      <a href="#" class="accordion-title">Cache</a>
                      <div class="accordion-content" data-tab-content>
                        <p> Include this page in site cache index</p>
                        <div class="switch tiny">
                          {!! Form::checkbox('cache', 1, TRUE, ['class' => 'switch-input', 'id' => 'cacheSwitch']) !!}
                          <label class="switch-paddle" for="cacheSwitch">
                            <span class="show-for-sr">
                              Cache Enabled
                            </span>
                          </label>
                        </div>
                      </div>
                    </li>
                    <li class="accordion-item" data-accordion-item>
                      <a href="#" class="accordion-title">Page Specific Styles</a>
                      <div class="accordion-content" data-tab-content>
                        <p>Enter declarations one per line.
                          Shift+Enter for new row.
                          These styles will be loaded only for this page. (assets/css/test.css)</p>
                        {!! Form::textarea('styles', old('styles'), ['rows' => 10, 'cols' => '30', 'id' => '']) !!}
                      </div>
                    </li>
                    <li class="accordion-item" data-accordion-item>
                      <a href="#" class="accordion-title">Page Specific Scripts</a>
                      <div class="accordion-content" data-tab-content>
                        <p>Enter declarations one per line.
                          Shift+Enter for new row.
                          These scripts will be loaded only for this page. (assets/js/test.js)</p>
                        {!! Form::textarea('scripts', old('scripts'), ['rows' => 10, 'cols' => '30', 'id' => '']) !!}
                      </div>
                    </li>
                    @if ($errors->get('start_date') || $errors->get('end_date'))
                    <li class="accordion-item is-active" data-accordion-item>
                      <a href="#" class="accordion-title redtext">Expiration <small class="form-error is-visible">Invalid field</small></a>
                      @else
                    <li class="accordion-item" data-accordion-item>
                      <a href="#" class="accordion-title">Expiration</a>
                      @endif
                      <div class="accordion-content" data-tab-content>
                        <p>If you choose start and end date, page will be active only within that range. Leaving fields blank makes the page published indefinitely.</p>
                        <div class="row">
                          <div class="columns small-6">
                            @if ($errors->get('start_date'))
                            <label for="start_date" class="is-invalid-label">From</label>
                            <span class="fa fa-calendar dtp-wrapper">
                              {!! Form::input('text', 'start_date', old('start_date'), ['class' => 'dtp is-invalid-input', 'id'=>'start_date']) !!}
                            </span>
                            <span class="form-error is-visible">{{ $errors->get('start_date')[0] }}</span>
                            @else
                            <label for="from">From</label>
                            <span class="fa fa-calendar dtp-wrapper">
                              {!! Form::input('text', 'start_date', old('start_date'), ['class' => 'dtp']) !!}
                            </span>
                            @endif
                          </div>
                          <div class="columns small-6">
                            @if ($errors->get('end_date'))
                            <label for="end_date" class="is-invalid-label">To</label>
                            <span class="fa fa-calendar dtp-wrapper">
                              {!! Form::input('text', 'end_date', old('end_date'), ['class' => 'dtp is-invalid-input', 'id'=>'end_date']) !!}
                            </span>
                            <span class="form-error is-visible">{{ $errors->get('end_date')[0] }}</span>
                            @else
                            <label for="from">To</label>
                            <span class="fa fa-calendar dtp-wrapper">
                              {!! Form::input('text', 'end_date', old('end_date'), ['class' => 'dtp']) !!}
                            </span>
                            @endif
                          </div>
                          <br>  
                        </div>
                      </div>
                    </li>
                    <li class="accordion-item" data-accordion-item>
                      <a href="#" class="accordion-title">SSL</a>
                      <div class="accordion-content" data-tab-content>
                        <p> Enable SSL for this page</p>
                        <div class="switch tiny">
                          {!! Form::checkbox('is_ssl', 1, FALSE, ['class' => 'switch-input', 'id' => 'sslSwitch']) !!}
                          <label class="switch-paddle" for="sslSwitch">
                            <span class="show-for-sr">
                              SSL Enabled
                            </span>
                          </label>
                        </div>
                      </div>
                    </li>
                    <li class="accordion-item" data-accordion-item>
                      <a href="#" class="accordion-title">Related Title</a>
                      <div class="accordion-content" data-tab-content>
                        {!! Form::input('text', 'related_title', old('related_title'), []) !!}
                      </div>
                    </li> 
                    <li class="accordion-item" data-accordion-item>
                      <a href="#" class="accordion-title">Related Image</a>
                      <div class="accordion-content" data-tab-content>
                        <div id="preview_thumb_id">

                        </div>
                        <br><br>
                        <button role="button" data-open="imagePreview" class="button">Browse</button>
                        <button role="button" href="#"  class="remove-thumb alert button">Remove</button>
                      </div>

                      {!! Form::input('hidden', 'preview_thumb_id', old('preview_thumb_id'), []) !!}
                    </li>
                    <li class="accordion-item" data-accordion-item>
                      <a href="#" class="accordion-title">Excerpt</a>
                      <div class="accordion-content" data-tab-content>
                        {!! Form::textarea('excerpt', old('excerpt'), ['rows' => 10, 'cols' => '30', 'id' => '']) !!}
                      </div>
                    </li>
                    <li class="accordion-item" data-accordion-item>
                      <a href="#" class="accordion-title">Notes</a>
                      <div class="accordion-content" data-tab-content>
                        {!! Form::textarea('notes', old('notes'), ['rows' => 10, 'cols' => '30', 'id' => '']) !!}
                      </div>
                    </li>
                    <li class="accordion-item" data-accordion-item>
                      <a href="#" class="accordion-title">Author</a>
                      <div class="accordion-content" data-tab-content>
                        {!! Form::input('text', 'author', old('author', $username), []) !!}
                      </div>
                    </li>
                    <li class="accordion-item" data-accordion-item>
                      <a href="#" class="accordion-title">Page Protected</a>
                      <div class="accordion-content" data-tab-content>
                        <p>Enable page protection</p>
                        <div class="switch tiny">
                          {!! Form::checkbox('protected', 1, FALSE, ['class' => 'switch-input', 'id' => 'protSwitch']) !!}
                          <label class="switch-paddle" for="protSwitch">
                            <span class="show-for-sr">
                              Protection Enabled
                            </span>
                          </label>
                        </div>
                      </div>
                    </li>
                    
                    
                    @if ($errors->has('canonical_url'))
                    <li class="accordion-item is-active" data-accordion-item>
                      <a href="#" class="accordion-title redtext">Canonical URL</a>
                      <div class="accordion-content" data-tab-content>
                        <p><small class="form-error is-visible">{{ $errors->get('canonical_url')[0] }}</small></p>
                        @else
                        <li class="accordion-item" data-accordion-item>
                          <a href="#" class="accordion-title">Canonical URL</a>
                          <div class="accordion-content" data-tab-content>
                            @endif                      
                            <p>If you don’t add a value, Atlantis will generate canonical tag based on your page url.</p>
                            {!! Form::input('text', 'canonical_url', old('canonical_url'), ['class' => $errors->has('canonical_url') ? 'is-invalid-input' : NULL]) !!}
                          </div>
                        </li>
                    
                  </ul>
                </aside>
              </div>
            </div>
          </div>
          <div class="tabs-panel" id="panel2">

            <table class="dataTable extended">
              <thead>
                <tr>
                  <th>Head</th>
                  <th>head</th>
                  <th>head</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>  content</td>
                  <td>  content</td>
                  <td>  content</td>
                </tr>
                <tr>
                  <td>  content</td>
                  <td>  content</td>
                  <td>  content</td>
                </tr>
                <tr>
                  <td>  lorem</td>
                  <td>  lorem</td>
                  <td>lorem</td>
                </tr>
              </tbody> 

            </table>
          </div>
        </div>
      </div>
    </div>
    {!! Form::close() !!}
  </section>
</main>
<footer>
  {{-- @include('atlantis-admin::help-sections/pages') --}}
  <div class="row">
    <div class="columns">
    </div>
  </div>
  {!!  \Atlantis\Helpers\Modal::pagePreview('imagePreview') !!}
</footer>
@stop