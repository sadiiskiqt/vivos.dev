<?php

namespace Atlantis\Models;

class PagesCategories extends Base {

  protected $table = "pages_categories";
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'category_name',
      'category_action',
      'category_string',
      'category_view',
      'category_url'
  ];
  
  public function page() {
    return $this->belongsTo('Page');
  }  

}