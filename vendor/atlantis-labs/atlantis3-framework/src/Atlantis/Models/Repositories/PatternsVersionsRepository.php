<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\PatternsVersions;
use Atlantis\Helpers\Cache\AtlantisCache;

class PatternsVersionsRepository {

  public static function getVersion($version_id) {

    return PatternsVersions::find($version_id);
  }

  public static function addNewVersion($data, $patt_id) {

    $oVersion = PatternsVersions::firstOrNew([
                'pattern_id' => $patt_id,
                'active' => 1,
                'language' => $data['language']
    ]);

    if ($oVersion != NULL) {

      $oVersion->active = 0;
      $oVersion->update();
    }

    $data['pattern_id'] = $patt_id;
    $data['version_id'] = self::getMaxVersion($patt_id, $data['language']) + 1;
    $data['user_id'] = auth()->user()->id;
    $data['active'] = 1;

    PatternsVersions::create($data);
  }

  public static function getMaxVersion($patt_id, $lang) {

    $oMaxVers = PatternsVersions::where('pattern_id', '=', $patt_id)
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

  public static function makeActiveVersion($patt_id, $version_id, $lang) {

    $oDeactivatedVersion = PatternsVersions::firstOrNew([
                'pattern_id' => $patt_id,
                'version_id' => $version_id,
                'language' => $lang
    ]);

    if ($oDeactivatedVersion != NULL) {

      $oActiveVersion = PatternsVersions::firstOrNew([
                  'pattern_id' => $patt_id,
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

  public static function deleteVersion($patt_id, $version_id, $lang) {

    $oVersion = PatternsVersions::firstOrNew([
                'pattern_id' => $patt_id,
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

  public static function deleteByPattern($patt_id) {
    PatternsVersions::where('pattern_id', '=', $patt_id)->delete();
  }
  
}
