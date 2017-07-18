<link media="all" type="text/css" rel="stylesheet" href="{{$pathVendor}}css/styles.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"/>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!--HEADER ONLY FOR THE DEMO-->
<div>
    <img src="http://placehold.it/1920x300">
</div>

<!-- MAIN PART -->
<main>
    <div class="row rooms_filter_main">
        <form action="" method="get">
            <div class="columns large-3">
                <div id="rooms-filter" class="dropdown_example">
                    <h3 class="side_bar_title">Rooms</h3>
                    @if(!empty($aDropDownFilter))
                        @foreach($aDropDownFilter as $aDropDown)
                            <div class="dropdown">
                                <h4>{{$aDropDown['sDropDownTitle']}}</h4>
                                <select name="{{$aDropDown['sDropDownTitle']}}" class="filter_select">
                                    <option value="" selected="selected">#
                                        select {{$aDropDown['sDropDownTitle']}}</option>
                                    @foreach($aDropDown['option'] as $aOption)
                                        <option value="{{$aOption['id']}}">{{$aOption['sOptionTitle']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div id="amenities" class="chekboxes_example nmtf">
                    <h4>amenities</h4>
                    @if(!empty($aCheckboxFilters))
                        @foreach($aCheckboxFilters as $checkboxFilter)
                            <div class="input_group">
                                <input id="checkbox1" type="checkbox" class="check_select"
                                       name="{{$checkboxFilter['sCheckboxTitle']}}" value="{{$checkboxFilter['id']}}">
                                <label for="checkbox1">{{$checkboxFilter['sCheckboxTitle']}}</label>
                            </div>
                        @endforeach
                    @endif
                </div>

                <?php
                /**
                 * <div id="" class="radio_buttons_example">
                <h4>choose between</h4>
                <input type="radio" name="rad_ex" value="Red" id="pokemonRed"><label for="pokemonRed">Red</label>
                <input type="radio" name="rad_ex" value="Blue" id="pokemonBlue"><label
                for="pokemonBlue">Blue</label>
                </div>
                 */
                ?>

                <div id="search_s">
                    <h4>Search</h4>
                    <input type="text" name="search" class="form-control" id="searchname"
                           placeholder="What are you looking for?">
                    <button name="auto" class="btn" value="">Search Button</button>
                </div>
            </div>


            <script type="text/javascript">
                $('#searchname').autocomplete({
                    source: 'autocomplete',
                    minlenght: 1,
                    autoFocus: true,
                    select: function (e, ui) {
                    }
                });
            </script>

        </form>

        <div class="columns large-9 type_list" id="fooms_inner_c">
            <div class="pre_text">
                <h3>Use the filters on this room finder page to find the right accommodation for you!</h3>
            </div>
            <div class="clearfix list_head">
                <div class="float-left" id="cnt_results_top">{!! count($aAllRooms) !!} results</div>
                <div class="float-right sc_view">
                    <div class="float-left mr-1r">
                        <select class="show-count" data-table-id="tdid4396">
                            <option value="">-- Show --</option>
                            <option value="5">Show 5</option>
                            <option value="15">Show 15</option>
                            <option value="25">Show 25</option>
                            <option value="50">Show 50</option>
                        </select>
                    </div>
                    <button id="list_v" class="active" type="button"><i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <button id="grid_v" type="button"><i class="fa fa-th" aria-hidden="true"></i></button>
                </div>
            </div>
            <div class="rooms_container row">

                @if(!empty($aAllRooms))


                    @foreach($aAllRooms as $aAllRoom => $aAllRoomVal)
                        <?php
                        $aRooms = $aAllRoomVal['oAccommodations']->toArray();
                        $aAmenitiesByRoom[] = $aAllRoomVal['allRooms'];
                        ?>
                    @endforeach

                    @foreach($aAmenitiesByRoom as $aRoom => $val)
                        <div class="room">
                            <div class="columns medium-5">
                                <div class="room_img">
                                    @if($val['rooms']['gallery_id'] > 0)
                                        @foreach(\MediaTools::getImagesByGallery($val['rooms']['gallery_id']) as $image)
                                            @if(!empty($val['rooms']['gallery_id']))
                                                <a href="{!! $image->original_filename !!}"
                                                   data-lightbox="gal" data-title="caption example">
                                                    <div class="h_el"><i class="fa fa-search" aria-hidden="true"></i>
                                                    </div>
                                                </a>
                                                <img src="{!! $image->original_filename !!}">
                                            @endif
                                        @endforeach
                                    @else
                                        <img src="https://www.keil.com/Content/images/photo_default.png">
                                    @endif
                                </div>
                            </div>

                            <div class="columns medium-7">
                                <div class="room_info">
                                    <h4 class="room_title">{{$val['rooms']['room_title']}}</h4>
                                    <div class="room_description">
                                        <p>{!! $val['rooms']['body'] !!}</p>
                                        @if(!empty($val['amenities']))
                                            <b>Amenities</b>
                                            <ul>
                                                @foreach($val['amenities'] as $oAmenities)
                                                    <li>{!! $oAmenities->sCheckboxTitle !!}</li>
                                                @endforeach

                                            </ul>
                                        @endif
                                    </div>
                                    <?php
                                    /**
                                     *<div class="btn_group">
                                    <a href="#" class="btn">BOOK NOW</a>
                                    <a href="#" class="btn">READ MORE</a>
                                    </div>
                                     */
                                    ?>
                                </div>
                            </div>
                        </div>

                    @endforeach


                @else
                    <div class="room">
                        <h3 style="color: red;">Sorry we could not be able to find rooms! </h3>
                    </div>
                @endif

                {{--@include('rooms-list')--}}

                {{$aAllRooms[0]['oAccommodations']->links()}}
            </div>
            <div class="after_text">
                <h4>After text example</h4>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                    and scrambled it to make a type specimen book. It has survived not only five centuries, but also the
                    leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s
                    with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                    publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            </div>
        </div>
    </div>
</main>

<!--FOOTER ONLY FOR THE DEMO-->
<footer>
    <img src="http://placehold.it/1920x100">
</footer>
</div>
<script type="application/javascript">
    $(".show-count").change(function () {
        getRoomsPerPage();
    });
    function getRoomsPerPage() {
        var selected_per_page = {};
        // Get values
        $(".show-count").each(function () {
            selected_per_page[$(this).attr("name")] = $(this).val();
        });
        var data = {selected_page: selected_per_page};
        $.get("accommodations", data, function (data) {
            $("#fooms_inner_c").html(data);
        });
    }
</script>
<script src="{{$pathVendor}}js/app.js"></script>
<script src="{{$pathVendor}}ajax/filter.js"></script>
