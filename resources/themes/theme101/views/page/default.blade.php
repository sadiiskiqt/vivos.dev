@extends('atlantis::' . config('atlantis.frontend_shell_view'))

@section('headTags')
@parent
{{-- Add custom tags in <head> per template --}}
{{-- <meta name="test"> --}}
@stop

@section('tracking_header')
@parent
{{-- Add tracking header per template --}}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
@stop

@section('styles')
@parent
{{-- Add styles per template --}}
@stop

@section('js')
@parent
{{-- Add js per template --}}
{{--  <script>
  $(document).ready(function () { ... --}}
@stop

@section('content')
@parent
<div class="scene-wrap">
    <ul id="scene" class="scene">
      <li class="layer one" data-depth="0.50"><svg height="85" width="170" class="par one" data-speed-x="40" data-speed-y="15">
        <path d="M170 0 L0 0 L85 85"  fill="#e1cece" />
      </svg></li>
      <li class="layer two" data-depth="0.40"><svg height="150" width="300" class="par two" data-speed-x="27" data-speed-y="17">
        <path d="M300 0 L0 0 L150 150 Z"  fill="#ae97b8" />
      </svg></li>
      <li class="layer three" data-depth="0.50"><svg height="100" width="150" class="par three" data-speed-x="70" data-speed-y="15">
        <path d="M150 0 L0 0 L75 75 Z"  fill="#fc9d9a" stroke-width="0" />
      </svg></li>
      <li class="layer four" data-depth="0.40"><svg height="100" width="200" class="par four" data-speed-x="50" data-speed-y="15">
        <path d="M200 0 L0 0 L100 100 Z"  fill="#f9cdad" />
      </svg></li>
      <li class="layer five" data-depth="0.20"><svg height="180" width="300" class="par five" data-speed-x="25" data-speed-y="17">
        <path d="M300 0 L0 0 L150 150 Z"  fill="#69d2e7" />
      </svg></li>
      <li class="layer six" data-depth="0.50"><svg height="130" width="260" class="par six" data-speed-x="30" data-speed-y="16">
        <path d="M260 0 L0 0 L130 130 Z"  fill="#a7dbd8" />
      </svg></li>
      <li class="layer seven" data-depth="0.10"><svg height="230" width="460" class="par seven" data-speed-x="10" data-speed-y="20">
        <path d="M460 0 L0 0 L230 230 Z"  fill="#a8dba8" />
      </svg></li>
      <li class="layer eight" data-depth="0.20"><svg height="180" width="360" class="par eight" data-speed-x="12" data-speed-y="18">
        <path d="M360 0 L0 0 L180 180 Z"  fill="#6ab5aa" />
      </svg></li>
</ul>
</div>

<header class="container">
  <div class="row">
    <div class="column u-full-width">
    {!! $content !!}
  </div>
  </div>

</header>

<main class="container">
<div class="row">
  <div class="column u-full-width">
    <h4>Thank you for being part of the Atlantis Community! Please feel free to join us on our <a href="https://www.facebook.com/atlantiscms" target="_blank">Facebook</a> and Twitter <a href="https://twitter.com/atlantiscms" target="_blank">@atlantiscms</a></h4>
  </div>
</div>
</main>

<footer>
  <div class="row container">
    <div class="column u-full-width">Copyright © 2017 Atlantis CMS. Powered by <a href="http://www.atlantis-cms.com" target="_blank" style="color: #fff; text-decoration: underline;">Atlantis CMS</a> </div>
  </div>
</footer>
@stop
