<?php

namespace Atlantis\Models;

class Page extends Base {

  protected $table = "pages";
  
  protected  $guarded = [ 'id' ];
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name',
      'path',
      'url',
      'categories_id',
      'author',
      'template',
      'is_ssl',
      'status',
      'start_date',
      'end_date',
      'styles',
      'scripts',
      'user',
      'mobile_template',
      'cache',
      'preview_thumb_id',
      'protected',
      'canonical_url'
  ];
  

  /**
  protected static $rules = array( 
      'create' => array( 
        'name' => 'required|unique:pages,name',
        'url' => 'required|unique:pages,url'
        ), 
      'update' => array( 
        'url' => 'required|unique:pages,url'
      )
  );
   * 
   * 
   */
  
  public function versions() {
    return $this->hasMany('\Atlantis\Models\PagesVersions', 'page_id');
  }
  
  public function category() {
      return $this->hasOne('\Atlantis\Models\PagesCategories', 'id');
  }
  
  public function preview_image() {
    return $this->hasOne('\Atlantis\Models\Media', 'id');
  }

}