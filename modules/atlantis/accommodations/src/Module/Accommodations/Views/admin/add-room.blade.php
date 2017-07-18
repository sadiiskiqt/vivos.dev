@extends('atlantis-admin::admin-shell')



@section('scripts')
    @parent
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/foundation-datepicker.min.js') !!}
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/tagsInput/jquery.tagsinput.min.js') !!}
@stop


@section('content')

    <main>
        <section class="greeting">
            <div class="row">
                <div class="columns ">
                    <h1 class="huge page-title">
                        Add Accommodations
                    </h1>
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
        <section class="editscreen">
            {!! Form::open(['url' => '/admin/modules/accommodations/add-room', 'data-abide' => '', 'novalidate'=> '']) !!}
            <div class="row">
                <div class="columns">
                    <div class="float-right">
                        <div class="buttons">
                            <a href="/admin/modules/accommodations/index" class="back button tiny top primary"
                               title="Go to Rooms list" data-tooltip>
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
                            <a href="#panel1" aria-selected="true">
                                {{ ((!empty($aCategoryEdit[0]['sCategoryName'])) ? 'Edit '.$aCategoryEdit[0]['sCategoryName'] : '') }}
                            </a>

                            @if(!empty($aCategoryEdit[0]['id']))
                                <span class="actions">
                                    <a data-tooltip title="Delete Property"
                                       href="/admin/modules/restaurantmenu/deletecategory/{{$aCategoryEdit[0]['id']}}"
                                       class="icon icon-Delete top"></a>
                                </span>
                            @endif
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">

                            <div class="row">
                                <div class="columns large-9">
                                    <div class="row">


                                        <div class="columns medium-10">

                                            @if ($errors->get('room_title'))
                                                <label for="page_name" class="is-invalid-label"><span
                                                            class="form-error is-visible">{{ $errors->get('room_title')[0] }}</span>
                                                    <span class="icon icon-Help top" data-tooltip
                                                          title="This is the name used to indentify the pattern in the CMS."></span>
                                                    {!! Form::input('text', 'room_title', old('room_title'), ['class' => 'is-invalid-input', 'id'=>'page_name', 'required'=>'required']) !!}
                                                </label>
                                            @else
                                                <label for="page_name">Room Title<span
                                                            class="form-error">is required.</span>
                                                    <span class="icon icon-Help top" data-tooltip
                                                          title="This is the name used to indentify the pattern in the CMS."></span>
                                                    {!! Form::input('text', 'room_title', old('room_title'), ['id'=>'page_name', 'required'=>'required']) !!}
                                                </label>
                                            @endif
                                        </div>

                                        <div class="columns medium-10">
                                            @if ($errors->get('body'))
                                                <label for="page_name" class="is-invalid-label"><span
                                                            class="form-error is-visible">{{ $errors->get('body')[0] }}</span>
                                                    <span class="icon icon-Help top" data-tooltip
                                                          title="This is the name used to indentify the pattern in the CMS."></span>
                                                    {!! \Editor::set('body', old('body'), ['rows' => 10, 'id' => 'custom_form', 'class' => 'is-invalid-input']) !!}
                                                </label>
                                            @else
                                                <label for="page_name">Body <span
                                                            class="form-error">is required.</span>
                                                    <span class="icon icon-Help top" data-tooltip
                                                          title="This is the name used to indentify the pattern in the CMS."></span>
                                                    {!! \Editor::set('body', old('body'), ['rows' => 10, 'id' => 'custom_form', 'class' => 'is-invalid-input']) !!}
                                                </label>
                                            @endif

                                        </div>
                                        <div class="columns medium-10">


                                            @if ($errors->get('booking_link'))
                                                <label for="page_name" class="is-invalid-label"><span
                                                            class="form-error is-visible">{{ $errors->get('booking_link')[0] }}</span>
                                                    <span class="icon icon-Help top" data-tooltip
                                                          title="This is the name used to indentify the pattern in the CMS."></span>
                                                    {!! Form::input('text', 'booking_link', old('booking_link'), ['class' => 'is-invalid-input', 'id'=>'page_name']) !!}
                                                </label>
                                            @else
                                                <label for="page_name">Booking Link <span
                                                            class="form-error">is required.</span>
                                                    <span class="icon icon-Help top" data-tooltip
                                                          title="This is the name used to indentify the pattern in the CMS."></span>
                                                    {!! Form::input('text', 'booking_link', old('booking_link'), ['id'=>'page_name', ]) !!}
                                                </label>
                                            @endif
                                        </div>
                                        <div class="columns small-12">
                                            {{--{!! \MediaTools::createImageSelector(null, false) !!}--}}
                                            {!! \MediaTools::createGallerySelector() !!}

                                        </div>
                                    </div>
                                </div>
                                <div class="columns large-3">
                                    <aside>
                                        <ul class="accordion" data-accordion>
                                            <li class="accordion-item" data-accordion-item>
                                                <a href="#" class="accordion-title">Add Amenities</a>
                                                <div class="accordion-content" data-tab-content>

                                                    @if(!empty($aCheckboxs) && is_array($aCheckboxs))
                                                        <p>Please add the necessary Amenities for this room (This
                                                            options
                                                            are not required)</p>
                                                        @foreach($aCheckboxs as $aCheckbox)
                                                            <label for="page_name">{{$aCheckbox['sCheckboxTitle']}}<span
                                                                        class="form-error">is required.</span>
                                                                <span class="icon icon-Help top" data-tooltip
                                                                      title="This is the name used to indentify the pattern in the CMS."></span>
                                                                <input type="checkbox" name="Create_Checkbox_Filter[]"
                                                                       value="{{$aCheckbox['id']}}">
                                                            </label>
                                                        @endforeach
                                                    @else
                                                        <p style="color: red">They are no Checkbox Filters please create
                                                            some!</p>
                                                    @endif
                                                </div>
                                            </li>
                                        </ul>
                                    </aside>
                                </div>

                                <div class="columns large-3">
                                    <aside>
                                        <ul class="accordion" data-accordion>
                                            <li class="accordion-item" data-accordion-item>
                                                <a href="#" class="accordion-title">Add Room Option Type</a>
                                                <div class="accordion-content" data-tab-content>

                                                    @if(!empty($aDropDowns) && is_array($aDropDowns))
                                                        <p>Please add the necessary Options for this room (This options
                                                            are not required)</p>
                                                        @foreach($aDropDowns as $aDropDown)
                                                            <label for="page_name">{{$aDropDown['sDropDownTitle']}}
                                                                <span class="form-error">is required.</span>
                                                                <span class="icon icon-Help top" data-tooltip
                                                                      title="This is the name used to indentify the pattern in the CMS."></span>
                                                                <select name="drop_down[{{$aDropDown['id']}}]">
                                                                    <option value="null">-- Option --</option>
                                                                    @foreach($aDropDown['option'] as $aOption)
                                                                        <option value="{{$aOption['id']}}">{{$aOption['sOptionTitle']}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </label>
                                                        @endforeach
                                                    @else
                                                        <p style="color: red">They are no Options Filters please create
                                                            some!</p>
                                                    @endif

                                                </div>
                                            </li>
                                        </ul>
                                    </aside>
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
        <div class="helper">
            <button type="button" class="icon icon-Bulb" data-panel-toggle="tips-panel"></button>
            <div class="right-panel side-panel" id="tips-panel" data-atlantis-panel>
                <ul class="accordion" data-accordion>
                    <li class="accordion-item is-active" data-accordion-item>
                        <a href="#" class="accordion-title">Tip 2</a>
                        <div class="accordion-content" data-tab-content>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex possimus labore numquam
                            assumenda et consectetur rem minima quis commodi nam atque corporis qui, exercitationem
                            alias voluptatem magnam ad. Esse, ipsum.
                        </div>
                    </li>
                    <li class="accordion-item" data-accordion-item>
                        <a href="#" class="accordion-title">Tip 1</a>
                        <div class="accordion-content" data-tab-content>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic, accusantium, laudantium?
                            Veniam a officiis, consequatur. Voluptatibus, consectetur, nam temporibus in fugiat
                            assumenda distinctio vitae modi architecto beatae provident voluptates magnam.
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