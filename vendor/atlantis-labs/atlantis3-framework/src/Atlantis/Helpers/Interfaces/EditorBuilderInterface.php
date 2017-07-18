<?php

namespace Atlantis\Helpers\Interfaces;

interface EditorBuilderInterface {
  
public function build($name, $value, $attributes);

public function styles();

public function scripts();

public function js();
  
}