<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Route;

class UploadFile
{
  public static function store($directory, $file)
  {
    $extension = $file->getClientOriginalExtension();

    if (
      Route::is('admin.course_management.lesson.upload_video') ||
      Route::is('admin.course_management.lesson.upload_file')
    ) {
      $originalName = $file->getClientOriginalName();
    }

    $fileName = uniqid() . '.' . $extension;
    @mkdir($directory, 0775, true);
    $file->move($directory, $fileName);

    if (Route::is('admin.course_management.lesson.upload_video')) {
      // get video duration after the video upload
      $getID3 = new \getID3;
      $fileInfo = $getID3->analyze($directory . $fileName);
      $duration = date('H:i:s', $fileInfo['playtime_seconds']);

      return array(
        'originalName' => $originalName,
        'uniqueName' => $fileName,
        'duration' => $duration
      );
    } elseif (Route::is('admin.course_management.lesson.upload_file')) {
      return array(
        'originalName' => $originalName,
        'uniqueName' => $fileName
      );
    } else {
      return $fileName;
    }
  }

  public static function update($directory, $newFile, $oldFile)
  {
    @unlink($directory . $oldFile);
    $extension = $newFile->getClientOriginalExtension();
    $fileName = uniqid() . '.' . $extension;
    @mkdir($directory, 0775, true);
    $newFile->move($directory, $fileName);

    return $fileName;
  }
}
