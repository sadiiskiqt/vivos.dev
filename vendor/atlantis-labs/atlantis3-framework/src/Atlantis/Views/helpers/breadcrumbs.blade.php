@if (!$pages->isEmpty())
<ul class="breadcrumbs">
  @foreach ($pages as $page)
  @if ($page->status == 1)
  <?php $page_url = url($page->url); ?>
  <li>
    <a {!! $current_url == $page_url ? '' : 'href="' . $page_url . '"' !!}>{{ $page->name }}</a>
  </li>
  @endif
  @endforeach
</ul>
@endif