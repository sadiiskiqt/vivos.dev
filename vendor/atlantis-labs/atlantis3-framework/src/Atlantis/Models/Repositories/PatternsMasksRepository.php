<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\PatternsMasks;

class PatternsMasksRepository {

  /**
   * 
   * @param String $old_url
   * @param String $new_url
   */
  public static function changeMaskUrl($old_url, $new_url) {

    $positive = $old_url . ':any';
    $positive1 = $old_url . '/:any';

    $negative = '!' . $old_url;
    $negative1 = '!/' . $old_url;

    $model = PatternsMasks::where('mask', '=', $old_url)
            ->orWhere('mask', '=', $positive)
            ->orWhere('mask', '=', $positive1)
            ->orWhere('mask', '=', $negative)
            ->orWhere('mask', '=', $negative1)
            ->get();

    foreach ($model as $mask) {
      $oMask = PatternsMasks::find($mask->id);

      $oMask->mask = str_replace($old_url, $new_url, $oMask->mask);
      $oMask->update();
    }
  }

  public static function saveMask($patt_id, $mask) {

    $data['mask'] = $mask;
    $data['pattern_id'] = $patt_id;

    PatternsMasks::create($data);
  }

  public static function getByPattern($patt_id, $mask = NULL) {

    $model = PatternsMasks::where('pattern_id', '=', $patt_id);
    if ($mask != NULL) {
      $model->where('mask', '=', $mask);
    }
    return $model->get();
  }

  public static function deleteByPattern($patt_id) {

    PatternsMasks::where('pattern_id', '=', $patt_id)->delete();
  }

}
