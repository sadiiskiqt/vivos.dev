<?php

namespace Atlantis\Events;

use Illuminate\Queue\SerializesModels;


class PageBodyClass extends \Illuminate\Support\Facades\Event {

  use SerializesModels;

  public function subscribe($events) {

    $events->listen('page.body_class', 'Atlantis\Events\PageBodyClass@filter', 999);
  }

  public function filter($url , $template = null, $category = null, $id = null) {
    
    //fix for homepage / , not very css friendly 
    
    $subject = array();
    
    if( !is_null( $url )) {
      
        $url = $url == "/" ? "index": str_slug($url, "-");
        
        $subject[] = $url;
    }
    
    if ( !is_null($template )) {
      
        $template = str_slug( $template, "-"); 
        
        $subject[] = $template;
    }
    
    
    if ( !is_null($id )) {
        
        $id = isset($id) ? "page-id-" . $id: "";
        
        $subject[] = $id; 
    }
    
    if ( !is_null($category)) {
      
        $category = str_slug( $category, "-" );
        
        $subject[] = $category;
    }
    
    $transport = \App::make('Transport');
    
    $transport->setEventValue("page.body_class", array("subject" => implode(" " , $subject), "weight" => 10 ));
 
  }

}