<div class="weather-widget">
  @if (!isset($error))
  <h3>{{ $oData->name }}, {{ $oData->sys->country }}</h3>
  <h2> <img src="http://openweathermap.org/img/w/{{ $oData->weather[0]->icon }}.png"> {{ $temp }}</h2>
  {{ $oData->weather[0]->main }}, {{ $oData->weather[0]->description }}
  <p>
    <span id="date_m">get at 0000.00.00 00:00</span>
  </p>

  <table class="table table-striped table-bordered table-condensed">
    <tbody>

      <tr><td>Wind</td><td>{{ $oData->wind->speed }} m/s</td></tr>

      <tr><td>Cloudiness</td><td>{{ $oData->clouds->all }} %</td></tr>

      <tr><td>Pressure<br></td><td>{{ $oData->main->pressure }} hpa</td></tr>

      <tr><td>Humidity</td><td>{{ $oData->main->humidity }} %</td></tr>

      <tr><td>Sunrise</td><td id="sunrise">00:00</td></tr>

      <tr><td>Sunset</td><td id="sunset">00:00</td></tr>
    </tbody>
  </table>
  @else
  <div class="weahter-error">{{ $error }}</p>
    @endif
  </div>
</div>