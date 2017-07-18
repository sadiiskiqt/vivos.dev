<?php

namespace Atlantis\Helpers;

class Assets {

  private $scripts;
  private $scripts_no_sort;
  private $styles;
  private $headTags;
  private $js;
  private $keyContent;

  public function __construct() {

    $this->scripts = new \ArrayObject(array());
    $this->scripts_no_sort = new \ArrayObject(array());
    $this->styles = new \ArrayObject(array());
    $this->headTags = new \ArrayObject(array());
    $this->js = new \ArrayObject(array());
    $this->keyContent = new \ArrayObject(array());
  }

  public function registerJSs($js) {

    $this->js->append($js);
    $this->js->uasort('\Atlantis\Helpers\Assets::cmp');
  }

  public function getRegisteredJSs() {

    $result = array();

    foreach ($this->js->getArrayCopy() as $js) {
      $result[] = $js["js"];
    }

    return $result;
  }

  public function registerHeadTags($headTag) {

    $this->headTags->append($headTag);
  }

  public function getRegisteredHeadTags() {

    return $this->headTags->getArrayCopy();
  }

  public function registerScripts($script) {

    $exist = FALSE;

    $aScripts = array_merge($this->scripts_no_sort->getArrayCopy(), $this->scripts->getArrayCopy());

    $aExcluded = empty(config('atlantis.excluded_scripts')) ? array() : config('atlantis.excluded_scripts');
  
    foreach ($aExcluded as $exc) {

      if ($script['src'] == \Html::script($exc)) {
        $exist = TRUE;
        break;
      }
    }

    if (!$exist) {
      foreach ($aScripts as $scr) {

        if ($scr['src'] == $script['src']) {
          $exist = TRUE;
          break;
        }
      }
    }
    
    if (!$exist) {

      if ($script['weight'] != NULL) {
        $this->scripts->append($script);
        $this->scripts->uasort('\Atlantis\Helpers\Assets::cmp');
      } else {
        $this->scripts_no_sort->append($script);
      }
    }
  }

  public function getRegisteredScripts() {

    $result = array();

    $aScripts = array_merge($this->scripts_no_sort->getArrayCopy(), $this->scripts->getArrayCopy());

    foreach ($aScripts as $scripts) {
      $result[] = $scripts["src"];
    }
    //dd($result);
    return $result;
  }

  public function registerStyles($style) {

    $this->styles->append($style);
  }

//method ends

  public function getRegisteredStyles() {

    return $this->styles->getArrayCopy();
  }

  public function registerKeyContent($key, $content) {

    if ($this->keyContent->offsetExists($key)) {
      $aObj = $this->keyContent->offsetGet($key);
      $aObj->append($content);
    } else {
      $aObj = new \ArrayObject(array());
      $aObj->append($content);
      $this->keyContent->offsetSet($key, $aObj);
    }
  }

  public function getRegisteredKeyContent($key) {

    if ($this->keyContent->offsetExists($key)) {
      $aObj = $this->keyContent->offsetGet($key);
      return $aObj->getArrayCopy();
    } else {
      return NULL;
    }
  }

//method ends

  public static function cmp($a, $b) {

    if ($a["weight"] == $b["weight"]) {
      return 0;
    }

    return ($a["weight"] < $b["weight"]) ? -1 : 1;
  }

  public static function registerScript($src, $weight = NULL) {

    $a = \App::make('Assets');

    $a->registerScripts(["src" => \Html::script($src), "weight" => $weight]);
  }

  public static function registerStyle($src) {

    $a = \App::make('Assets');

    $a->registerStyles(\Html::style($src));
  }

  public static function registerHeadTag($tag) {

    $a = \App::make('Assets');

    $a->registerHeadTags($tag);
  }

  public static function getHeadTags() {
    $a = \App::make('Assets');

    return $a->getRegisteredHeadTags();
  }

  public static function registerJS($js, $weight = 1) {

    $a = \App::make('Assets');

    $a->registerJSs(['js' => $js, 'weight' => $weight]);
  }

  public static function getContentWithKey($key) {
    $a = \App::make('Assets');
    return $a->getRegisteredKeyContent($key);
  }

  public static function registerContentWithKey($key, $content) {
    $a = \App::make('Assets');
    $a->registerKeyContent($key, $content);
  }

}
