<?php

namespace Atlantis\Models\Repositories;

use Illuminate\Support\Facades\DB;
use Atlantis\Models\Interfaces;
use Atlantis\Models\Page;
use Atlantis\Models\PagesCategories as PagesCats;
use Illuminate\Support\Facades\Validator;
use Atlantis\Models\Repositories\PagesVersionsRepository;
use Atlantis\Models\Repositories\TagRepository;
use Carbon\Carbon;
use Atlantis\Helpers\Cache\AtlantisCache;

class PageRepository implements Interfaces\IPageInterface {

    public static $_EXPIRATION_DATE_FORMAT_VIEW = 'm/d/Y';

    public function validationCreate($data, $page_id = NULL) {

        /**
         *  Validation rules for create
         * 
         * @var array
         */
        $rules_create = [
            'name' => 'required|unique:pages,name,' . $page_id,
            'url' => 'required|unique:pages,url,' . $page_id . '|valid_url',
            'canonical_url' => 'unique:pages,canonical_url,' . $page_id . '|valid_url',
            'template' => 'required',
            'path' => 'valid_path',
            //'start_date' => 'date_format:' . self::$_EXPIRATION_DATE_FORMAT_VIEW . '|required_with:end_date',
            //'end_date' => 'date_format:' . self::$_EXPIRATION_DATE_FORMAT_VIEW . '|required_with:start_date'
            'start_date' => 'date_format:' . self::$_EXPIRATION_DATE_FORMAT_VIEW,
            'end_date' => 'date_format:' . self::$_EXPIRATION_DATE_FORMAT_VIEW
        ];

        $messages = [
            'required' => trans('admin::validation.required'),
            'unique' => trans('admin::validation.unique'),
            'valid_url' => trans('admin::validation.valid_url'),
            'valid_path' => trans('admin::validation.valid_path'),
            'date_format' => trans('admin::validation.date_format'),
            'required_with' => trans('admin::validation.required_with')
        ];

        $validator = Validator::make($data, $rules_create, $messages);

        $validator = $this->addReplacers($validator);

        return $validator;

    }

    public function validationClone($data) {

        /**
         *  Validation rules for clone
         * 
         * @var array
         */
        $rules_create = [
            'clone_name' => 'required|unique:pages,name',
            'clone_url' => 'required|unique:pages,url|valid_url'
        ];

        $messages = [
            'required' => trans('admin::validation.required'),
            'unique' => trans('admin::validation.unique'),
            'valid_url' => trans('admin::validation.valid_url')
        ];

        $validator = Validator::make($data, $rules_create, $messages);

        //$validator = $this->addReplacers($validator);

        return $validator;

    }

    /**
     * Create custom validation message only for add/edit pages
     */
    public function addReplacers(\Illuminate\Validation\Validator $validator) {

        $validator->addReplacer('required', function ($message, $attribute, $rule, $parameters) {

            if ($attribute == 'url') {
                return str_replace([$attribute], 'PAGE URL', $message);
            } else if ($attribute == 'name') {
                return str_replace([$attribute], 'PAGE NAME', $message);
            } else if ($attribute == 'template') {
                return str_replace([$attribute], 'PAGE TEMPLATE', $message);
            }

            return $message;
        });

        $validator->addReplacer('unique', function ($message, $attribute, $rule, $parameters) {

            if ($attribute == 'url') {
                return str_replace([$attribute], 'PAGE URL', $message);
            } else if ($attribute == 'name') {
                return str_replace([$attribute], 'PAGE NAME', $message);
            }

            return $message;
        });

        $validator->addReplacer('valid_url', function ($message, $attribute, $rule, $parameters) {

            if ($attribute == 'url') {
                return str_replace([$attribute], 'PAGE URL', $message);
            }

            return $message;
        });

        $validator->addReplacer('date_format', function ($message, $attribute, $rule, $parameters) {

            $attributeStrip = str_replace('_', ' ', $attribute);

            if ($attribute == 'start_date') {
                return str_replace([$attributeStrip, ':format'], ['FROM', $parameters[0]], $message);
            } else if ($attribute == 'end_date') {
                return str_replace([$attributeStrip, ':format'], ['TO', $parameters[0]], $message);
            }

            return str_replace(':format', $parameters[0], $message);
        });

        $validator->addReplacer('required_with', function ($message, $attribute, $rule, $parameters) {

            $attributeStrip = str_replace('_', ' ', $attribute);

            if ($attribute == 'start_date') {
                return str_replace([$attributeStrip, ':values'], ['FROM', 'TO'], $message);
            } else if ($attribute == 'end_date') {
                return str_replace([$attributeStrip, ':values'], ['TO', 'FROM'], $message);
            }

            return str_replace(':values', $parameters[0], $message);
        });

        return $validator;

    }

