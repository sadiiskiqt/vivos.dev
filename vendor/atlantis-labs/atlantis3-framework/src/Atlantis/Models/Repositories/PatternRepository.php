<?php

namespace Atlantis\Models\Repositories;

use Illuminate\Support\Facades\DB;
use Atlantis\Models\Interfaces;
use Atlantis\Models\Pattern;
use Atlantis\Helpers\Tools;
use Atlantis\Models\Pattern\Text;
use Atlantis\Models\Pattern\Url;
use Atlantis\Models\Pattern\View;
use Atlantis\Helpers\RegexMatcher as Regex;
use Illuminate\Support\Facades\Validator;
use Atlantis\Helpers\Cache\AtlantisCache;
use Carbon\Carbon;

class PatternRepository implements Interfaces\IPatternInterface {

    public function validationCreate($data, $pattern_id = NULL) {

        /**
         *  Validation rules for create
         * 
         * @var array
         */
        $rules_create = [
            'name' => 'required|unique:patterns,name,' . $pattern_id,
            'weight' => 'required',
            'outputs' => 'required',
            'view' => 'required_if:type,view',
            'url' => 'required_if:type,hmvc',
            'mask' => 'required',
            //'start_date' => 'date_format:' . PageRepository::$_EXPIRATION_DATE_FORMAT_VIEW . '|required_with:end_date',
            //'end_date' => 'date_format:' . PageRepository::$_EXPIRATION_DATE_FORMAT_VIEW . '|required_with:start_date',
            'start_date' => 'date_format:' . PageRepository::$_EXPIRATION_DATE_FORMAT_VIEW,
            'end_date' => 'date_format:' . PageRepository::$_EXPIRATION_DATE_FORMAT_VIEW
        ];

        $messages = [
            'required' => trans('admin::validation.required'),
            'unique' => trans('admin::validation.unique'),
            'required_if' => trans('admin::validation.required_if'),
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
            'clone_name' => 'required|unique:patterns,name'
        ];

        $messages = [
            'required' => trans('admin::validation.required'),
            'unique' => trans('admin::validation.unique')
        ];

        $validator = Validator::make($data, $rules_create, $messages);

        return $validator;

    }

