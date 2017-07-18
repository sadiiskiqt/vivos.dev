@include('accommodations::app.header')

<!-- MAIN PART -->
<main>
    <div class="row rooms_filter_main">
        @include('accommodations::filter.filter')

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
                                    <a href="offer/1/ime na ofertata"></a>
                                    <img src="https://www.keil.com/Content/images/photo_default.png">
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
@include('accommodations::app.footer')

