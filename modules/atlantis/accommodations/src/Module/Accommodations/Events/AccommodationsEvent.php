<?php

namespace Module\Accommodations\Events;

/*
 * Event: Accommodations
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Queue\SerializesModels;

class AccommodationsEvent extends \Illuminate\Support\Facades\Event
{

  use SerializesModels;

  public function test()
  {

     $t = \App::make('Transport');

     $t->setEventValue('demo_event', array('name' => 'test', 'weight' => '10') );

  }

  public function handle($event)
  {

  }

  public function subscribe($events)
  {

    $events->listen('demo_event', 'Module\Accommodations\Events\AccommodationsEvent@test');

  }

}
