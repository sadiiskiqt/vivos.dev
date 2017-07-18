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