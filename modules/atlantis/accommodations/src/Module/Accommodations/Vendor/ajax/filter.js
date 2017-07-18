$(document).ready(function () {


    $(".filter_select").change(function () {
        getRooms();
    });
    $(".check_select").change(function () {
        getRooms();
    });


    // Function that will make AJAX request and get available rooms
    function getRooms() {
        //Get token
        var token = $("input[name='_token']").val();

        // Get values
        var selected_filter = {};
        $(".filter_select").each(function () {
            selected_filter[$(this).attr("name")] = $(this).val();
        });

        var checkbox_filter = {};
        $(".check_select").each(function () {
            if ($(this).is(":checked")) {
                checkbox_filter[$(this).attr("name")] = $(this).val();
            }
        });

        var data = {drop: selected_filter, check: checkbox_filter, _token: token};

        $.get("getRooms", data, function (data) {
            $("#fooms_inner_c").html(data);
        });
    }





});
