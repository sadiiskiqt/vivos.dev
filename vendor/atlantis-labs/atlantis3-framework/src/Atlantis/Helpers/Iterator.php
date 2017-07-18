<?php

namespace Atlantis\Helpers;

class Iterator {

  /**
   * Function getFiles
   *
   * @param string $sDir
   * @param string $sOption
   * @param string $sRecursive
   * @param string $sFullPath
   * @return array 
   * @example Helper_Iterator::getFiles("/../atlantis/", "WITHOUT EXT", TRUE, FALSE);
   * Set path and get all filenames in this path.
   */
  public static function getFiles($sDir, $sOption = "WITH EXT", $sRecursive = FALSE, $sFullPath = TRUE) {

    $aFiles = array();

    $aRecursive = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(base_path() . $sDir), \RecursiveIteratorIterator::SELF_FIRST);

    $aDirectory = new \DirectoryIterator(base_path() . $sDir);

    if ($sOption == "WITH EXT") {

      if ($sRecursive) {

        foreach ($aRecursive as $fileinfo) {

          if (!$fileinfo->isDir()) {

            if (!$sFullPath) {

              $aFiles[] = substr($fileinfo->getPathinfo() . "/" . $fileinfo->getFilename(), strlen(base_path() . $sDir . "/"));
            } else {

              $aFiles[] = $fileinfo->getFilename();
            }
          }
        }
      } else {

        foreach ($aDirectory as $fileinfo) {

          if (!$fileinfo->isDot()) {

            if (!$sFullPath) {

              $aFiles[] = substr($fileinfo->getPathinfo() . "/" . $fileinfo->getFilename(), strlen(base_path() . $sDir . "/"));
            } else {

              $aFiles[] = $fileinfo->getFilename();
            }
          }
        }
      }
    } else if ($sOption == "WITHOUT EXT") {

      if ($sRecursive) {

        foreach ($aRecursive as $fileinfo) {

          if (!$fileinfo->isDir()) {

            if (!$sFullPath) {

              $aEx = explode(".php", $fileinfo->getFilename());

              $aFiles[] = substr($fileinfo->getPathinfo() . "/" . $aEx[0], strlen(base_path() . $sDir . "/"));
            } else {

              $aFiles[] = $fileinfo->getPathinfo() . "/" . $aEx[0];
            }
          }
        }
      } else {

        foreach ($aDirectory as $fileinfo) {

          $aEx = explode(".php", $fileinfo->getBasename());

          if (!$fileinfo->isDot()) {

            if (!$sFullPath) {

              $aFiles[] = substr($aEx[0], strlen(base_path() . $sDir . "/"));
            } else {

              $aFiles[] = $aEx[0];
            }
          }
        }
      }
    }

    asort($aFiles);

    return $aFiles;
  }

}
