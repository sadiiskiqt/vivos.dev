<?php

namespace Atlantis\Helpers;

class PLUploadReceiver {

  public function upload() {

    $targetDir = config('atlantis.user_media_upload');

    $cleanupTargetDir = true;

    $maxFileAge = 5 * 3600;

    //$file = \Input::file('file')->move($targetDir, \Input::file('file')->getClientOriginalName());
    //die('{"jsonrpc" : "2.0", "result" : null, "id" : 2}');

    $original_name = request()->get('name');

    $filePath = $targetDir . $original_name;

    $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
    $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

    if ($cleanupTargetDir) {

      if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
      }

      while (($file = readdir($dir)) !== false) {

        $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

        // If temp file is current file proceed to the next
        if ($tmpfilePath == "{$filePath}.part") {
          continue;
        }

        // Remove temp file if it is older than the max age and is not the current file
        if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
          @unlink($tmpfilePath);
        }
      }

      closedir($dir);
    }

    // Open temp file
    if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
      die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
    }

    if (!empty($_FILES)) {
      if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
      }

      // Read binary input stream and append it to temp file
      if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
      }
    } else {
      if (!$in = @fopen("php://input", "rb")) {
        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
      }
    }

    while ($buff = fread($in, 4096)) {
      fwrite($out, $buff);
    }

    @fclose($out);
    @fclose($in);

    // Check if file has been uploaded
    if (!$chunks || $chunk == $chunks - 1) {

      $fname = time() . '-' . str_replace(' ', '_', $original_name);

      // Strip the temp .part suffix off
      rename("{$filePath}.part", $targetDir . $fname);

      return $fname;

      // Return Success JSON-RPC response
      //return '{"jsonrpc" : "2.0", "target_name" : "' . $fname . '", "id" : "' . $id . '"}';
    }
  }

}
