<?php

namespace Module\Accommodations\Controllers\Admin;


use Illuminate\Http\Request;
use Module\Alex\Models\Alex;
use Illuminate\Support\Facades\DB;


class AccommodationsDataTable implements \Atlantis\Helpers\Interfaces\DataTableInterface
{

    public function __construct()
    {
        if (\Auth::check() === false) {
            return response()->json([]);
        }
    }

    public function columns()
    {
        return [
            [
                'title' => '<span class="fa fa-check-square-o"></span>',
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
    public function bulkActions()
    {
        return [
            'url' => 'admin/modules/accommodations/bulk-action-room',
            'actions' => [
                [
                    'name' => 'Delete',
                    'key' => 'bulk_delete'
                ]
            ]
        ];
    }

    public function getData(Request $request)
    {
        $model = DB::table('accommodations');
        /*
         * SEARCH
         */
        if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
            $search = $request->get('search')['value'];

            $model->where('id', 'LIKE', '%' . $search . '%');
            $model->orWhere('room_title', 'LIKE', '%' . $search . '%');
        }

        /*
         * Count filtered data without LIMIT and OFFSET
         */
        $modelWhitoutOffset = $model;
        $count = $modelWhitoutOffset->count();

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
                'id' => $obj->id,
                'name' => $this->nameTd($obj),
                'updated_at' => $obj->updated_at,
            ];
        }

        return response()->json([
            'drow' => $request->get('draw'),
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data
        ]);
    }

    private function nameTd($obj)
    {

        return '<span class="tags hidden">tags</span>
                    <a class="item" href="/admin/modules/accommodations/edit-room/' . $obj->id . '">' . $obj->room_title . '</a>
                    <span class="actions">
                      <a data-tooltip title="Edit Room" href="/admin/modules/accommodations/edit-room/' . $obj->id . '" class="icon icon-Edit top"></a> 
                      <a data-open="deleteMenu' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Delete Room" class="icon icon-Delete top "></a>
                    </span>' .
            \Atlantis\Helpers\Modal::set('deleteMenu' . $obj->id, 'Delete Accommodation Room', 'Are you sure you want to delete ' . $obj->room_title, 'Delete', '/admin/modules/accommodations/remove-room/' . $obj->id);
    }

    /**
     * Add class to <table></table> tag
     *
     */
    public function tableClass()
    {
        return NULL;
    }
}
