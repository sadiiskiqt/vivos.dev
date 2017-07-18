<?php

namespace Atlantis\Helpers;

use Atlantis\Models\Repositories\TagRepository;

class Tags {

  /**
   * 
   * @param string $resource
   * @param int $resource_id
   * @return \Illuminate\Database\Eloquent\Collection;
   */
  public static function getTagsByResourceID($resource, $resource_id) {

    return TagRepository::getTagsByResourceID($resource, $resource_id);
  }

  /**
   * 
   * @param string $tag
   * @param string $resource
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function getItemsByTag($tag, $resource) {

    return TagRepository::getItemsByTag($tag, $resource);
  }

  /**
   * 
   * @param string $tag
   * @param string $resource
   * @param int $resource_id
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function getRelatedByTag($tag, $resource, $resource_id) {

    return TagRepository::getRelatedByTag($tag, $resource, $resource_id);
  }

  /**
   * 
   * @param string $tag
   * @param int $resource_id
   * @param string $resource
   * @return mixed
   */
  public static function addTag($tag, $resource_id, $resource) {

    return TagRepository::addTag($tag, $resource_id, $resource);
  }

  /**
   * 
   * @param string $delimiter
   * @param string $tags
   * @param int $resource_id
   * @param string $resource
   */
  public static function addTagsWithDelimiter($delimiter, $tags, $resource_id, $resource) {

    TagRepository::addTagsWithDelimiter($delimiter, $tags, $resource_id, $resource);
  }

  /**
   * 
   * @param int $resource_id
   * @param string $resource
   * @param array $aNewTags
   */
  public static function updateTags($resource_id, $resource, $aNewTags) {

    TagRepository::updateTags($resource_id, $resource, $aNewTags);
  }

  /**
   * 
   * @param string $delimiter
   * @param string $tags
   * @param int $resource_id
   * @param string $resource
   */
  public static function updateTagsWithDelimiter($delimiter, $tags, $resource_id, $resource) {

    TagRepository::updateTagsWithDelimiter($delimiter, $tags, $resource_id, $resource);
  }

  /**
   * 
   * @param string $resource
   * @param int $resource_id
   */
  public static function deleteTag($resource, $resource_id) {

    TagRepository::deleteTag($resource, $resource_id);
  }

  /**
   * 
   * @param string $resource
   * @param string $tag
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function findByTag($resource, $tag) {

    return TagRepository::findByTag($resource, $tag);
  }

  /**
   * 
   * @param string $resource
   */
  public static function deleteTags($resource) {

    TagRepository::deleteTags($resource);
  }

}
