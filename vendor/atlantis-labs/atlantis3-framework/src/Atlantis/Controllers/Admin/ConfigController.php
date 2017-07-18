<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use Atlantis\Models\Repositories\ConfigRepository;
use Illuminate\Http\Request;
use Atlantis\Helpers\Tools;
use Illuminate\Support\Facades\Storage;

class ConfigController extends AdminController {

    public function __construct() {

        parent::__construct(self::$_ID_CONFIG);

    }

    public function getIndex() {
        //exec('chmod -R 775 storage/framework');
        $aData = array();

        if (\Session::get('info') != NULL) {
            $aData['msgInfo'] = \Session::get('info');
        }

        if (\Session::get('success') != NULL) {
            $aData['msgSuccess'] = \Session::get('success');
        }

        if (\Session::get('error') != NULL) {
            $aData['msgError'] = \Session::get('error');
        }

        $aData['site_name'] = NULL;
        $aData['include_title'] = NULL;
        $aData['domain_name'] = NULL;
        $aData['frontend_shell_view'] = NULL;
        $aData['admin_items_per_page'] = NULL;
        $aData['default_language'] = NULL;
        $aData['cache_lifetime'] = NULL;
        $aData['show_shortcut_bar'] = NULL;
        $aData['cache'] = NULL;
        $aData['allowed_max_filesize'] = NULL;
        $aData['user_media_upload'] = NULL;
        $aData['allowed_image_extensions'] = NULL;
        $aData['allowed_others_extensions'] = NULL;
        $aData['static_images'] = NULL;
        $aData['responsive_images'] = NULL;
        $aData['default_styles'] = NULL;
        $aData['default_scripts'] = NULL;
        $aData['excluded_scripts'] = NULL;
        $aData['default_404_view'] = NULL;
        $aData['default_meta_keywords'] = NULL;
        $aData['default_meta_description'] = NULL;
        $aData['amazon_s3_url'] = NULL;
        $aData['amazon_cloudfront_url'] = NULL;
        $aData['use_amazon_s3'] = NULL;
        $aData['use_amazon_cdn'] = NULL;
        $aData['delete_local_file'] = NULL;
        $aData['responsive_breakpoints'] = NULL;

        $config = ConfigRepository::getAll();

        foreach ($config as $c) {

            $key = $c->config_key;
            $value = unserialize($c->config_value);

            if ($key == 'allowed_image_extensions') {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
            } else if ($key == 'allowed_others_extensions') {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
            } else if ($key == 'static_images') {
                $value = $this->staticImagesArrayToString($value);
            } else if ($key == 'responsive_images') {
                $value = $this->responsiveImagesArrayToString($value);
            } else if ($key == 'default_styles') {
                $value = $this->stylesArrayToString($value);
            } else if ($key == 'default_scripts') {
                $value = $this->scriptsArrayToString($value);
            } else if ($key == 'excluded_scripts') {
                $value = $this->excludedScriptsArrayToString($value);
            } else if ($key == 'responsive_breakpoints') {
                $value = $this->responsiveBreakpoints($value);
            }

            $aData[$key] = $value;

            $aData['aLang'] = Tools::getThemeLanguages();

            $aData['framework_version'] = Tools::getFrameworkVersion();
        }

        return view('atlantis-admin::config', $aData);

    }

    public function postUpdate(Request $request) {

        $config = new ConfigRepository();
        $config->updateConfig($request->all());

        \Session::flash('success', 'Updated');

        return redirect()->back();

    }

    public function postSyncFiles(Request $request) {

        $type = $request->get('sync_type');
        $dirs = $request->get('dirs');

        $syncFile = new \Atlantis\Helpers\SyncFiles();

        if ($type == 'to_local') {
            $res = $syncFile->manageDirs($dirs, 's3');
            if (isset($res['error'])) {
                return response()->json($res);
            }
            $syncFile->s3ToLocal();
        } else if ($type == 'to_s3') {
            $res = $syncFile->manageDirs($dirs, 'local');
            if (isset($res['error'])) {
                return response()->json($res);
            }
            $syncFile->localToS3();
        }

        \Atlantis\Helpers\Cache\AtlantisCache::clearAll();

        return response()->json(['success' => 'Done!']);

    }

    public function postSyncFilesV2(Request $request) {

        $type = $request->get('sync_type');
        $dirs = $request->get('dirs');
        $files = json_decode($request->get('files'), TRUE);

        $syncFile = new \Atlantis\Helpers\SyncFilesV2();

        $res = NULL;

        if (!is_array($files) && empty($files)) {

            if ($type == 'to_local') {
                $res = $syncFile->manageDirs($dirs, 's3');
            } else if ($type == 'to_s3') {
                $res = $syncFile->manageDirs($dirs, 'local');
            }
        } else {
            if ($type == 'to_local') {
                $res = $syncFile->s3ToLocal($files, $dirs, $type);
            } else if ($type == 'to_s3') {
                $res = $syncFile->localToS3($files, $dirs, $type);
            }
        }

        \Atlantis\Helpers\Cache\AtlantisCache::clearAll();

        return response()->json($res);

    }

