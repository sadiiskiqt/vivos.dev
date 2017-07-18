<?php

namespace Atlantis\Models;

class PatternsFields extends Base {

  protected $table = "patterns_fields";
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'pattern_id',
      'key',
      'value'
  ];

  public function pattern() {
    return $this->belongsTo('Pattern', 'pattern_id');
  }

}