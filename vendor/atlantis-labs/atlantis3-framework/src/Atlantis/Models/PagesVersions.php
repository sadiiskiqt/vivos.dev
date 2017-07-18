<?php

namespace Atlantis\Models;

class PagesVersions extends Base {

  protected $table = "pages_versions";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'page_id',
      'version_id',
      'page_body',
      'excerpt',
      'related_title',
      'user_id',
      'notes',
      'mobile_body',
      'seo_title',
      'meta_description',
      'meta_keywords',
      'language',
      'active'
  ];

  public function page() {
    return $this->belongsTo('Page');
  }

}