    public function postInvalidateFiles(Request $request) {

        $disk = 's3';

        $files = $request->get('files');

        $aF = array_filter(explode("\n", $files));

        $aErrors = array();

        $aDirs = array();
        $aFiles = array();

        foreach ($aF as $k => $v) {
            $v = trim($v);

            $last = substr($v, -4);

            if ($last == ':all') {
                //dir
                $dir = str_replace($last, '', $v);
                $path = implode('/', array_filter(explode('/', $dir))) . '/';

                if (!Storage::disk($disk)->has($path)) {
                    $aErrors[] = '"' . $v . '" is invalid ' . strtoupper($disk) . ' path.';
                }

                $aDirs[] = $path;
            } else {
                //file
                $path = implode('/', array_filter(explode('/', $v)));
                if (!Storage::disk($disk)->has($path)) {
                    $aErrors[] = '"' . $v . '" is invalid ' . strtoupper($disk) . ' file.';
                }
                $aFiles[] = '/' . $v;
            }
        }

        if (empty($aErrors)) {

            foreach ($aDirs as $dir) {
                $fl = Storage::disk($disk)->allFiles($dir);

                foreach ($fl as $f) {
                    $aFiles[] = '/' . $f;
                }
            }

            $aFiles = array_unique($aFiles);

            $syncFiles = new \Atlantis\Helpers\SyncFilesV2();
            $result = $syncFiles->invalidateFiles($aFiles);

            return response()->json(['success' => 'Done!']);
        } else {
            return response()->json(['error' => $aErrors]);
        }

    }

    private function staticImagesArrayToString($value) {

        if (is_array($value)) {

            $str = '';

            foreach ($value as $size_name => $sizes) {

                $fullSizeCrop = isset($value[$size_name]['fullsize']['crop']) && $value[$size_name]['fullsize']['crop'] ? 'xC' : '';
                $thumbnailCrop = isset($value[$size_name]['thumbnail']['crop']) && $value[$size_name]['thumbnail']['crop'] ? 'xC' : '';

                $str .= $size_name .
                        '/' . $value[$size_name]['fullsize']['width'] . 'x' . $value[$size_name]['fullsize']['height'] . $fullSizeCrop .
                        '/' . $value[$size_name]['thumbnail']['width'] . 'x' . $value[$size_name]['thumbnail']['height'] . $thumbnailCrop . "\n";
            }

            return $str;
        } else {
            return $value;
        }

    }

    private function responsiveImagesArrayToString($value) {

        if (is_array($value)) {

            $str = '';

            foreach ($value as $size_name => $sizes) {

                $desktopCrop = isset($value[$size_name]['desktop']['crop']) && $value[$size_name]['desktop']['crop'] ? 'xC' : '';
                $tabletCrop = isset($value[$size_name]['tablet']['crop']) && $value[$size_name]['tablet']['crop'] ? 'xC' : '';
                $phoneCrop = isset($value[$size_name]['phone']['crop']) && $value[$size_name]['phone']['crop'] ? 'xC' : '';
                $thumbnailCrop = isset($value[$size_name]['thumbnail']['crop']) && $value[$size_name]['thumbnail']['crop'] ? 'xC' : '';

                $str .= $size_name .
                        '/' . $value[$size_name]['desktop']['width'] . 'x' . $value[$size_name]['desktop']['height'] . $desktopCrop .
                        '/' . $value[$size_name]['tablet']['width'] . 'x' . $value[$size_name]['tablet']['height'] . $tabletCrop .
                        '/' . $value[$size_name]['phone']['width'] . 'x' . $value[$size_name]['phone']['height'] . $phoneCrop .
                        '/' . $value[$size_name]['thumbnail']['width'] . 'x' . $value[$size_name]['thumbnail']['height'] . $thumbnailCrop . "\n";
            }

            return $str;
        } else {
            return $value;
        }

    }

    private function responsiveBreakpoints($value) {

        if (isset($value['large']) && isset($value['medium'])) {
            return $value['large'] . '/' . $value['medium'];
        } else {
            return $value;
        }

    }

    private function stylesArrayToString($value) {

        if (is_array($value)) {

            $str = '';

            foreach ($value as $style) {
                $str .= $style . "\n";
            }

            return $str;
        } else {
            return $value;
        }

    }

    private function scriptsArrayToString($value) {

        if (is_array($value)) {

            $str = '';

            foreach ($value as $script) {
                $str .= $script . "\n";
            }

            return $str;
        } else {
            return $value;
        }

    }

    private function excludedScriptsArrayToString($value) {

        if (is_array($value)) {

            $str = '';

            foreach ($value as $script) {
                $str .= $script . "\n";
            }

            return $str;
        } else {
            return $value;
        }

    }

}
