<?php

namespace Atlantis\Events;

use Illuminate\Queue\SerializesModels;


class PageBodyEvent extends \Illuminate\Support\Facades\Event {

  use SerializesModels;

  public function subscribe($events) {

    $events->listen('page.body', 'Atlantis\Events\PageBodyEvent@filter', 999);
  }

  public function filter($subject) {

    $transport = \App::make('Transport');
    
    $transport->setEventValue("page.body", array("subject" => "My new replaced body", "weight" => 10, TRUE));
 
  }

}