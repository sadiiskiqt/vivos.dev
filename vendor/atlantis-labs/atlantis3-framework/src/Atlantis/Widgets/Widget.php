<?php

namespace Atlantis\Widgets;

class Widget {
  
  const SMALL = 3;
  const MEDIUM = 6;
  const LARGE = 9;
  const EXTRA_LARGE = 12;
     
  /**
   * 
   * @return string Widget title
   */
  public function title() {
    return NULL;
  }
  
  /**
   * 
   * @return int
   */
  public function size() {
    return self::SMALL;
  }
  
   /**
   * 
   * @return mixed
   */
  public function view() {   
    return NULL;
  }
  
  /**
   * 
   * @return string
   */
  public function description() {
    return NULL;
  }
  
}

