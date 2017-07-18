<div class="helper">
  <button type="button" class="icon icon-Bulb" data-panel-toggle="tips-panel"></button>
  <div class="right-panel side-panel" id="tips-panel" data-atlantis-panel>
    <ul class="accordion" data-accordion>
      <li class="accordion-item is-active" data-accordion-item>
        <a href="#" class="accordion-title">Call from editor</a>
        <div class="accordion-content" data-tab-content>
          <p>with cache - true/false</p>
          <p>{{ '<div data-pattern-func="module:menu@buildByID-11,true">&nbsp;</div>' }}</p>
        </div>
      </li>
      <li class="accordion-item" data-accordion-item>
        <a href="#" class="accordion-title">Call from code</a>
        <div class="accordion-content" data-tab-content>
          <p>with cache - true/false</p>
          <p><?= "{!! \Module\Menus\Helpers\MenuBuilder::buildByID([11,false]) !!}" ?></p>
        </div>
      </li>
    </ul>
  </div>
</div>