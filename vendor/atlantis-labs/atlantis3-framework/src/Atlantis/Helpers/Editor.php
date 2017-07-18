<?php

namespace Atlantis\Helpers;

class Editor {

  private $editorClass;
  private $name;
  private $value;
  private $attributes;
  private $editor;

  public function __construct($name, $value, $attributes = array()) {

    $user = auth()->user();

    $editorClassNamespace = $user->editor;

    $this->name = $name;
    $this->value = $value;
    $this->attributes = $attributes;

    if (class_exists($editorClassNamespace)) {

      $this->editorClass = new $editorClassNamespace();

      $this->editor = $this->editorClass->build($this->name, $this->value, $this->attributes);

      $this->checkImplementedInterface($editorClassNamespace, Interfaces\EditorBuilderInterface::class);

      $this->setScripts($this->editorClass->scripts());

      $this->setStyles($this->editorClass->styles());

      $this->setJS($this->editorClass->js());
    }
  }

  public function create() {

    if ($this->editorClass != NULL) {
      return $this->editor;
    } else {
      return \Form::textarea($this->name, $this->value, $this->attributes);
    }
  }

  public static function set($name, $value, $attributes = array()) {

    $dataTable = new self($name, $value, $attributes);

    return $dataTable->create();
  }

  private function setScripts($scripts) {

    $i = 0;

    foreach ($scripts as $script) {

      Assets::registerScript($script, 20 + $i);

      $i++;
    }
  }

  private function setStyles($styles) {

    foreach ($styles as $style) {

      Assets::registerStyle($style);
    }
  }

  private function setJS($js) {

    $i = 0;

    foreach ($js as $j) {

      Assets::registerJS($j, 20 + $i);

      $i++;
    }
  }

  private function checkImplementedInterface($class, $interfaceClass) {

    $correct = FALSE;

    foreach (class_implements($class) as $implements) {

      if ($implements == $interfaceClass) {
        $correct = TRUE;
      }
    }

    if (!$correct) {
      abort(404, 'Interface ' . $interfaceClass . ' not found in class ' . $class);
    }
  }

}
