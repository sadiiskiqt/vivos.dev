<?php

namespace Atlantis\Helpers\Cache;

use Closure;

class AtlantisCache extends \Illuminate\Support\Facades\Cache {

  public static function rememberQuery($key, $aQueryParams, Closure $callback, $allwaysCache = FALSE) {
    
    $minutes = intval(ceil(config('atlantis.cache_lifetime') / 60));

    if ($minutes == 0) {
      $minutes = 60;
    }

    if (!empty($aQueryParams)) {
      $key = $key . '-' . md5(serialize($aQueryParams));
    }

    if (config('atlantis.cache') || $allwaysCache) {
      return static::$app['cache']->remember($key, $minutes, $callback);
    } else {
      return $callback();
    }
  }

  public static function clearAll() {
    return static::$app['cache']->flush();
  }

}
