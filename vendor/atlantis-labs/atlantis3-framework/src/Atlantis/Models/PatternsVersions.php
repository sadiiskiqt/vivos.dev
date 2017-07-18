<?php

namespace Atlantis\Models;

class PatternsVersions extends Base {

  protected $table = "patterns_versions";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'text',
      'view',
      'user_id',
      'language',
      'pattern_id',
      'version_id',
      'active'
  ];
  
  public function page() {
    return $this->belongsTo('Pattern', 'pattern_id');
  }

}