    /**
     * Create custom validation message only for add/edit patterns
     */
    public function addReplacers(\Illuminate\Validation\Validator $validator) {

        $validator->addReplacer('required', function ($message, $attribute, $rule, $parameters) {

            if ($attribute == 'name') {
                return str_replace([$attribute], 'PATTERN NAME', $message);
            } else if ($attribute == 'weight') {
                return str_replace([$attribute], 'WEIGHT', $message);
            } else if ($attribute == 'outputs') {
                return str_replace([$attribute], 'PATTERN OUTPUT', $message);
            } else if ($attribute == 'mask') {
                return str_replace([$attribute], 'URL MASK (ONE PER LANE)', $message);
            }

            return $message;
        });

        $validator->addReplacer('unique', function ($message, $attribute, $rule, $parameters) {

            if ($attribute == 'name') {
                return str_replace([$attribute], 'PATTERN NAME', $message);
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

        $validator->addReplacer('required_if', function ($message, $attribute, $rule, $parameters) {

            $attributeStrip = str_replace('_', ' ', $attribute);

            if ($attribute == 'view') {
                return str_replace([$attributeStrip, ':value', ':other'], ['PATTERN VIEW', 'View', 'Type'], $message);
            } else if ($attribute == 'url') {
                return str_replace([$attributeStrip, ':value', ':other'], ['URL', 'Resource', 'Type'], $message);
            }

            return str_replace([':other', ':value'], [$parameters[0], $parameters[1]], $message);
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

    public function createPattern($data) {

        $data = $this->fitData($data, 'create');

        /**
         * save pattern
         */
        $oPattern = Pattern::create($data);

        /**
         * save version
         */
        PatternsVersionsRepository::addNewVersion($data, $oPattern->id);

        /**
         * save masks
         */
        $aMasks = explode("\n", $data['mask']);
        $aMasks = array_filter($aMasks);

        foreach ($aMasks as $mask) {
            PatternsMasksRepository::saveMask($oPattern->id, trim($mask));
        }

        /**
         * save fields
         */
        if (isset($data['attr']) && is_array($data['attr'])) {

            foreach ($data['attr'] as $attr) {
                if (!empty($attr['name'])) {
                    PatternsFieldsRepository::saveField($oPattern->id, $attr['name'], $attr['value']);
                }
            }
        }

        /**
         * save tags
         */
        $aTags = explode(',', $data['tags']);

        foreach ($aTags as $tag) {
            TagRepository::addTag($tag, $oPattern->id, \Atlantis\Controllers\Admin\AdminController::$_ID_PATTERNS);
        }

        AtlantisCache::clearAll();

        return $oPattern->id;

    }

    public function updatePattern($id, $data) {

        //dd($id, $data);

        $data = $this->fitData($data, 'update');

        $oPattern = Pattern::find($id);

        if ($oPattern != NULL) {

            /**
             * update pattern
             */
            $oPattern->update($data);

            /**
             * add new pattern version
             */
            PatternsVersionsRepository::addNewVersion($data, $oPattern->id);

            /**
             * update masks
             */
            $aMasks = explode("\n", $data['mask']);
            $aMasks = array_filter($aMasks);

            PatternsMasksRepository::deleteByPattern($oPattern->id);

            foreach ($aMasks as $mask) {
                PatternsMasksRepository::saveMask($oPattern->id, trim($mask));
            }

            /**
             * update fields
             */
            PatternsFieldsRepository::deleteByPattern($oPattern->id);
            if (isset($data['attr']) && is_array($data['attr'])) {

                foreach ($data['attr'] as $attr) {
                    if (!empty($attr['name'])) {
                        PatternsFieldsRepository::saveField($oPattern->id, $attr['name'], $attr['value']);
                    }
                }
            }

            /**
             * update tags
             */
            $aTags = explode(',', $data['tags']);

            TagRepository::updateTags($oPattern->id, \Atlantis\Controllers\Admin\AdminController::$_ID_PATTERNS, $aTags);

            AtlantisCache::clearAll();
        }

    }

    public function fitData($data, $type) {

        if (!isset($data['mobile'])) {
            $data['mobile'] = 0;
        }

        $data['mask'] = trim($data['mask']);

        if (!empty($data['start_date'])) {

            $startDT = Carbon::createFromFormat(PageRepository::$_EXPIRATION_DATE_FORMAT_VIEW . ' H:i:s', $data['start_date'] . ' 00:00:01');

            $data['start_date'] = $startDT->toDateTimeString();
        } else {
            $data['start_date'] = NULL;
        }

        if (!empty($data['end_date'])) {

            $endDT = Carbon::createFromFormat(PageRepository::$_EXPIRATION_DATE_FORMAT_VIEW . ' H:i:s', $data['end_date'] . ' 23:59:59');

            $data['end_date'] = $endDT->toDateTimeString();
        } else {
            $data['end_date'] = NULL;
        }

        return $data;

    }

    public function clonePattern($id, $data, $user_id, $username, $lang) {

        $oPattern = Pattern::find($id);

        $oVersion = \Atlantis\Models\PatternsVersions::firstOrNew([
                    'pattern_id' => $id,
                    'active' => 1,
                    'language' => $lang
        ]);

        if ($oPattern != NULL && $oVersion->id != NULL) {

            /**
             * clone pattern
             */
            $clonedP = $oPattern->replicate();
            $clonedP->name = $data['clone_name'];
            $clonedP->save();

            /**
             * clone active version
             */
            $clonedV = $oVersion->replicate();
            $clonedV->pattern_id = $clonedP->id;
            $clonedV->version_id = 1;
            $clonedV->user_id = $user_id;
            $clonedV->save();

            /**
             * clone pattern masks
             */
            $oPatternMasks = \Atlantis\Models\PatternsMasks::where('pattern_id', '=', $oPattern->id)->get();

            foreach ($oPatternMasks as $pattM) {
                $mask = $pattM->replicate();
                $mask->pattern_id = $clonedP->id;
                $mask->save();
            }

            /**
             * clone pattern fields
             */
            $oPatternFields = \Atlantis\Models\PatternsFields::where('pattern_id', '=', $oPattern->id)->get();

            foreach ($oPatternFields as $pattF) {
                $field = $pattF->replicate();
                $field->pattern_id = $clonedP->id;
                $field->save();
            }

            /**
             * clone tags
             */
            $oTags = TagRepository::getTagsByResourceID(\Atlantis\Controllers\Admin\AdminController::$_ID_PATTERNS, $id);

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

    /**
     * Builds patterns list using inclusive and exclusive masks
     */
    public function buildPatterns($url) {

        /**
         * TODO : drop "mobile" from url when the url has one
         * 
         */
        $mobile = Tools::checkURLforMobile($url);

        $positive = AtlantisCache::rememberQuery('buildPattPos', [$url, $mobile], function() use ($url, $mobile) {

                    return DB::table('patterns_masks')
                                    ->select("patterns_masks.pattern_id", DB::raw(" IF ( '{$url}' REGEXP CONCAT('^',REPLACE(mask, ':any', '(.*)'),'$') , 1 , 0 ) AS check_value  "))
                                    ->leftJoin('patterns', 'patterns.id', '=', 'patterns_masks.pattern_id')
                                    ->where("patterns.status", "=", 1)
                                    ->where("patterns.mobile", "=", $mobile ? 1 : 0 )
                                    ->having("check_value", "!=", 0)
                                    ->orderBy("weight")
                                    ->get();
                });

        $aPositive = array();

        foreach ($positive as $p) {
            $aPositive[] = $p->pattern_id;
        }

        //dd($aPositive);

        $sNegativeRequest = "!" . $url;

        $negative = AtlantisCache::rememberQuery('buildPattNeg', [$sNegativeRequest, $mobile], function() use ($sNegativeRequest, $mobile) {
                    return DB::table('patterns_masks')
                                    ->select('patterns_masks.pattern_id')
                                    ->leftJoin('patterns', 'patterns.id', '=', 'patterns_masks.pattern_id')
                                    ->where("patterns.status", "=", 1)
                                    ->whereRaw(" '{$sNegativeRequest}'  REGEXP CONCAT('^',REPLACE(mask, ':all', '(.*)'),'$')")
                                    ->where("patterns.mobile", "=", $mobile ? 1 : 0 )
                                    ->orderBy("weight")
                                    ->get();
                });

        $aNegative = array();

        foreach ($negative as $n) {
            $aNegative[] = $n->pattern_id;
        }

        //dd($aNegative);

        return $this->processPatterns(array_diff($aPositive, $aNegative));

    }

    /*
     * 
     */

    public function processPatterns($results, $type = 'id') {

        if ($results) {

            // $sLanguage = Request::current()->param('lang');

            $list_results = Tools::arr2list($results);

            //TODO : This query can be removed and replaced with getInlinePatternsDB , just extending the select in getInlinePatternsDB , no need to query 2 times

            /*
              $stmt = DB::table("patterns")
              ->select("patterns.*", "patterns_versions.text", "patterns_versions.view", "patterns_versions.user_id", "patterns_versions.edited", "patterns_versions.language", "patterns_versions.pattern_id", "patterns_versions.version_id", "patterns_versions.active")
              ->leftJoin("patterns_versions", "patterns_versions.pattern_id", "=", "patterns.id")
              ->whereRaw("patterns.id IN ($list_results)")
              ->where("patterns.status", "=", 1)
              //->where("patterns_versions.language", "=", $lang)
              ->where("patterns_versions.active", "=", 1)
              ->orderBy("weight")
              ->get();
             * 
             */

            $stmt = $this->getInlinePatternsDB($list_results, $type);

            $aOutput = array();

            $aView = array();

            foreach ($stmt as $result) {

                //$oPattern = Pattern::where("id", "=", $result->id)->get();

                if ($this->checkExpiration($result->start_date, $result->end_date, $result->id)) {
                    
                    if (!array_key_exists($result->outputs, $aOutput)) {

                        $aOutput[$result->outputs] = '';
                    }

                    try {

                        //make the subtype models
                        switch ($result->type) {
                            case "hmvc" :
                                $submodel = new Url($result);
                                $aView[] = $submodel->init();
                                break;
                            case "text" :
                                $submodel = new Text($result);
                                $aView[] = $submodel->init();
                                break;
                            case "view" :
                                $submodel = new View($result);
                                $aView[] = $submodel->init();
                                break;
                        }

                        $aOutput[$result->outputs] .= implode("", $aView);
                        unset($aView);
                    } catch (Exception $e) {

                        print $e->getMessage();
                    }
                }
            }

            return $aOutput;
        } else {

            return FALSE;
        }

    }

    public function checkExpiration($start_date, $end_date, $id) {

        $from = strtotime($start_date);
        $to = strtotime($end_date);
        $now = Carbon::now()->timestamp;

        if ($from !== FALSE && $to !== FALSE) {

            if ($to < $now) {
                $this->changeStatus($id, '0');
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
                $this->changeStatus($id, '0');
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    public function changeStatus($id, $status) {

        $pattern = Pattern::find($id);

        $pattern->status = $status;

        $pattern->save();

        AtlantisCache::clearAll();

    }

    public static function getInlinePatternsDB($list, $type) {

        return AtlantisCache::rememberQuery('inlinePattDB', [$list, $type], function() use ($list, $type) {

                    $result = DB::table("patterns")
                            ->select("patterns.*", "patterns_versions.text", "patterns_versions.view", "patterns_versions.user_id", "patterns_versions.updated_at", "patterns_versions.language", "patterns_versions.pattern_id", "patterns_versions.version_id", "patterns_versions.active")
                            ->leftJoin("patterns_versions", "patterns_versions.pattern_id", "=", "patterns.id");


                    if ($type == "name") {
                        $result->whereRaw("patterns.name IN('$list')");
                    } elseif ($type == "id") {
                        $result->whereRaw("patterns.id IN($list)");
                    }

                    return $result->where("patterns.status", "=", 1)
                                    ->where("patterns_versions.active", "=", 1)
                                    //->where("patterns_versions.language", "=" , $lang);
                                    ->orderBy("patterns.weight")
                                    ->get();
                });

    }

    /** This works only when the system is in regex not DOM matchin mode * */
    public function inlinePatterns($page_body) {

        /** match [patternname] * */
        $aMatchesName = Regex::matchPatternName($page_body);

        /** match [patternid] * */
        $aMatchesID = Regex::matchPatternId($page_body);

        /** match [patternfunc] * */
        $aMatchesFunc = Regex::matchPatternFunc($page_body);

        if (count($aMatchesName[1])) {

            $aName = array_values($aMatchesName[1]);

            $list_aName = Tools::arr2list($aName);

            $stmtName = self::getInlinePatternsDB($list_aName, "name");

            $aTempName = array();

            foreach ($stmtName as $p) {

                $aTempName[] = $p->id;
            }

            if (count($stmtName)) {

                if ($aResultsName = $this->processPatterns($aTempName)) {

                    foreach ($aResultsName as $patt_name => $patt_value) {

                        $page_body = Regex::removePatternName($patt_name, $patt_value, $page_body);
                    }
                }
            } else {

                foreach ($aName as $patt_name) {

                    $page_body = Regex::removePatternName($patt_name, '', $page_body);
                }
            }
        }

        if (count($aMatchesID[1]) > 0) {

            $aID = array_values($aMatchesID[1]);

            $list_aID = Tools::arr2list($aID);

            $stmtID = self::getInlinePatternsDB($list_aID, "id");

            $aTempID = array(); // store patt_id

            foreach ($stmtID as $p) {
                $aTempID[] = $p->id;
            }

            if ($stmtID) {

                if ($aResultsID = $this->processPatterns($aTempID, "id")) {

                    ///get the patt id and values for replacement from the 2 arrays and merge them together

                    $aReplace = array_combine($aTempID, $aResultsID);

                    foreach ($aReplace as $patt_id => $patt_value) {

                        $page_body = Regex::removePatternId($patt_id, $patt_value, $page_body);
                    }
                }
            } else {

                foreach ($aID as $pid) {

                    $page_body = Regex::removePatternId($pid, '', $page_body);
                }
            }
        }

        if (count($aMatchesFunc[1]) > 0) {

            foreach ($aMatchesFunc[1] as $func) {

                $request = Tools::makeAppCallFromString($func);

                if (!empty($request)) {

                    $page_body = Regex::removePatternFunc($func, $request, $page_body);
                } else {

                    $page_body = Regex::removePatternFunc($func, '', $page_body);
                }
            }
        }

        return $page_body;

    }

    /**
     * Function getPerPage
     * 
     * Get patterns from page url
     * 
     * @param string $sPageUrl
     * @return object
     */
    public static function getPerPage($sPageUrl) {

        $sNegativePageName = "!" . $sPageUrl;

        $rStmtNeg = DB::table('patterns_masks');

        $negative = $rStmtNeg->where('mask', '=', $sNegativePageName)->get();

        $aNegative = array();
        foreach ($negative as $n) {
            $aNegative[] = $n->pattern_id;
        }


        $rStmt = AtlantisCache::rememberQuery('getPerPage', [$sPageUrl, $aNegative], function() use ($sPageUrl, $aNegative) {

                    return DB::table('patterns_masks')
                                    ->select('patterns_masks.*', 'patterns.*', DB::raw("IF ('{$sPageUrl}' REGEXP CONCAT('^',REPLACE(mask, ':any', '(.*)'),'$') , 1 , 0 ) AS check_value"))
                                    ->leftJoin('patterns', 'patterns.id', '=', 'patterns_masks.pattern_id')
                                    ->where('patterns.status', '!=', '5')
                                    ->whereNotIn('patterns_masks.pattern_id', $aNegative)
                                    ->having('check_value', '!=', '0')
                                    ->get();
                });




        /**
          $rStmt = DB::table('patterns_masks')
          ->select('patterns_masks.*', 'patterns.*', DB::raw("IF ('{$sPageUrl}' REGEXP CONCAT('^',REPLACE(mask, ':any', '(.*)'),'$') , 1 , 0 ) AS check_value"))
          ->leftJoin('patterns', 'patterns.id', '=', 'patterns_masks.pattern_id')
          ->where('patterns.status', '!=', '5')
          ->whereNotIn('patterns_masks.pattern_id', $aNegative)
          ->having('check_value', '!=', '0')
          ->get();
         * 
         */
        return $rStmt;

    }

    public static function getAllPerPage($sPageUrl) {

        $sNegativePageName = "!" . $sPageUrl;

        $negative = AtlantisCache::rememberQuery('getAllPerPageNeg', [$sNegativePageName], function() use ($sNegativePageName) {

                    return DB::table('patterns_masks')
                                    ->select('patterns_masks.id AS mask_id', 'patterns_masks.mask', 'patterns.*')
                                    ->leftJoin('patterns', 'patterns.id', '=', 'patterns_masks.pattern_id')
                                    ->where('patterns.status', '!=', '5')
                                    ->where('mask', '=', $sNegativePageName)
                                    ->get()->toArray();
                });

        /**
          $negative = DB::table('patterns_masks')
          ->select('patterns_masks.id AS mask_id', 'patterns_masks.mask', 'patterns.*')
          ->leftJoin('patterns', 'patterns.id', '=', 'patterns_masks.pattern_id')
          ->where('patterns.status', '!=', '5')
          ->where('mask', '=', $sNegativePageName)
          ->get();
         * 
         */
        $aNegative = array();
        foreach ($negative as $n) {
            $aNegative[] = $n->id;
        }

        $positive = AtlantisCache::rememberQuery('getAllPerPagePos', [$sPageUrl, $aNegative], function() use ($sPageUrl, $aNegative) {

                    return DB::table('patterns_masks')
                                    ->select('patterns_masks.id AS mask_id', 'patterns_masks.mask', 'patterns.*', DB::raw("IF ('{$sPageUrl}' REGEXP CONCAT('^',REPLACE(mask, ':any', '(.*)'),'$') , 1 , 0 ) AS check_value"))
                                    ->leftJoin('patterns', 'patterns.id', '=', 'patterns_masks.pattern_id')
                                    ->where('patterns.status', '!=', '5')
                                    ->whereNotIn('patterns_masks.pattern_id', $aNegative)
                                    ->having('check_value', '!=', '0')
                                    ->get()->toArray();
                });
        /**
          $positive = DB::table('patterns_masks')
          ->select('patterns_masks.id AS mask_id', 'patterns_masks.mask', 'patterns.*', DB::raw("IF ('{$sPageUrl}' REGEXP CONCAT('^',REPLACE(mask, ':any', '(.*)'),'$') , 1 , 0 ) AS check_value"))
          ->leftJoin('patterns', 'patterns.id', '=', 'patterns_masks.pattern_id')
          ->where('patterns.status', '!=', '5')
          ->whereNotIn('patterns_masks.pattern_id', $aNegative)
          ->having('check_value', '!=', '0')
          ->get();
         * 
         */
        return array_merge($negative, $positive);

    }

    public static function latestEditedPatterns($limit) {

        return Pattern::where('status', '!=', 5)
                        ->take($limit)
                        ->orderBy('updated_at', 'DESC')
                        ->get();

    }

    public static function getPatternWithActiveVersion($patt_id, $lang = NULL) {

        $model = DB::table('patterns');
        $model->select('patterns_versions.*', 'patterns.*');
        $model->leftJoin('patterns_versions', 'patterns.id', '=', 'patterns_versions.pattern_id');
        $model->where('patterns.id', '=', $patt_id);
        $model->where('patterns_versions.active', '=', 1);
        if ($lang != NULL) {
            $model->where('patterns_versions.language', '=', $lang);
        }
        return $model->first();

    }

    public static function getPatternByVersion($patt_id, $version_id, $lang) {

        return DB::table('patterns')
                        ->select('patterns_versions.*', 'patterns.*')
                        ->leftJoin('patterns_versions', 'patterns.id', '=', 'patterns_versions.pattern_id')
                        ->where('patterns.id', '=', $patt_id)
                        ->where('patterns_versions.version_id', '=', $version_id)
                        ->where('patterns_versions.language', '=', $lang)
                        ->first();

    }

    public static function deletePattern($id) {

        PatternsFieldsRepository::deleteByPattern($id);
        PatternsMasksRepository::deleteByPattern($id);
        PatternsVersionsRepository::deleteByPattern($id);
        Pattern::find($id)->delete();

        TagRepository::deleteTag(\Atlantis\Controllers\Admin\AdminController::$_ID_PATTERNS, $id);

        AtlantisCache::clearAll();

    }

    public static function search($search) {



        return DB::table('patterns')
                        ->select('patterns_versions.*', 'patterns.*')
                        ->leftJoin('patterns_versions', 'patterns.id', '=', 'patterns_versions.pattern_id')
                        ->orWhere('patterns.name', 'LIKE', '%' . $search . '%')
                        ->orWhere('patterns.url', 'LIKE', '%' . $search . '%')
                        ->orWhere('patterns_versions.text', 'LIKE', '%' . $search . '%')
                        ->having('patterns.status', '!=', 5)
                        ->having('patterns_versions.active', '=', 1)
                        ->get();

    }

    public static function deleteAllFromTrash() {

        $model = Pattern::where('status', '=', 5)->get();

        foreach ($model as $m) {
            self::deletePattern($m->id);
        }

    }

    public static function getPattern($id) {

        return Pattern::find($id);

    }

}
