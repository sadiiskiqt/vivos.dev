<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\PatternsFields;

class PatternsFieldsRepository {
  
  public static function saveField($patt_id, $key, $value) {
        
    $data['pattern_id'] = $patt_id;
    $data['key'] = $key;
    $data['value'] = $value;
    
    PatternsFields::create($data);
    
  }
  
  public static function getByPattern($patt_id) {
    
    return PatternsFields::where('pattern_id', '=', $patt_id)->get();
    
  }
  
  public static function deleteByPattern($patt_id) {    
    
    PatternsFields::where('pattern_id', '=', $patt_id)->delete();
    
  }

}
