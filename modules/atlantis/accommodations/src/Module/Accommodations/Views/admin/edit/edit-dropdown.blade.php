@extends('atlantis-admin::admin-shell')

{{--<script type="text/javascript" src="http://code.jquery.com/jquery-1.3.2.min.js "></script>--}}

{{--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>--}}
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>


@section('content')

    <main>
        <section class="greeting">
            <div class="row">
                <div class="columns ">
                    <h1 class="huge page-title">
                        Edit Drop Down Filter
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
            {!! Form::open(['url' => '/admin/modules/accommodations/edit-dropdown', 'data-abide' => '', 'novalidate'=> '']) !!}
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
                                {{ ((!empty($aDropdawnData[0]['sDropDownTitle'])) ? 'Edit '.$aDropdawnData[0]['sDropDownTitle'] : '') }}
                            </a>

                            @if(!empty($aDropdawnData[0]['id']))
                                <span class="actions">
                                    <a data-tooltip title="Delete Property"
                                       href="/admin/modules/accommodations/deletecategory/{{$aDropdawnData[0]['id']}}"
                                       class="icon icon-Delete top"></a>
                                </span>
                            @endif
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">
                            <input type="hidden" name="updateDropDown" value="{{$aDropdawnData[0]['id']}}">
                            <div class="row">
                                <div class="columns large-7">
                                    <div class="row">
                                        @foreach($aDropdawnData as $aDropDown)
                                            <div class="columns medium-12">
                                                @if ($errors->get('drop_down_title'))
                                                    <label for="page_name" class="is-invalid-label"><span
                                                                class="form-error is-visible">{{ $errors->get('drop_down_title')[0] }}</span>
                                                        <span class="icon icon-Help top" data-tooltip
                                                              title="This is the Filter Name."></span>
                                                        <input type="text" name="drop_down_title"
                                                               value="{{$aDropDown['sDropDownTitle']}}"/>
                                                    </label>
                                                @else
                                                    <label for="page_name">Drop Down Filter Title <span
                                                                class="form-error"> is required.</span>
                                                        <span class="icon icon-Help top" data-tooltip
                                                              title="This is the Filter Name."></span>
                                                        <input type="text" name="drop_down_title"
                                                               value="{{$aDropDown['sDropDownTitle']}}"/>
                                                    </label>
                                                @endif
                                            </div>


                                            @foreach($aDropDown['option'] as $aOption)

                                                <div class="columns medium-12" id="div1">
                                                    @if ($errors->get('drop_down_option'))
                                                        <label for="page_name" class="is-invalid-label"><span
                                                                    class="form-error is-visible">{{ $errors->get('drop_down_option')[0] }}</span>
                                                            <span class="icon icon-Help top" data-tooltip
                                                                  title="This is the Filter Option."></span>
                                                            <input type="text" name="option[{{$aOption['id']}}]"
                                                                   value="{{$aOption['sOptionTitle']}}"/>
                                                            <a style="float: right"
                                                               href="/admin/modules/accommodations/remove-drop-down-option/{{$aDropdawnData[0]['id']}}/{{$aOption['id']}}"
                                                               data-tooltip aria-haspopup="true"
                                                               data-disable-hover="false"
                                                               tabindex="1"
                                                               title="Delete Drop Down"
                                                               class="icon icon-Delete top "></a>
                                                        </label>
                                                    @else
                                                        <label for="page_name">Option Title<span class="form-error"> is required.</span>
                                                            <span class="icon icon-Help top" data-tooltip
                                                                  title="This is the Filter Option."></span>
                                                            <input type="text" name="option[{{$aOption['id']}}]"
                                                                   value="{{$aOption['sOptionTitle']}}"/>

                                                            {{--<a style="float: right" href="javascript:void(0);"--}}
                                                               {{--class="remove_button_1" title="Remove field">Remove 00--}}
                                                                {{--Option</a>--}}

                                                            <a style="float: right"
                                                               href="/admin/modules/accommodations/remove-drop-down-option/{{$aDropdawnData[0]['id']}}/{{$aOption['id']}}"
                                                               data-tooltip aria-haspopup="true"
                                                               data-disable-hover="false"
                                                               tabindex="1"
                                                               title="Delete Option {{$aOption['sOptionTitle']}}"
                                                               class="icon icon-Delete top "></a>
                                                        </label>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endforeach

                                        <script type="text/javascript">
                                            $(document).ready(function () {

                                                $(".remove_button_1").click(function () {
                                                    $("#div1").remove();
                                                });

                                                $("#div1").on('click', '.remove_button_1', function (e) { //Once remove button is clicked
                                                    e.preventDefault();
                                                    $(this).parent('div').remove(); //Remove field html
                                                    x--; //Decrement field counter
                                                });


                                                var maxField = 100; //Input fields increment limitation
                                                var addButton = $('.add_button'); //Add button selector
                                                var wrapper = $('.field_wrapper'); //Input field wrapper
                                                var fieldHTML =
                                                    '<div>'
                                                    + '<label for="page_name"> Add Option Title'
                                                    + '</label>'
                                                    + '<input style="float: right" type="text" name="field_name_option[]" value=""/>'
                                                    + '<a style="float: right" href="javascript:void(0);" class="button remove_button" title="Remove field">'
                                                    + 'Remove Option'
                                                    + '</a>'
                                                    + '</div>'; //New input field html
                                                var x = 1; //Initial field counter is 1
                                                $(addButton).click(function () { //Once add button is clicked
                                                    if (x < maxField) { //Check maximum number of input fields
                                                        x++; //Increment field counter
                                                        $(wrapper).append(fieldHTML); // Add field html
                                                    }
                                                });
                                                $(wrapper).on('click', '.remove_button', function (e) { //Once remove button is clicked
                                                    e.preventDefault();
                                                    $(this).parent('div').remove(); //Remove field html
                                                    x--; //Decrement field counter
                                                });
                                            });
                                        </script>
                                        <div class="columns medium-12">

                                            <div class="field_wrapper">
                                                <div>
                                                    <a href="javascript:void(0);" class="add_button alert button"
                                                       title="Add field">Add Option</a>
                                                </div>
                                            </div>
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