<?php

namespace Atlantis\Models;

class Tag extends Base {

  protected $table = "tags";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['tag', 'resource_id', 'resource'];

}