    public function validUrl($attribute, $value, $parameters, $validator) {

        if ($value == '/media' || $value == 'media' || $value == 'media/') {

            return FALSE;
        }

        $match = preg_match('/\s|\\|\'|\"|\,|\.|\!|\?|\^|\<|\>|\<>|\@|\#|\$|\&|\*|\(|\)|\=|\%|\`|\;/', $value);

        if ($match) {

            return FALSE;
        }

        return TRUE;

    }

    public function validPath($attribute, $value, $parameters, $validator) {

        $oPage = Page::find($value);

        if ($oPage == NULL) {
            return TRUE;
        }

        $aPath = array_filter(explode('/', $oPage->path));

        if (in_array(request()->route('one'), $aPath)) {
            return FALSE;
        }

        return TRUE;

    }

    public function clonePage($id, $data, $user_id, $username, $lang) {

        $oPage = Page::find($id);

        $oVersion = \Atlantis\Models\PagesVersions::firstOrNew([
                    'page_id' => $id,
                    'active' => 1,
                    'language' => $lang
        ]);

        if ($oPage != NULL && $oVersion->id != NULL) {

            /**
             * clone page
             */
            $clonedP = $oPage->replicate();
            $clonedP->name = $data['clone_name'];
            $clonedP->url = $data['clone_url'];
            $clonedP->user = $user_id;
            $clonedP->author = $username;
            $clonedP->canonical_url = NULL;
            $clonedP->save();

            /**
             * clone active version
             */
            $clonedV = $oVersion->replicate();
            $clonedV->page_id = $clonedP->id;
            $clonedV->version_id = 1;
            $clonedV->user_id = $user_id;
            $clonedV->save();

            /**
             * clone pattern masks
             */
            $oPatterns = PatternRepository::getPerPage($oPage->url);

            foreach ($oPatterns as $patt) {
                if ($patt->mask != ':any') {
                    $mask = new \Atlantis\Models\PatternsMasks();
                    $mask->pattern_id = $patt->id;
                    $mask->mask = $data['clone_url'];
                    $mask->save();
                }
            }

            /**
             * clone tags
             */
            $oTags = TagRepository::getTagsByResourceID(\Atlantis\Controllers\Admin\AdminController::$_ID_PAGES, $id);

            foreach ($oTags as $oTag) {
                $cloneT = $oTag->replicate();
                $cloneT->resource_id = $clonedP->id;
                $cloneT->save();
            }

            AtlantisCache::clearAll();

            return $clonedP;
        }

        return FALSE;

    }

    public function createPage($data) {

        $data = $this->fitData($data, 'create');

        $oPage = Page::create($data);

        $oPage->path = $this->buildPath($data['path'], $oPage);

        $oPage->update();

        PagesVersionsRepository::addNewVersion($data, $oPage->id);

        $aTags = explode(',', $data['tags']);

        foreach ($aTags as $tag) {
            TagRepository::addTag($tag, $oPage->id, \Atlantis\Controllers\Admin\AdminController::$_ID_PAGES);
        }

        AtlantisCache::clearAll();

        return $oPage->id;

    }

    public function updatePage($id, $data) {

        $data = $this->fitData($data, 'update');

        $oPage = Page::find($id);

        if ($oPage != NULL) {

            $old_url = $oPage->url;

            $data['path'] = $this->buildPath($data['path'], $oPage);

            $oPage->update($data);

            PagesVersionsRepository::addNewVersion($data, $oPage->id);

            if ($old_url != $data['url']) {
                PatternsMasksRepository::changeMaskUrl($old_url, $data['url']);
            }

            $aTags = explode(',', $data['tags']);

            TagRepository::updateTags($oPage->id, \Atlantis\Controllers\Admin\AdminController::$_ID_PAGES, $aTags);

            AtlantisCache::clearAll();
        }

    }

