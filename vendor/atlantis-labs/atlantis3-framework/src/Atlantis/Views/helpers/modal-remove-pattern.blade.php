
<div class="reveal" id="{!! $modal_id !!}" data-reveal>
	<h1>{{ $title }}</h1>
	<p class="lead">{{ $body }}</p>
	<button class="close-button" data-close aria-label="Close modal" type="button">
		<span aria-hidden="true">&times;</span>
	</button>
	<a class="ajax-remove-pattern button alert" data-page="{!! $oPage->id !!}" data-patterntype="{!! $type !!}"  data-pattern="{{ $pattern_id }}">{{ $title }}</a>
</div>