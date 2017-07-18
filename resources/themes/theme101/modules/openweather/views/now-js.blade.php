<script type="text/javascript">
    var dt = new Date({{ $oData->dt }} * 1000);
    var hr = dt.getHours();
    if (hr < 10)
            hr = '0' + hr;
    var mn = dt.getMinutes();
    if (mn < 10)
            mn = '0' + mn;
    var mon = dt.getMonth() + 1;
    if (mon < 10)
            mon = '0' + mon;
    var day = dt.getDate();
    if (day < 10)
            day = '0' + day;
    var year = dt.getFullYear();
    $("#date_m").html('get at ' + year + '.' + mon + '.' + day + ' ' + hr + ':' + mn);
    var dt = new Date({{ $oData->sys->sunrise }} * 1000);
    var hr = dt.getHours();
    if (hr < 10)
            hr = '0' + hr;
    var mn = dt.getMinutes();
    if (mn < 10)
            mn = '0' + mn;
    $("#sunrise").html(hr + ':' + mn);
    var dt = new Date({{ $oData->sys->sunset }} * 1000);
    var hr = dt.getHours();
    if (hr < 10)
            hr = '0' + hr;
    var mn = dt.getMinutes();
    if (mn < 10)
            mn = '0' + mn;
    $("#sunset").html(hr + ':' + mn);
  </script>
 