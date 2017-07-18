<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Models\PatternsVersions;
use Illuminate\Support\Facades\DB;

class PatternVersionsDataTable implements \Atlantis\Helpers\Interfaces\DataTableInterface {

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
            'title' => 'Version',
            'class-th' => '', // class for <th>
            'class-td' => 'name', // class for <td>
            'key' => 'version_id', // db column name
            'order' => [
                'sorting' => TRUE, // only one column have TRUE
                'order' => 'desc'
            ]
        ],
        [
            'title' => 'Updated at',
            'class-th' => '', // class for <th>
            'class-td' => '', // class for <td>
            'key' => 'updated_at', // identifier
            'order' => [
                'sorting' => FALSE, // only one column have TRUE
                'order' => 'desc'
            ]
        ],
        [
            'title' => 'By',
            'class-th' => '', // class for <th>
            'class-td' => '', // class for <td>
            'key' => 'user_id', // identifier
            'order' => [
                'sorting' => FALSE, // only one column have TRUE
                'order' => 'desc'
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
        'url' => 'admin/patterns/bulk-action-versions',
        'actions' => [
            [
                'name' => 'Delete',
                'key' => 'bulk_delete'
            ]
        ]
    ];
  }

  public function getData(\Illuminate\Http\Request $request) {

    $model = DB::table('patterns_versions');
    $model->leftJoin('users', 'patterns_versions.user_id', '=', 'users.id');
    $model->select('patterns_versions.*', 'users.name AS username');
    

    /*
     * SEARCH
     */
    if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
      $search = $request->get('search')['value'];

      $model->where('patterns_versions.version_id', 'LIKE', '%' . $search . '%');
      $model->orWhere('patterns_versions.updated_at', 'LIKE', '%' . $search . '%');
      $model->orWhere('users.name', 'LIKE', '%' . $search . '%');
    }

    $model->having('patterns_versions.pattern_id', '=', $request->get('pattern_id'));
    $model->having('patterns_versions.language', '=', $request->get('lang'));
    
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

    foreach ($modelWithOffset as $k => $obj) {

      $data[$k] = [
          'checkbox' => '<span data-atl-checkbox>' . \Form::checkbox($obj->id, NULL, FALSE, ['data-id' => $obj->id]) . '</span>',
          'version_id' => $this->nameTd($obj),
          'updated_at' => $obj->updated_at,
          'user_id' => $obj->username
      ];
    }

    return response()->json([
                'drow' => $request->get('draw'),
                'recordsTotal' => PatternsVersions::where('pattern_id', '=', $request->get('pattern_id'))->where('language', '=', $request->get('lang'))->get()->count(),
                'recordsFiltered' => $count,
                'data' => $data
    ]);
  }

  private function nameTd($obj) {

    $status = '';

    if ($obj->active == 0) {
      $status = 'disabled';
      return '<span class="tags hidden">tags</span>
                    <a class="item" data-status="' . $status . '" href="/admin/patterns/edit/' . $obj->pattern_id . '/' . $obj->version_id . '/' . $obj->language . '">' . $obj->version_id . '</a>
                    <span class="actions">
                      <a data-tooltip title="Make Active Version" href="/admin/patterns/make-active-version/' . $obj->pattern_id . '/' . $obj->version_id . '/' . $obj->language . '" class="icon icon-Pulse top"></a>
                      <a data-open="deleteVersion' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Delete Version" class="icon icon-Delete top "></a>
                    </span>' . \Atlantis\Helpers\Modal::set('deleteVersion' . $obj->id, 'Delete Version', 'Are you sure you want to delete version ' . $obj->version_id, 'Delete', '/admin/patterns/delete-version/' . $obj->pattern_id . '/' . $obj->version_id . '/' . $obj->language . '');
    } else if ($obj->active == 1) {
      $status = 'active';
      return '<span class="tags hidden">tags</span>
                    <a class="item" data-status="' . $status . '" href="/admin/patterns/edit/' . $obj->pattern_id . '/' . $obj->version_id . '/' . $obj->language . '">' . $obj->version_id . '</a>';
    }
  }

  /**
   * Add class to <table></table> tag
   * 
   */
  public function tableClass() {
    return NULL;
  }
}
