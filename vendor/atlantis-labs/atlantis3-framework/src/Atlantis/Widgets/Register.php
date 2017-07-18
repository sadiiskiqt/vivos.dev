<?php

namespace Atlantis\Widgets;

class Register {

  private $widgets;

  public function __construct() {
    $this->widgets = new \ArrayObject(array());
  }

  public function setWidget($widget) {
    $this->widgets->append($widget);
  }

  public function getWidgets() {
    return $this->widgets->getArrayCopy();
  }

  public static function set($widget, $moduleSetup) {
    app('WidgetRegister')->setWidget([
        'class' => $widget,
        'moduleSetup' => $moduleSetup
    ]);
  }

  public static function get() {
    return app('WidgetRegister')->getWidgets();
  }

}
