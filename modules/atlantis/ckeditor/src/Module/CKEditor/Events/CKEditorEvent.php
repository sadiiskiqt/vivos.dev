<?php

namespace Module\CKEditor\Events;

/*
 * Event: CKEditor
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Queue\SerializesModels;

class CKEditorEvent extends \Illuminate\Support\Facades\Event
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

    $events->listen('demo_event', 'Module\CKEditor\Events\CKEditorEvent@test');

  }

}
