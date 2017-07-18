<div class="pre_text">
    <h3>The results from your search are listed below!</h3>
</div>
<div class="clearfix list_head">
    <div class="float-left" id="cnt_results_top"> @if($aFilteredRooms != false) {!! count($aFilteredRooms) !!} @else 0 @endif results</div>
    <div class="float-right sc_view">
        <select class="show-count" data-table-id="tdid4396">
            <option value="">-- Show --</option>
            <option value="5">Show 5</option>
            <option value="15">Show 15</option>
            <option value="25">Show 25</option>
            <option value="50">Show 50</option>
        </select>
        <button id="list_v" class="active" type="button"><i class="fa fa-bars" aria-hidden="true"></i>
        </button>
        <button id="grid_v" type="button"><i class="fa fa-th" aria-hidden="true"></i></button>
    </div>
</div>
<div class="rooms_container row">

    @if($aFilteredRooms != false)
        @foreach($aFilteredRooms as $aFilteredRoom)
            @foreach($aFilteredRoom['accommodations'] as $room)
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
                            <h4 class="room_title">{{$room['room_title']}}</h4>
                            <div class="room_description">
                                <p>{!! $room['body'] !!}</p>
                                <b>Amenities</b>
                                <ul>
                                    @foreach($aFilteredRoom['amenities'] as $amenities)
                                        <li>{!! $amenities->sCheckboxTitle !!}</li>
                                    @endforeach
                                </ul>
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
        @endforeach
    @else
        <div class="room">
            <h3 style="color: red;">Sorry we could not be able to find rooms! </h3>
        </div>
    @endif

    {{--@include('rooms-list')--}}

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
