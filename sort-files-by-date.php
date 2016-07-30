<?php
//Save to site/plugins/sort-files-by-date.php

kirby()->hook('panel.file.upload', 'uploaddatesort');
kirby()->hook('panel.file.replace', 'sortfiles');

function setdatetime($file) {

  //Reference Date Options:
  //"now": Current date and time.
  //"modified": Time of last file modification. If uploaded via Panel, this will be the upload date.
  //"taken": If a photo, then when it was taken. Falls back on "modified" behavior otherwise.
  $referencedate = "taken";

  switch ($referencedate) {
    case "now":
      $filedate = time('Y-m-d');
      $filetime = time('H:i');
      break;
    case "modified":
      $filedate = $file->modified('Y-m-d');
      $filetime = $file->modified('H:i');
      break;
    case "taken":
      if ($file->type() == "image"){
          $filedate = date('Y-m-d',$file->exif()->timestamp());
          $filetime = date('H:i',$file->exif()->timestamp());
      } else {
        $filedate = $file->modified('Y-m-d');
        $filetime = $file->modified('H:i');
      }
      break;
  }

  $file->update(array(
    'date' => $filedate,
    'time' => $filetime
  ));

}


function uploaddatesort ($file) {
  setdatetime($file);
  sortfiles($file);
}


function sortfiles($file) {
  //Order Options:
  //'asc': ascending, oldest to newest
  //'desc': descending, newest to oldest
  $order = 'desc';

  foreach ($file->files() as $f) {
    if ($f->date() == "") {
      setdatetime($f);
    }
  }

  $i = 1;
  foreach ($file->files()->sortBy('date', $order, 'time', $order) as $f) {
    $f->update(array(
      'sort'    => $i
    ));
    $i++;
  }
}

?>
