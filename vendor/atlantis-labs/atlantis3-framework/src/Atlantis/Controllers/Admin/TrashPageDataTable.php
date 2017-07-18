<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Models\Page;
use Illuminate\Support\Facades\DB;

class TrashPageDataTable implements \Atlantis\Helpers\Interfaces\DataTableInterface {

  public function __construct() {

    if (\Auth::check() === false) {

      return response()->json([]);
    }
  }

  public function columns() {

    return [
        [
            'title' => '<span class="fa fa-check-square-o select-all"></span>',
            'class-th' => 'checkbox no-sort',
            'class-td' => 'checkbox',
            'key' => 'checkbox',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'ID',
            'class-th' => '', // class for <th>
            'class-td' => 'id', // class for <td>
            'key' => 'id', // db column name
            'order' => [
                'sorting' => TRUE, // only one column have TRUE
                'order' => 'desc'
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
            'title' => 'Url',
            'class-th' => '',
            'class-td' => 'url',
            'key' => 'url',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Template',
            'class-th' => '',
            'class-td' => 'template-class',
            'key' => 'template',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Updated at',
            'class-th' => '',
            'class-td' => 'template-class',
            'key' => 'updated_at',
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

    return [
        'url' => 'admin/trash/bulk-action-page',
        'actions' => [
            [
                'name' => 'Delete',
                'key' => 'bulk_delete'
            ],
            [
                'name' => 'Restore',
                'key' => 'bulk_restore'
            ]
        ]
    ];
  }

  public function getData(\Illuminate\Http\Request $request) {

    $model = DB::table('pages');

    /*
     * SEARCH
     */
    if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
      $search = $request->get('search')['value'];

      $model->where('id', 'LIKE', '%' . $search . '%');
      $model->orWhere('name', 'LIKE', '%' . $search . '%');
      $model->orWhere('url', 'LIKE', '%' . $search . '%');
    }
    $model->having('status', '=', 5);
    
    /*
     * Count filtered data without LIMIT and OFFSET
     */
    $modelWhitoutOffset = $model;
    $count = count($modelWhitoutOffset->get());

    /*
     * OFFSET and LIMIT
     */
    $model->take($request->get('length'));
    $model->skip($request->get('start'));

    /*
     * ORDER BY
     */
    if (isset($request->get('order')[0]['column']) && isset($request->get('order')[0]['dir'])) {

      $column = $request->get('order')[0]['column'];
      $dir = $request->get('order')[0]['dir'];
      $columns = $request->get('columns');

      $model->orderBy($columns[$column]['data'], $dir);
    }

    /*
     * Get filtered data
     */
    $modelWithOffset = $model->get();

    $data = array();

    $lang = request()->getDefaultLocale();

    foreach ($modelWithOffset as $k => $obj) {

      $data[$k] = [
          'checkbox' => '<span data-atl-checkbox>' . \Form::checkbox($obj->id, NULL, FALSE, ['data-id' => $obj->id]) . '</span>',
          'id' => $obj->id,
          'name' => $this->nameTd($obj, $lang),
          'url' => $obj->url,
          'template' => $obj->template,
          'updated_at' => $obj->updated_at
      ];
    }

    return response()->json([
                'drow' => $request->get('draw'),
                'recordsTotal' => Page::where('status', '=', 5)->get()->count(),
                'recordsFiltered' => $count,
                'data' => $data
    ]);
  }

  private function nameTd($obj, $lang) {

    $status = '';

    if ($obj->status == 0) {
      $status = 'disabled';
    } else if ($obj->status == 1) {
      $status = 'active';
    } else if ($obj->status == 2) {
      $status = 'dev';
    } else if ($obj->status == 5) {
      $status = 'disabled';
    }

    return '<span class="tags hidden">tags</span>
                    <a class="item" data-status="' . $status . '" href="/admin/pages/edit/' . $obj->id . '">' . $obj->name . '</a>
                    <span class="actions">
                      <a data-tooltip title="Edit Page" href="/admin/pages/edit/' . $obj->id . '" class="icon icon-Edit top"></a>
                      <a data-open="restorePage' . $obj->id . '" data-tooltip title="Restore Page" class="icon icon-Export top"></a>  
                      <a data-open="deletePage' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Delete Page" class="icon icon-Delete top "></a>
                    </span>' .
            \Atlantis\Helpers\Modal::set('deletePage' . $obj->id, 'Delete Page', 'Are you sure you want to delete forever ' . $obj->name, 'Delete', '/admin/trash/delete-page/' . $obj->id) .
            \Atlantis\Helpers\Modal::set('restorePage' . $obj->id, 'Restore Page', 'Are you sure you want to restore ' . $obj->name, 'Restore', '/admin/trash/restore-page/' . $obj->id);
  }

  /**
   * Add class to <table></table> tag
   * 
   */
  public function tableClass() {
    return NULL;
  }
}
