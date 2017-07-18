<?php

namespace Atlantis\Models;

class Media extends Base {

  protected $table = "media";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'filename',
      'original_filename',
      'tablet_name',
      'phone_name',
      'filesize',
      'thumbnail',
      'caption',
      'credit',
      'description',
      'type',
      'alt',
      'weight',
      'css',
      'anchor_link',
      'resize'
  ];
  protected $guarded = [ 'id'];

  /**
   * Get the media's resize attribute.
   *
   * @param  string  $value
   * @return string
   */
  public function getResizeAttribute($value) {
    
    if (empty($value)) {
      return NULL;
    } else {
      return unserialize($value);
    }
  }

}
