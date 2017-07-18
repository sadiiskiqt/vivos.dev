<?php

namespace Atlantis\Helpers;

use Atlantis\Models\Repositories\LockedItemsRepository;

/*
 * Helper class for lock items.
 * Lock and unlock items for edit from users.
 */

class LockedItems {

  private $item_identifier = '';

  public function __construct($item_identifier) {

    $this->item_identifier = $item_identifier;
  }

  /*
   * Lock item
   * 
   * @return BOOLEAN
   */

  public function lockItem($item_id, $item_identifier = NULL) {

    if ($item_identifier == NULL) {

      if (empty($this->item_identifier)) {
        return FALSE;
      } else {
        $item_identifier = $this->item_identifier;
      }
    }

    LockedItemsRepository::addItem($item_identifier, $item_id, \Auth::user()->id);
    
    return TRUE;
  }
  
  /*
   * Unlock item
   * 
   * @return BOOLEAN
   */
  public function unlockItem($item_id, $item_identifier = NULL) {
    
    if ($item_identifier == NULL) {

      if (empty($this->item_identifier)) {
        return FALSE;
      } else {
        $item_identifier = $this->item_identifier;
      }
    }

    LockedItemsRepository::deleteItem($item_identifier, $item_id, \Auth::user()->id);
    
    return TRUE;    
  }  
  
  /*
   * If item is locked
   * 
   * @return BOOLEAN
   */
  public function isLockedItem($item_id, $item_identifier = NULL) {
    
     if ($item_identifier == NULL) {

      if (empty($this->item_identifier)) {
        return FALSE;
      } else {
        $item_identifier = $this->item_identifier;
      }
    }
    
    return LockedItemsRepository::itemIsLockedForUser($item_identifier, $item_id, \Auth::user()->id);
  }


  /*
   * Delete all expired items 
   */
  public static function unlockAllExpiredItems($minutes) {
    LockedItemsRepository::deleteExpiredItems($minutes);
  }

}
