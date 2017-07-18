@extends('atlantis-admin::admin-shell') @section('scripts') @parent {{-- Add scripts per template --}} {!! Html::script('modules/atlantis/menus/src/Module/Menus/Assets/jquery-sortable.js') !!} @stop @section('styles') @parent {{-- Add styles per template --}} @stop @section('content')
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">Add Menu</h1> @if (isset($msgInfo))
        <div class="callout warning">
          <h5>{!! $msgInfo !!}</h5>
        </div>
        @endif @if (isset($msgSuccess))
        <div class="callout success">
          <h5>{!! $msgSuccess !!}</h5>
        </div>
        @endif @if (isset($msgError))
        <div class="callout alert">
          <h5>{!! $msgError !!}</h5>
        </div>
        @endif
      </div>
    </div>
  </section>
  <section class="editscreen">
    {!! Form::open(['url' => 'admin/modules/menus/add', 'data-abide' => '', 'novalidate'=> '']) !!}
    <div class="row">
      <div class="columns">
        <div class="float-right">
          <div class="buttons">
            <a href="/admin/modules/menus" class="back button tiny top primary" title="Go to Menus" data-tooltip>
              <span class=" back icon icon-Goto"></span>
            </a>
            {!! Form::input('submit', '_save_close', 'Save &amp; Close', ['class' => 'alert button', 'id'=>'save-close-btn']) !!} {!! Form::input('submit', '_update', 'Update', ['class' => 'alert button', 'id'=>'update-btn']) !!}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <!-- data-status: active, disabled or dev -->
            <a href="#panel1" aria-selected="true">New Menu</a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            <div class="row">
              <div class="columns large-7">
                <div class="row">
                  <div class="columns medium-4">
                    @if ($errors->get('name'))
                    <label for="name" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('name')[0] }}</span>
                      <span class="icon icon-Help top" data-tooltip title="This will be used to identify the Menu."></span> {!! Form::input('text', 'name', old('name'), ['class' => 'is-invalid-input', 'id'=>'name']) !!}
                    </label>
                    @else
                    <label for="name">Menu Name <span class="form-error">is required.</span>
                      <span class="icon icon-Help top" data-tooltip title="This will be used to identify the Menu."></span> {!! Form::input('text', 'name', old('name'), ['id'=>'name', 'required'=>'required']) !!}
                    </label>
                    @endif
                  </div>
                  <div class="columns medium-4">
                    <label for="css">Menu CSS Class
                      <span class="icon icon-Help top" data-tooltip title="Css class for the menu."></span> {!! Form::input('text', 'css', old('css'), ['id'=>'css']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4">
                    <label for="menu_attributes">Menu Attributes
                      {!! Form::input('text', 'menu_attributes', old('menu_attributes'), ['id'=>'menu_attributes']) !!}
                    </label>
                  </div>
                  <div class="columns medium-4 end">
                    <label for="element_id">Menu Element ID
                      <span class="icon icon-Help top" data-tooltip title="ID of the menu."></span> {!! Form::input('text', 'element_id', old('element_id'), ['id'=>'element_id']) !!}
                    </label>
                  </div>
                </div>
              </div>
              <div class="columns large-7 end">
                <hr>
              </div>
            </div>
            <div class="row">
              <div class="columns large-7">
                <label for="">MENU ITEMS <a id="add-menu-item" class="button alert small float-right">Add New Item</a></label>
              </div>
              <ol id="sortable" class="columns large-7 end">
                <li id="row_items_1">
                  <div class="callout">
                    <div class="row menu-item">
                      <div class="columns large-3">
                        <label for="">
                          <span class="fa fa-bars move"></span>
                          <span class="index">1</span>.
                          <span class="item-title">
                            NEW ITEM
                          </span>
                          <small class="item-url">                   
                          <!--   <span class="icon icon-Linked"></span>/ -->
                          </small>
                        </label>
                        <span class="actions">
                          <a data-toggle="advanced-item1" class=""><span data-tooltip title="Show/Hide Advanced Settings" class="icon icon-Settings top"></span></a>

                        </span>
                      </div>
                      <div class="columns">
                        <div class="row advanced expanded" data-length="1" id="advanced-item1" data-toggler=".expanded">
                          <br>
                          <div class="columns large-4">
                            <label for="">
                              Item Label {!! Form::input('text', 'label[]', NULL, []) !!}
                            </label>
                          </div>
                          <div class="columns large-4">
                            <label for="">
                              Item URL {!! Form::input('text', 'url[]', NULL, []) !!}
                            </label>
                          </div>
                          <div class="columns large-4">
                            <label for="">
                              Item Attributes {!! Form::input('text', 'attributes[]', NULL, []) !!}
                            </label>
                          </div>
                          <div class="columns large-4">
                            <label for="">
                              Class {!! Form::input('text', 'class[]', NULL, []) !!}
                            </label>
                          </div>
                          <div class="columns large-4">
                            <label for="">
                              Item onClick {!! Form::input('text', 'onclick[]', NULL, []) !!}
                            </label>
                          </div>
                          <div class="columns large-4">
                            <label for="">
                              Child Menu {!! Form::select('child_id[]', $menus, NULL, []) !!}
                            </label>
                          </div>
                          <div class="columns large-12">
                            {!! Form::input('hidden', 'weight[]', 1, ['required'=>'required', 'min'=>'1']) !!}
                            <a id="btn_delete_1" class="button alert small">Delete Item</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>
    {!! Form::close() !!}
  </section>
</main>
<footer>
  @include('menus-admin::admin/help-sections/menus')
  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
@stop

<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function (event) {
    $("#sortable").sortable({
      onDrop: function ($item, container, _super, event) {
        $item.removeClass(container.group.options.draggedClass).removeAttr("style");
        $("body").removeClass(container.group.options.bodyClass);

        $.each($('ol#sortable>li'), function (key, val) {
          $(this).find('[type="hidden"][name*="weight"]').val(key + 1);
          $(this).find('.index').text((key + 1));
        });


      }
    });
  });
</script>