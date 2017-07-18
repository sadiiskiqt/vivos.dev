<?php

namespace Atlantis\Models;

class Pattern extends Base {

  protected $table = "patterns";

  protected $guarded = [ 'id' ];
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name',
      'url',
      'type',
      'outputs',
      'status',
      'weight',
      'start_date',
      'end_date',
      'mobile'
  ];
  
  public function versions() {
    return $this->hasMany('\Atlantis\Models\PatternsVersions', 'pattern_id');
  }
  
  public function masks() {
     return $this->hasMany('\Atlantis\Models\PatternsMasks' , 'pattern_id');
  }
  
  public function fields() {
     return $this->hasMany('\Atlantis\Models\PatternsFields' , 'pattern_id');
  }  

}