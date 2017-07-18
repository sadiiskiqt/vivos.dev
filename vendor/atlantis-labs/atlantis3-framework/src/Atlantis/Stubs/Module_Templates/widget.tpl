<?php

namespace %mod_namespace%\%capital_name%\Widgets;

/*
 * Widget: %capital_name%
 * @Atlantis CMS
 * v 1.0
 */


class %capital_name%Widget extends \Atlantis\Widgets\Widget {
  
  public function title() {
    parent::title();
    
    return 'Widget title';
  }
  
  public function description() {
    parent::description();
    
    return 'widget description';
  }


  public function size() {
    parent::size();
    
    return self::SMALL;
  }
  
  public function view() {
    parent::view();
   
    return view("%lower_name%-admin::admin/view-widget");
  }

}
