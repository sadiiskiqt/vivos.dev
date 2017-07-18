<?php

namespace Atlantis\Widgets;

class Builder {

  private $widgets = array();
  private $allWidgets = array();

  public function __construct() {
    if (!empty(auth()->user())) {
      $this->build();
    }
  }

  private function build() {

    $regWidgets = Register::get();
    $allowedWidgets = auth()->user()->widgets;

    $regWidgets = $this->basicOrder($regWidgets);

    $last = last($regWidgets);

    foreach ($regWidgets as $viewData) {

      $viewData['isLast'] = FALSE;

      $viewData['dashboardView'] = view('atlantis-admin::helpers/widget', $viewData);

      if (in_array($viewData['class'], $allowedWidgets)) {
        $this->widgets[] = $viewData;
      }

      $this->allWidgets[] = $viewData;
    }

    $lastWidgetKey = key(array_slice($this->widgets, -1, 1, TRUE));

    if (array_key_exists($lastWidgetKey, $this->widgets)) {

      $this->widgets[$lastWidgetKey]['isLast'] = TRUE;
    }

    $lastAllWidgetKey = key(array_slice($this->allWidgets, -1, 1, TRUE));

    if (array_key_exists($lastAllWidgetKey, $this->allWidgets)) {

      $this->allWidgets[$lastAllWidgetKey]['isLast'] = TRUE;
    }
  }

  private function basicOrder($widgets) {

    $widg = array();

    $small = array();
    $medium = array();
    $large = array();
    $extraLarge = array();

    foreach ($widgets as $widgetProp) {

      $widget = new $widgetProp['class']();

      $viewData['size'] = $widget->size();
      $viewData['title'] = $widget->title();
      $viewData['description'] = $widget->description();
      $viewData['content'] = $widget->view();
      $viewData['moduleSetup'] = $widgetProp['moduleSetup'];
      $viewData['class'] = $widgetProp['class'];

      //$widg[] = $viewData;

      switch ($viewData['size']) {
        case Widget::SMALL:
          $small[] = $viewData;
          break;
        case Widget::MEDIUM:
          $medium[] = $viewData;
          break;
        case Widget::LARGE:
          $large[] = $viewData;
          break;
        case Widget::EXTRA_LARGE:
          $extraLarge[] = $viewData;
          break;
      }
    }

    foreach ($extraLarge as $k => $xl) {
      $widg[] = $xl;
      unset($extraLarge[$k]);
    }

    foreach ($large as $lk => $l) {

      $widg[] = $l;
      unset($large[$lk]);
      $small_key_last = key(array_slice($small, -1, 1, TRUE));

      if (array_key_exists($small_key_last, $small)) {
        $widg[] = $small[$small_key_last];
        unset($small[$small_key_last]);
      }
    }

    foreach ($medium as $mk => $m) {

      $widg[] = $m;
      unset($medium[$mk]);

      $medium_key_last = key(array_slice($medium, -1, 1, TRUE));

      if (array_key_exists($medium_key_last, $medium)) {
        $widg[] = $medium[$medium_key_last];
        unset($medium[$medium_key_last]);
      } else {

        $small_key_last = key(array_slice($small, -1, 1, TRUE));

        if (array_key_exists($small_key_last, $small)) {
          $widg[] = $small[$small_key_last];
          unset($small[$small_key_last]);
        }

        $small_key_last = key(array_slice($small, -1, 1, TRUE));

        if (array_key_exists($small_key_last, $small)) {
          $widg[] = $small[$small_key_last];
          unset($small[$small_key_last]);
        }
      }
    }

    foreach ($small as $k => $s) {
      $widg[] = $s;
      unset($small[$k]);
    }



    return array_unique($widg, SORT_REGULAR);
  }

  /**
   * get available widgets for logged user
   * @return array
   */
  public function getWidgets() {
    return $this->widgets;
  }

  /**
   * get all widgets
   * @return array
   */
  public function getAllWidgets() {
    return $this->allWidgets;
  }

}
