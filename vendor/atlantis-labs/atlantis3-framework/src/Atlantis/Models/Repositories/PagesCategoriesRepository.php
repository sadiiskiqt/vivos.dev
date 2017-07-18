<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\PagesCategories;
use Illuminate\Support\Facades\Validator;
use Atlantis\Helpers\Cache\AtlantisCache;

class PagesCategoriesRepository {

  public function validationCreate($data, $cat_id = NULL) {

    /**
     *  Validation rules for create
     * 
     * @var array
     */
    $rules_create = [
        'category_name' => 'required|unique:pages_categories,category_name,' . $cat_id,
        'category_string' => 'required',
        //'category_view' => 'required'
    ];

    $messages = [
        'required' => trans('admin::validation.required'),
        'unique' => trans('admin::validation.unique')
    ];

    $validator = Validator::make($data, $rules_create, $messages);

    //$validator = $this->addReplacers($validator);

    return $validator;
  }
  
  public function createCategory($data) {
    
    $data = $this->fitData($data);
    
    $model = PagesCategories::create($data);
    
    AtlantisCache::clearAll();
    
    return $model->id;
    
  }
  
  public function updateCategory($id, $data) {
    
    $data = $this->fitData($data);
    
    $model = PagesCategories::find($id);
    
    if ($model != NULL) {
      AtlantisCache::clearAll();
      $model->update($data);
    }
    
  }
  
  public function fitData($data) {
    
    if (empty($data['category_action'])) {
      $data['category_action'] = NULL;
    }
    
    if (empty($data['category_view'])) {
      $data['category_view'] = NULL;
    }
    
    return $data;
  }

  public static function getAll() {

    return PagesCategories::all();
  }
  
  public static function deleteCategory($id) {
    PagesCategories::find($id)->delete();
    AtlantisCache::clearAll();
  }
  
  public static function getCategory($id) {
    return PagesCategories::find($id);
  }

}
