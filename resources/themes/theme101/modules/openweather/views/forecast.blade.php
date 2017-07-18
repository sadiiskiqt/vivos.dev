<div class="weather-widget">
  @if (!isset($error))
  <table class="table table-striped table-bordered table-condensed">
    <tbody>
      @foreach($aData as $date => $data)

      @if (!empty($data['day']))
      <tr style="background-color:#ffffff">
        <td>{{ $date }} - Day</td>
        <td><img alt="light rain" src="http://openweathermap.org/img/w/{{ $data['day']['weather_icon'] }}.png"></td>
        <td>{{ $data['day']['main_temp'] }}</td>
        <td>Wind: {{ $data['day']['wind_speed'] }} m/s</td>
        <td>Cloudiness: {{ $data['day']['clouds_all'] }} %</td>
        <td>Pressure: {{ $data['day']['main_pressure'] }} hpa</td>
        <td>Humidity: {{ $data['day']['main_humidity'] }} %</td>
      </tr>
      @endif

      @if (!empty($data['night']))
      <tr style="background-color:#eeeeee">
        <td>{{ $date }} - Night</td>
        <td><img alt="light rain" src="http://openweathermap.org/img/w/{{ $data['night']['weather_icon'] }}.png"></td>
        <td>{{ $data['night']['main_temp'] }}</td>
        <td>Wind: {{ $data['night']['wind_speed'] }} m/s</td>
        <td>Cloudiness: {{ $data['night']['clouds_all'] }} %</td>
        <td>Pressure: {{ $data['night']['main_pressure'] }} hpa</td>
        <td>Humidity: {{ $data['night']['main_humidity'] }} %</td>
      </tr>
      @endif

      @endforeach

    </tbody>
  </table>
  @else
  <div class="weahter-error">{{ $error }}</p>
    @endif
  </div>
</div>