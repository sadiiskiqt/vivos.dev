<div class="helper">
  <button type="button" class="icon icon-Bulb" data-panel-toggle="tips-panel"></button>
  <div class="right-panel side-panel" id="tips-panel" data-atlantis-panel>
    <ul class="accordion" data-accordion>
      <li class="accordion-item is-active" data-accordion-item>
        <a href="#" class="accordion-title">Call from code</a>
        <div class="accordion-content" data-tab-content>
          <code><?= htmlspecialchars('@foreach (\MediaTools::getImagesByGallery(10) as $image)
         <label>{{ $image->name }}
          <img src="{!! $image->original_filename !!}" alt="{{ $image->alt }}">
          <img src="{!! $image->tablet_name !!}" alt="{{ $image->alt }}">
          <img src="{!! $image->phone_name !!}" alt="{{ $image->alt }}">
          <img src="{!! $image->thumbnail !!}" alt="{{ $image->alt }}">
         </label>
         @endforeach') ?></code>
        </div>
      </li>
      <li class="accordion-item" data-accordion-item>
        <a href="#" class="accordion-title">Gallery ex 1</a>
        <div class="accordion-content" data-tab-content>
          Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic, accusantium, laudantium? Veniam a officiis, consequatur. Voluptatibus, consectetur, nam temporibus in fugiat assumenda distinctio vitae modi architecto beatae provident voluptates magnam.
        </div>
      </li>
    </ul>
  </div>
</div>