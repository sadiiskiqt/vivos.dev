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
                        Create Drop Down
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
            {!! Form::open(['url' => '/admin/modules/accommodations/add-select-option', 'data-abide' => '', 'novalidate'=> '']) !!}
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
                                       href="/admin/modules/accommodations/deletecategory/{{$aCategoryEdit[0]['id']}}"
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


                                        <div class="columns medium-8">
                                            @if ($errors->get('drop_down_title'))
                                                <label for="page_name" class="is-invalid-label"><span
                                                            class="form-error is-visible">{{ $errors->get('drop_down_title')[0] }}</span>
                                                    <span class="icon icon-Help top" data-tooltip
                                                          title="This is the name used to indentify the pattern in the CMS."></span>
                                                    {!! Form::input('text', 'drop_down_title', old('drop_down_title'), ['class' => 'is-invalid-input', 'id'=>'page_name', 'required'=>'required']) !!}
                                                </label>
                                            @else
                                                <label for="page_name">Drop Down Title <span
                                                            class="form-error"> is required.</span>
                                                    <span class="icon icon-Help top" data-tooltip
                                                          title="This is the name used to indentify the pattern in the CMS."></span>
                                                    {!! Form::input('text', 'drop_down_title', old('drop_down_title'), ['id'=>'page_name', 'required'=>'required']) !!}
                                                </label>
                                            @endif


                                            <script type="text/javascript">
                                                $(document).ready(function () {
                                                    var maxField = 100; //Input fields increment limitation
                                                    var addButton = $('.add_button'); //Add button selector
                                                    var wrapper = $('.field_wrapper'); //Input field wrapper
                                                    var fieldHTML = '<div>'
                                                        + '<label for="page_name"> Add Option Title'
                                                        + '</label>'
                                                        + '<input style="float: right" type="text" name="field_name_option[]" value=""/>'
                                                        + '<a style="float: right" href="javascript:void(0);" class="remove_button" title="Remove field">'
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

                                            <div class="field_wrapper">
                                                <div>
                                                    <option type="checkbox" name="field_name_option[]"
                                                            value=""></option>
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