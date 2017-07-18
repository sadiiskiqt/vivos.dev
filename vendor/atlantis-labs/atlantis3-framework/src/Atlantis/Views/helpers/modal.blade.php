<div class="reveal" id="{!! $modal_id !!}" data-reveal>
  <h1>{!! $title !!}</h1>
  <p class="lead">{!! $body !!}</p>
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  @if (empty($actionBtnHREF))
  <a data-close class="alert button">{{ $actionBtnName }}</a>
  @else
  <a href="{!! $actionBtnHREF !!}" class="alert button">{{ $actionBtnName }}</a>
  @endif
</div>