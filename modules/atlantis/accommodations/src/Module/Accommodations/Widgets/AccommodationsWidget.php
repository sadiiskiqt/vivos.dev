<?php

namespace Module\Accommodations\Widgets;

/*
 * Widget: Accommodations
 * @Atlantis CMS
 * v 1.0
 */


class AccommodationsWidget extends \Atlantis\Widgets\Widget {
  
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
   
    return view("accommodations-admin::admin/view-widget");
  }

}
