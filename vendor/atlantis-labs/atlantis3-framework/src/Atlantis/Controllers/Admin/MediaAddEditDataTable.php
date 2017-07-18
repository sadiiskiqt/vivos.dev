<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Models\Media;
use Illuminate\Support\Facades\DB;

class MediaAddEditDataTable implements \Atlantis\Helpers\Interfaces\DataTableInterface {

  public function __construct() {

    if (\Auth::check() === false) {

      return response()->json([]);
    }
  }

  public function columns() {

    return [
        [
            'title' => '<span class="fa fa-check-square-o select-all"></span>',
            'class-th' => 'checkbox no-sort hidden',
            'class-td' => 'checkbox hidden',
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
            'title' => 'Thumbnail',
            'class-th' => 'thumb',
            'class-td' => '',
            'key' => 'thumbnail',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Name',
            'class-th' => '',
            'class-td' => 'name',
            'key' => 'filename',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Type',
            'class-th' => 'hidden',
            'class-td' => 'url hidden',
            'key' => 'type',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Size',
            'class-th' => 'hidden',
            'class-td' => 'template-class hidden',
            'key' => 'filesize',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Updated at',
            'class-th' => 'hidden',
            'class-td' => 'template-class hidden',
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

    return [];
  }

  public function tableClass() {
   return 'add-media-table';
  }

  public function getData(\Illuminate\Http\Request $request) {

    $model = DB::table('media');

    /*
     * SEARCH
     */
    if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
      $search = $request->get('search')['value'];

      $oTags = \Atlantis\Models\Repositories\TagRepository::findByTag(AdminController::$_ID_MEDIA, $search);
      $aMediaTagIDs = array();
      foreach ($oTags as $t) {
        $aMediaTagIDs[] = $t->resource_id;
      }

      $model->where('id', 'LIKE', '%' . $search . '%');
      $model->orWhere('filename', 'LIKE', '%' . $search . '%');
      $model->orWhere('original_filename', 'LIKE', '%' . $search . '%');
      $model->orWhere('type', 'LIKE', '%' . $search . '%');
      $model->orWhere('filesize', 'LIKE', '%' . $search . '%');
      $model->orWhereIn('id', $aMediaTagIDs);
    }
    $model->having('thumbnail', '!=', '');
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

    $filePath = \Atlantis\Helpers\Tools::getFilePath();

    $aGalleries = \Atlantis\Models\Repositories\GalleryRepository::getAllGalleriesForSelect();

    foreach ($modelWithOffset as $k => $obj) {

      $data[$k] = [
          'checkbox' => '<span data-atl-checkbox>' . \Form::checkbox($obj->id, NULL, FALSE, ['data-id' => $obj->id]) . '</span>',
          'id' => $obj->id,
          'thumbnail' => $this->thumbnailTd($obj, $filePath),
          'filename' => $this->nameTd($obj, $aGalleries, $filePath),
          'type' => $obj->type,
          'filesize' => $obj->filesize,
          'updated_at' => $obj->updated_at
      ];
    }

    return response()->json([
                'drow' => $request->get('draw'),
                'recordsTotal' => Media::where('thumbnail', '!=', '')->get()->count(),
                'recordsFiltered' => $count,
                'data' => $data
    ]);
  }

  private function nameTd($obj, $aGalleries, $filePath) {

    if (empty($obj->filename)) {
      $name = $obj->original_filename;
    } else {
      $name = $obj->filename;
    }

    if (!empty($obj->thumbnail)) {
      $addToGalleryLink = '<a data-open="addToGallery' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Add to gallery" class="icon icon-Picture top "></a>';
      $addToGalleryModal = \Atlantis\Helpers\Modal::addImgToGallery('addToGallery' . $obj->id, $obj->id, $name, $aGalleries);
    } else {
      $addToGalleryLink = '';
      $addToGalleryModal = '';
    }

    return '<span class="tags hidden">tags</span>
                    <a class="item" href="/admin/media/media-edit/' . $obj->id . '">' . $name . '</a>
                    <span class="actions">
                    <a class="button alert add-to-gal" data-image-path="' . $filePath . $obj->thumbnail . '" data-image-id="' . $obj->id . '" id="include-' . $obj->id . '">add<a>
                    </span>' .
            \Atlantis\Helpers\Modal::set('deleteMedia' . $obj->id, 'Delete File', 'Are you sure you want to delete forever ' . $name, 'Delete', '/admin/media/media-delete/' . $obj->id) .
            $addToGalleryModal;
  }

  private function thumbnailTd($obj, $filePath) {

    if (!empty($obj->thumbnail)) {

      return '<img src="' . $filePath . $obj->thumbnail . '">';
    } else {
      return $obj->original_filename;
    }
  }

}
