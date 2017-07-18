<?php

namespace Module\GoogleAnalytics\Events;

/*
 * Event: GoogleAnalytics
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Queue\SerializesModels;

class GoogleAnalyticsEvent extends \Illuminate\Support\Facades\Event {

  use SerializesModels;

  public function trackingCode() {

    $t = \App::make('Transport');
    
    $t->setEventValue("page.tracking_header", array("name" => \Module\GoogleAnalytics\Controllers\GoogleAnalyticsController::getTrackingCode(), "weight" => 10));
  }

  public function subscribe($events) {

    $events->listen('page.tracking_header', 'Module\GoogleAnalytics\Events\GoogleAnalyticsEvent@trackingCode');
  }

}
