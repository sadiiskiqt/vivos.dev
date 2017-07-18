<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Models\Repositories\ModulesRepository;
use Atlantis\Helpers\Modules\Updater;

class ModulesRepositoryDataTable implements \Atlantis\Helpers\Interfaces\DataTableInterface {

  public function __construct() {

    if (\Auth::check() === false) {

      return response()->json([]);
    }
  }

  public function columns() {

    return [
        [
            'title' => 'ID',
            'class-th' => '', // class for <th>
            'class-td' => 'id', // class for <td>
            'key' => 'id', // db column name
            'order' => [
                'sorting' => TRUE, // only one column have TRUE
                'order' => 'DESC'
            ]
        ],
        [
            'title' => 'Name',
            'class-th' => '',
            'class-td' => 'name',
            'key' => 'name',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Namespace',
            'class-th' => '',
            'class-td' => 'namespace',
            'key' => 'namespace',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Description',
            'class-th' => '',
            'class-td' => 'description',
            'key' => 'description',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Status',
            'class-th' => '',
            'class-td' => 'status',
            'key' => 'status',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Version',
            'class-th' => '',
            'class-td' => 'template-class',
            'key' => 'version',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Author',
            'class-th' => '',
            'class-td' => 'template-class',
            'key' => 'author',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ]
    ];
  }

  /**
   * Fill array or return empty.
   * 
   * @return array
   */
  public function bulkActions() {

    return [];
  }

  public function getData(\Illuminate\Http\Request $request) {

    $client = new \GuzzleHttp\Client();

    $formData = $request->all();
    $formData['modules_keys'] = serialize(config('atlantis.modules_keys'));

    try {
      $res = $client->request('POST', 'http://modules.atlantis-cms.com/all-modules', [
          'form_params' => $formData
      ]);

      $result = json_decode($res->getBody()->getContents(), TRUE);

      $data = array();

      $oModules = ModulesRepository::getAllModules();

      foreach ($result['data'] as $key => $val) {

        $status = $this->getModuleStatus($oModules, $val['path'], $val['namespace']);

        if ($status == Updater::$_STATUS_ACTIVE) {
          $name = $this->nameTd($val);
        } else {
          $name = $val['name'];
        }

        $data[$key]['id'] = $val['id'];
        $data[$key]['name'] = $name;
        $data[$key]['namespace'] = $val['namespace'];
        $data[$key]['description'] = $val['description'];
        $data[$key]['status'] = $status;
        $data[$key]['version'] = $val['version'];
        $data[$key]['author'] = $val['author'];
      }
      $result['data'] = $data;

      return $result;
    } catch (\Exception $e) {
      //return $e;
      return response()->json([
                  'drow' => $request->get('draw'),
                  'recordsTotal' => 0,
                  'recordsFiltered' => 0,
                  'data' => array()
      ]);
    }
  }

  private function nameTd($obj) {

    return '<span class="tags hidden">tags</span>
                    <a class="item" href="#">' . $obj['name'] . '</a>
                    <span class="actions">
                        <a data-open="installModal-' . $obj['id'] . '" data-tooltip title="Download and Install" class="icon icon-DownloadCloud top"></a>
                    </span>' . \Atlantis\Helpers\Modal::downloadAndInstall('installModal-' . $obj['id'], $obj);
  }

  private function getModuleStatus($oModules, $path, $namespace) {

    $installed = NULL;
    $downloaded = NULL;

    foreach ($oModules as $module) {

      if ($namespace == $module->namespace && $module->active == 1) {
        $installed = Updater::$_STATUS_INSTALLED;
      }
    }

    $modulePath = base_path(config('atlantis.modules_dir') . $path);

    $modulePath = str_replace('//', '/', $modulePath);

    if (is_dir($modulePath)) {
      $downloaded = Updater::$_STATUS_DOWNLOADED;
    }

    if ($installed != NULL) {
      return $installed;
    }

    if ($downloaded != NULL) {
      return $downloaded;
    }

    return Updater::$_STATUS_ACTIVE;
  }

  /**
   * Add class to <table></table> tag
   * 
   */
  public function tableClass() {
    return NULL;
  }

}
