<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\Tag;

class TagRepository {

  public static function getTagsByResourceID($resource, $resource_id) {

    return Tag::where('resource', '=', $resource)
                    ->where('resource_id', '=', $resource_id)
                    ->get();
  }

  public static function getTagsByResourceIDinArray($resource, $resource_id) {

    $model = Tag::where('resource', '=', $resource)
            ->select('tag')
            ->where('resource_id', '=', $resource_id)
            ->get();

    return array_flatten($model->toArray());
  }

  public static function getItemsByTag($tag, $resource) {

    $model = new Tag();

    return $model->where("tag", "=", $tag)
                    ->where("resource", "=", $resource)
                    ->get();
  }

  public static function getRelatedByTag($tag, $resource, $resource_id) {

    $model = new Tag();

    return $model->where("tag", "=", $tag)
                    ->where("resource", "=", $resource)
                    ->where("resource_id", "!=", $resource_id)
                    ->get();
  }

  /**
   * add new tag
   */
  public static function addTag($tag, $resource_id, $resource) {

    if (!empty($tag)) {

      $model = Tag::firstOrNew([
                  'tag' => $tag,
                  'resource_id' => $resource_id,
                  'resource' => $resource
      ]);

      if ($model->id == NULL) {
        return $model->save();
      } else {
        return $model->touch();
      }
    }

    return FALSE;
  }

  public static function addTagsWithDelimiter($delimiter, $tags, $resource_id, $resource) {

    $aTags = explode($delimiter, $tags);

    foreach ($aTags as $tag) {
      self::addTag($tag, $resource_id, $resource);
    }
  }

  public static function updateTags($resource_id, $resource, $aNewTags) {

    $model = Tag::where('resource_id', '=', $resource_id)
            ->where('resource', '=', $resource)
            ->get();

    foreach ($model as $m) {

      if (!in_array($m->tag, $aNewTags)) {

        $m->delete();
      }
    }

    foreach ($aNewTags as $tag) {

      self::addTag($tag, $resource_id, $resource);
    }
  }

  public static function updateTagsWithDelimiter($delimiter, $tags, $resource_id, $resource) {

    $aTags = explode($delimiter, $tags);

    self::updateTags($resource_id, $resource, $aTags);
  }

  public static function deleteTag($resource, $resource_id) {

    Tag::where('resource', '=', $resource)
            ->where('resource_id', '=', $resource_id)
            ->delete();
  }

  public static function findByTag($resource, $tag) {

    return Tag::where('resource', '=', $resource)
                    ->where('tag', 'LIKE', '%' . $tag . '%')
                    ->get();
  }

  public static function deleteTags($resource) {

    Tag::where('resource', '=', $resource)->delete();
  }

}