    public function buildPath($parent_id, $oPage) {

        if ($parent_id == 0) {
            return $oPage->id . '/';
        } else {
            return $parent_id . '/' . $oPage->id . '/';
        }
        /**
          $pages = Page::where('path', 'like', $oPage->path . '%')->where('id', '!=', $oPage->id)->get();

          $path = $oPage->id . '/';

          if ($parent_id == 0) {
          $path = $oPage->id . '/';
          } else {
          $parenPage = Page::where('status', '=', 1)->where('id', '=', $parent_id)->get()->first();
          if ($parenPage == NULL) {
          $path = $oPage->id . '/';
          }

          $path = $parenPage->path . $oPage->id . '/';
          }

          foreach ($pages as $page) {
          $page->path = str_replace($oPage->path, $path, $page->path);
          $page->save();
          }

          return $path;
         * 
         */

    }

    public function fitData($data, $type) {

        $data['styles'] = trim($data['styles']);
        $data['scripts'] = trim($data['scripts']);

        if (empty($data['styles'])) {
            $data['styles'] = NULL;
        }

        if (empty($data['scripts'])) {
            $data['scripts'] = NULL;
        }

        if ($type == 'create') {

            $data['user'] = auth()->user()->id;
        }

        if (!isset($data['cache'])) {
            $data['cache'] = 0;
        }

        if (!isset($data['is_ssl'])) {
            $data['is_ssl'] = 0;
        }

        if (!isset($data['protected'])) {
            $data['protected'] = 0;
        }

        if (!empty($data['start_date'])) {

            $startDT = Carbon::createFromFormat(self::$_EXPIRATION_DATE_FORMAT_VIEW . ' H:i:s', $data['start_date'] . ' 00:00:01');

            $data['start_date'] = $startDT->toDateTimeString();
        } else {
            $data['start_date'] = NULL;
        }
        
        if (!empty($data['end_date'])) {

            $endDT = Carbon::createFromFormat(self::$_EXPIRATION_DATE_FORMAT_VIEW . ' H:i:s', $data['end_date'] . ' 23:59:59');

            $data['end_date'] = $endDT->toDateTimeString();
        } else {            
            $data['end_date'] = NULL;
        }

        return $data;

    }

    /**
     * Get All Pages
     */
    public function getAllPages() {

        return Models\Page::all();

    }

    /**
     * Main method to discover pages
     */
    public function findPageByURL($url, $lang) {


        $page = AtlantisCache::rememberQuery('findPageByURL', [$url, $lang], function() use ($url, $lang) {

                    return DB::table('pages')
                                    ->select('pages.*', 'pages_versions.notes', 'pages_versions.page_body', 'pages_versions.mobile_body', 'pages_versions.meta_description', 'pages_versions.meta_keywords', 'pages_versions.seo_title', 'pages_categories.id as category_id', 'pages_categories.category_name', DB::raw(" IF ('{$url}' REGEXP CONCAT('^',REPLACE(url, ':any', '(.*)'),'$') , 1 , 0 ) AS check_value "))
                                    ->leftJoin('pages_versions', 'pages.id', '=', 'pages_versions.page_id')
                                    ->leftJoin('pages_categories', 'pages.categories_id', '=', 'pages_categories.id')
                                    ->where('pages_versions.active', '=', 1)
                                    ->where('pages_versions.language', '=', $lang)
                                    ->having('check_value', '!=', 0)
                                    ->havingRaw('pages.status IN  (1,2) ')
                                    ->first();
                });

        /**
          $page = DB::table('pages')
          ->select('pages.*', 'pages_versions.page_body', 'pages_versions.mobile_body', 'pages_versions.meta_description', 'pages_versions.meta_keywords', 'pages_versions.seo_title', 'pages_categories.id as category_id', 'pages_categories.category_name', DB::raw(" IF ('{$url}' REGEXP CONCAT('^',REPLACE(url, ':any', '(.*)'),'$') , 1 , 0 ) AS check_value "))
          ->leftJoin('pages_versions', 'pages.id', '=', 'pages_versions.page_id')
          ->leftJoin('pages_categories', 'pages.categories_id', '=', 'pages_categories.id')
          ->where('pages_versions.active', '=', 1)
          ->where('pages_versions.language', '=', $lang)
          ->having('check_value', '!=', 0)
          ->havingRaw('pages.status IN  (1,2) ')
          ->first();
         * 
         */
        if ($page) {

            if (!$this->checkExpiration($page)) {                
                return false;
            } else {                
                return $page;
            }
        }

    }

