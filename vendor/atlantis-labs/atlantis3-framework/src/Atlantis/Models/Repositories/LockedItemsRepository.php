<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\LockedItems;

class LockedItemsRepository {

  public static function addItem($item_type, $item_id, $user_id) {

    $model = LockedItems::firstOrNew(['item_type' => $item_type, 'item_id' => $item_id, 'user_id' => $user_id]);

    if ($model->id == NULL) {
      $model->save();
    } else {
      $model->touch();
    }
  }

  public static function deleteItem($item_type, $item_id, $user_id) {

    $model = LockedItems::firstOrNew(['item_type' => $item_type, 'item_id' => $item_id, 'user_id' => $user_id]);

    if ($model->id != NULL) {
      $model->delete();
    }
  }

  public static function getAll() {

    return LockedItems::all();
  }

  public static function deleteExpiredItems($minutes) {

    $all = self::getAll();

    foreach ($all as $item) {

      $today = new \DateTime();
      $date = new \DateTime($item->updated_at);
      $date->modify('+ ' . $minutes . ' minute');
      $interval = $date->diff($today);
      $mins = (int) $interval->format("%R%i");

      if ($mins > 0) {

        LockedItems::find($item->id)->delete();
      }
    }
  }

  public static function itemIsLockedForUser($item_type, $item_id, $user_id) {

    $model = LockedItems::firstOrNew(['item_type' => $item_type, 'item_id' => $item_id]);

    if ($model->id != NULL) {

      if ($model->user_id == $user_id) {
        return FALSE;
      } else {
        return TRUE;
      }
    } else {

      return FALSE;
    }
  }

}
