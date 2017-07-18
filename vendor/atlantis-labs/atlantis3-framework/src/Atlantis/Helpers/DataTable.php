<?php

namespace Atlantis\Helpers;

class DataTable {

  private $dataTableClass;
  private $dataTableClassNamespace;
  private $postParams = array();
  private $dataTableScript;

  public function __construct($dataTableClassNamespace, $postParams = array(), $dataTableScript = 'data-table-script') {

    $this->dataTableClassNamespace = $dataTableClassNamespace;

    $this->dataTableClass = new $dataTableClassNamespace();

    $this->postParams = $postParams;
    
    $this->dataTableScript = $dataTableScript;

    Assets::registerScript('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/DataTables/media/js/jquery.dataTables.min.js');
  }

  public function create() {

    $aData = array();

    $aBulkActions = $this->dataTableClass->bulkActions();

    $aData['aBulkActions'] = array();

    if (!empty($aBulkActions)) {
      $aData['bulk_action_url'] = $aBulkActions['url'];
      $aData['aBulkActions'] = array_prepend($aBulkActions['actions'], ['name' => 'Bulk actions', 'key' => 'bulk_none']);
    }

    $columns = $this->dataTableClass->columns();
    //var_dump(is_callable($this->dataTableClass->columns()));
    $aData['columns'] = $columns;
    
    $tableClass = $this->dataTableClass->tableClass();
    
    if ($tableClass != NULL) {
      $aData['tableClass'] = $tableClass;
    } else {
      $aData['tableClass'] = '';
    }

    $aData['url'] = '/datatable-proccessing/getdata';

    $aData['namespaceClass'] = rawurlencode($this->dataTableClassNamespace);

    $id = rand(0, 9999);

    $aData['table_id'] = 'tdid' . $id;

    $aData['postParams'] = $this->postParams;

    $admin_items_per_page = intval(config('atlantis.admin_items_per_page'));
    
    if ($admin_items_per_page <= 0) {
      $admin_items_per_page = 25;
    }
    
    $aData['lengthMenu'] = $this->lengthMenu($admin_items_per_page);

    $aData['admin_items_per_page'] = $admin_items_per_page;

    Assets::registerJS(view('atlantis-admin::helpers/' . $this->dataTableScript, $aData));

    return view('atlantis-admin::helpers/data-table', $aData);
  }

  private function lengthMenu($admin_items_per_page) {

    $aItems = [25, 50, 100];

    array_push($aItems, $admin_items_per_page);

    $aItems = array_unique($aItems);

    foreach ($aItems as $item) {
      $a[$item] = 'Show ' . $item;
    }

    ksort($a);

    return $a;
  }

  public static function set($dataTableClassNamespace, $postParams = array(), $dataTableScript = 'data-table-script') {

    $dataTable = new self($dataTableClassNamespace, $postParams, $dataTableScript);

    return $dataTable->create();
  }

}
