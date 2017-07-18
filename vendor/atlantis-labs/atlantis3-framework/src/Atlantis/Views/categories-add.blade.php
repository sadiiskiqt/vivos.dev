@extends('atlantis-admin::admin-shell')

@section('title')
Add Category| A3 Administration | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
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
        <h1 class="huge page-title">Add Category</h1>
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
      </div>
    </div>
  </section>
  <section class="editscreen">
    {!! Form::open(['url' => 'admin/categories/add', 'data-abide' => '', 'novalidate'=> '']) !!}
    <div class="row">
      <div class="columns">
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
            <a href="#panel1" aria-selected="true">New Category</a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">

            <div class="row">
              <div class="columns large-9">
                <div class="row">
                  <div class="columns medium-4">
                    @if ($errors->get('category_name'))
                    <label for="category_name" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('category_name')[0] }}</span>
                      <span class="icon icon-Help top" data-tooltip title="Category name can be arbitrary. it let's you organize pages in categories, as well as auto-generate urls."></span>
                      {!! Form::input('text', 'category_name', old('category_name'), ['class' => 'is-invalid-input', 'id'=>'category_name']) !!}
                    </label>
                    @else
                    <label for="category_name">Category Name <span class="form-error">is required.</span>
                      <span class="icon icon-Help top" data-tooltip title="Category name can be arbitrary. it let's you organize pages in categories, as well as auto-generate urls."></span>
                      {!! Form::input('text', 'category_name', old('category_name'), ['id'=>'category_name', 'required'=>'required']) !!}
                    </label>
                    @endif
                  </div>
                  <div class="columns medium-4">
                    @if ($errors->get('category_string'))
                    <label for="category_string" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('category_string')[0] }}</span>
                      <span class="icon icon-Help top" data-tooltip title="Apply this string to the action. This string will be applied when creating a page to the page url , based on the action you have chosen above."></span>
                      {!! Form::input('text', 'category_string', old('category_string'), ['class' => 'is-invalid-input', 'id'=>'category_string']) !!}
                    </label>
                    @else
                    <label for="category_string">Category String <span class="form-error">is required.</span>
                      <span class="icon icon-Help top" data-tooltip title="Apply this string to the action. This string will be applied when creating a page to the page url , based on the action you have chosen above."></span>
                      {!! Form::input('text', 'category_string', old('category_string'), ['id'=>'category_string', 'required'=>'required']) !!}
                    </label>
                    @endif
                  </div>
                  <div class="columns medium-4">
                    <label for="category_action">Category Action
                      <span class="icon icon-Help top" data-tooltip title="This action defines what the url will become once a new page is being added to this category."></span>
                      {!! Form::select('category_action', $actions, NULL, ['id' => '']) !!} 
                    </label>
                  </div>
                  <div class="columns medium-4 end">
                    <label for="category_view">Category Default View
                      <span class="icon icon-Help top" data-tooltip title="To create version in alternative language, please select from the dropdown."></span>
                      {!! Form::select('category_view', $aTemplates, NULL, ['id' => '']) !!} 
                    </label>
                  </div>                  
                </div>
              </div>              
            </div>
          </div>
        </div>
      </div>
    </div>
    {!! Form::close() !!}
  </section>
</main>
<footer>

  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
@stop