    public static function buildParents($nPageID, $sPath) {

        $aEx = preg_split("/\//", $sPath, 0, PREG_SPLIT_NO_EMPTY);

        $key = array_search($nPageID, $aEx);

        unset($aEx[$key]);

        if (count($aEx) > 0) {

            $sImplode = implode("/", $aEx);

            return $sImplode . "/";
        } else {

            return false;
        }

    }

    public function checkExpiration($page) {

        /**
         * Home page and 404 page cannot be expired 
         */        
        if ($page->url == '/' || $page->url == '404') {
            return TRUE;
        }
        
        $from = strtotime($page->start_date);
        $to = strtotime($page->end_date);
        $now = Carbon::now()->timestamp;

        if ($from !== FALSE && $to !== FALSE) {

            if ($to < $now) {
                //this page has expired and we need to change its status
                $this->changeStatus($page->id, 0);
            }

            if ($from < $now && $to > $now) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else if ($from !== FALSE && $to === FALSE) {

            if ($from < $now) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else if ($from === FALSE && $to !== FALSE) {

            if ($to < $now) {
                //this page has expired and we need to change its status
                $this->changeStatus($page->id, 0);
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }



        /**
         * Home page and 404 page cannot be expired 
         */
        if (!is_null($page->start_date) && !is_null($page->end_date) && $page->url != "/" && $page->url != "404") {

            $date_current = new \DateTime('now');

            if ($page->start_date < $date_current && $page->end_date > $date_current) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }

    }

    public function changeStatus($id, $status) {

        $model = Page::find($id);

        $model->status = $status;

        $model->save();

        AtlantisCache::clearAll();

    }

    public function getPageCategory($cat_id) {

        return PagesCats::find($cat_id);

    }

    public function getSiteMapPages() {

        $model = new Page();

        return $model->where("url", "NOT LIKE", "%:any%")
                        ->whereNotIn("status", [ 0, 2, 5])
                        ->where("url", '!=', '404')
                        ->get();

    }

    public static function gellAll() {

        return Page::all();

    }

    public static function getAllActive()
    {
        return Page::where("status", 1)->get();
    }

    public static function getPageWithActiveVersion($page_id, $lang = NULL) {

        $model = DB::table('pages');
        $model->select('pages_versions.*', 'pages.*');
        $model->leftJoin('pages_versions', 'pages.id', '=', 'pages_versions.page_id');
        $model->where('pages.id', '=', $page_id);
        $model->where('pages_versions.active', '=', 1);
        if ($lang != NULL) {
            $model->where('pages_versions.language', '=', $lang);
        }
        return $model->first();

    }

    public static function getPageByVersion($page_id, $version_id, $lang) {

        return DB::table('pages')
                        ->select('pages_versions.*', 'pages.*')
                        ->leftJoin('pages_versions', 'pages.id', '=', 'pages_versions.page_id')
                        ->where('pages.id', '=', $page_id)
                        ->where('pages_versions.version_id', '=', $version_id)
                        ->where('pages_versions.language', '=', $lang)
                        ->first();

    }

    public static function getPage($id) {

        return Page::find($id);

    }

    public static function deletePage($id) {

        PagesVersionsRepository::deleteAllVersions($id);

        Page::find($id)->delete();

        TagRepository::deleteTag(\Atlantis\Controllers\Admin\AdminController::$_ID_PAGES, $id);

        AtlantisCache::clearAll();

    }

    public static function latestEdited($limit) {

        return Page::where('status', '!=', 5)
                        ->take($limit)
                        ->orderBy('updated_at', 'DESC')
                        ->get();

    }

    public static function search($search) {

        return DB::table('pages')
                        ->select('pages_versions.*', 'pages.*')
                        ->leftJoin('pages_versions', 'pages.id', '=', 'pages_versions.page_id')
                        ->orWhere('pages.name', 'LIKE', '%' . $search . '%')
                        ->orWhere('pages.url', 'LIKE', '%' . $search . '%')
                        ->orWhere('pages_versions.page_body', 'LIKE', '%' . $search . '%')
                        ->having('pages.status', '!=', 5)
                        ->having('pages_versions.active', '=', 1)
                        ->get();

    }

    public static function searchInSite($search) {

        return DB::table('pages')
                        ->select('pages_versions.*', 'pages.*')
                        ->leftJoin('pages_versions', 'pages.id', '=', 'pages_versions.page_id')
                        ->orWhere('pages.name', 'LIKE', '%' . $search . '%')
                        ->orWhere('pages.url', 'LIKE', '%' . $search . '%')
                        ->orWhere('pages_versions.page_body', 'LIKE', '%' . $search . '%')
                        ->having('pages.status', '=', 1)
                        ->having('pages_versions.active', '=', 1)
                        ->get();

    }

    public static function deleteAllFromTrash() {

        $model = Page::where('status', '=', 5)->get();

        foreach ($model as $m) {
            self::deletePage($m->id);
        }

    }

    public static function soundex($url) {

        $oPages = DB::table('pages')
                ->select('pages_versions.*', 'pages.*')
                ->leftJoin('pages_versions', 'pages.id', '=', 'pages_versions.page_id')
                ->where('pages.status', '=', '1')
                ->where("pages_versions.active", "=", 1)
                ->get();

        $aPages = array();

        foreach ($oPages as $k => $page) {

            $stripURL = str_replace(':any', '', $page->url);

            $levURL = levenshtein($url, $stripURL);

            $lenURL = round(strlen($stripURL) / 3);

            if ($levURL <= $lenURL) {

                $aPages[$k]['url'] = $stripURL;
                $aPages[$k]['name'] = $page->name;
            }

            $stripSEO = strtolower($page->seo_title);

            $levSEO = levenshtein($url, $stripSEO);

            $lenSEO = round(strlen($stripSEO) / 3);

            if ($levSEO <= $lenSEO) {

                $aPages[$k]['url'] = $stripURL;
                $aPages[$k]['name'] = $page->name;
            }
        }

        if (count($aPages) == 0) {

            $oPages = DB::table('pages')
                    ->select('pages_versions.*', 'pages.*')
                    ->leftJoin('pages_versions', 'pages.id', '=', 'pages_versions.page_id')
                    ->where('pages.url', 'LIKE', '%' . $url . '%')
                    ->orWhere('pages_versions.seo_title', 'LIKE', '%' . $url . '%')
                    ->having('pages.status', '=', '1')
                    ->having("pages_versions.active", "=", 1)
                    ->get();

            foreach ($oPages as $k => $page) {

                $stripURL = str_replace(':any', '', $page->url);

                $aPages[$k]['url'] = $stripURL;
                $aPages[$k]['name'] = $page->name;
            }
        }

        return $aPages;

    }

    public static function getRelatedById($page_id, $random = FALSE, $limit = NULL) {

        $model = DB::table('pages')
                ->select('pages_versions.*', 'pages.*')
                ->leftJoin('pages_versions', 'pages.id', '=', 'pages_versions.page_id')
                ->where('pages.categories_id', '=', function($query) use ($page_id) {
                    $query->select('categories_id')
                    ->from('pages')
                    ->where('id', '=', $page_id);
                })
                ->where('pages.status', '=', 1)
                ->where('pages.id', '!=', $page_id)
                ->where('pages_versions.active', '=', 1);

        if ($random) {
            $model->orderByRaw("RAND()");
        }
        $model->take($limit);


        return $model->get();

    }

    public static function getRelatedByTag($tags, $excluded_id = 0, $random = FALSE, $limit = NULL) {

        $model = DB::table('pages')
                ->select('pages_versions.*', 'pages.*')
                ->leftJoin('pages_versions', 'pages.id', '=', 'pages_versions.page_id')
                ->whereIn('pages.id', function($query) use ($tags) {
                    $query->select('resource_id')
                    ->from('tags')
                    ->whereIn('tag', $tags)
                    ->where('resource', '=', \Atlantis\Controllers\Admin\AdminController::$_ID_PAGES);
                })
                ->where('pages.status', '=', 1)
                ->where('pages.id', '!=', $excluded_id)
                ->where('pages_versions.active', '=', 1);

        if ($random) {
            $model->orderByRaw("RAND()");
        }
        $model->take($limit);

        return $model->get();

    }

    /**
     * 
     * @param string $path
     * @return \Illuminate\Support\Collection
     */
    public static function getPagesByPath($path) {

        $aPath = array_filter(explode('/', $path));

        $sPath = implode(',', $aPath);

        //return Page::whereIn('id', $aPath)->get();
        if (empty($sPath)) {
            return collect();
        } else {

            return AtlantisCache::rememberQuery('getPagesByPath', [$sPath], function() use ($sPath) {

                        return collect(DB::select(DB::raw("select * from pages where id in ($sPath) order by find_in_set(id, '$sPath') DESC")));
                    });
        }

    }

}
