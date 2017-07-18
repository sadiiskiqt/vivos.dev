<?php

namespace %mod_namespace%\%capital_name%\Events;

/*
 * Event: %capital_name%
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Queue\SerializesModels;

class %capital_name%Event extends \Illuminate\Support\Facades\Event
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

    $events->listen('demo_event', '%mod_namespace%\%capital_name%\Events\%capital_name%Event@test');

  }

}
