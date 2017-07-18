<?php

namespace Atlantis\Models;

class PatternsMasks extends Base {

  protected $table = "patterns_masks";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'pattern_id',
      'mask'
  ];
  
  public function pattern() {
    return $this->belongsTo('Pattern', 'pattern_id');
  }

}