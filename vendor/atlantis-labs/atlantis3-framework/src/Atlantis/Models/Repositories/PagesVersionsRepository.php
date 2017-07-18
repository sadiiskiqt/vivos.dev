<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\PagesVersions;
use Atlantis\Helpers\Cache\AtlantisCache;

class PagesVersionsRepository {

  public static function getVersion($version_id) {

    return PagesVersions::find($version_id);
  }

  public static function addNewVersion($data, $page_id) {

    $oVersion = PagesVersions::firstOrNew([
                'page_id' => $page_id,
                'active' => 1,
                'language' => $data['language']
    ]);

    if ($oVersion != NULL) {

      $oVersion->active = 0;
      $oVersion->update();
    }

    $data['page_id'] = $page_id;
    $data['version_id'] = self::getMaxVersion($page_id, $data['language']) + 1;
    $data['user_id'] = auth()->user()->id;
    $data['active'] = 1;

    PagesVersions::create($data);
  }

  public static function getMaxVersion($page_id, $lang) {

    $oMaxVers = PagesVersions::where('page_id', '=', $page_id)
            ->where('language', '=', $lang)
            ->orderBy('version_id', 'DESC')
            ->get();

    $vers = $oMaxVers->first();

    if ($vers != NULL) {
      return $vers->version_id;
    } else {
      return 0;
    }
  }

  public static function makeActiveVersion($page_id, $version_id, $lang) {

    $oDeactivatedVersion = PagesVersions::firstOrNew([
                'page_id' => $page_id,
                'version_id' => $version_id,
                'language' => $lang
    ]);

    if ($oDeactivatedVersion != NULL) {

      $oActiveVersion = PagesVersions::firstOrNew([
                  'page_id' => $page_id,
                  'active' => 1,
                  'language' => $lang
      ]);

      if ($oActiveVersion != NULL) {

        $oActiveVersion->active = 0;
        $oActiveVersion->update();

        $oDeactivatedVersion->active = 1;
        $oDeactivatedVersion->update();

        AtlantisCache::clearAll();
        
        return TRUE;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  public static function deleteVersion($page_id, $version_id, $lang) {

    $oVersion = PagesVersions::firstOrNew([
                'page_id' => $page_id,
                'version_id' => $version_id,
                'language' => $lang
    ]);

    if ($oVersion != NULL) {

      if ($oVersion->active == 1) {
        return FALSE;
      } else {

        $dd = $oVersion->delete();

        AtlantisCache::clearAll();
        
        if ($dd) {
          return TRUE;
        } else {
          return FALSE;
        }
      }
    }

    return FALSE;
  }
  
  public static function deleteAllVersions($page_id) {
    
    $model = PagesVersions::where('page_id', '=', $page_id)->delete();
    
  }

}
