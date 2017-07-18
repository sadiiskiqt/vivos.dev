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