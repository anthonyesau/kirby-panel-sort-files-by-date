<?php
//Save to site/plugins/file-sorting-by-date.php

kirby()->hook('panel.file.upload', 'uploaddatesort');
kirby()->hook('panel.file.replace', 'sortfiles');

function setdate($file) {

  //Reference Date Options:
  //"today": Current date.
  //"modified": Time of last file modification. If uploaded via Panel, this will be the upload date.
  //"taken": If a photo, then when it was taken. Falls back on "modified" behavior otherwise.
  $referencedate = "taken";

  switch ($referencedate) {
    case "today":
      $filedate = time('Y-m-d');
      break;
    case "modified":
      $filedate = $file->modified('Y-m-d');
      break;
    case "taken":
      if ($file->type() == "image"){
          $filedate = date('Y-m-d',$file->exif()->timestamp());
      } else {
        $filedate = $file->modified('Y-m-d');
      }
      break;
  }

  $file->update(array(
    'date' => $filedate
  ));

}


function uploaddatesort ($file) {
  setdate($file);
  sortfiles($file);
}


function sortfiles($file) {
  //Order Options:
  //'asc': ascending, oldest to newest
  //'desc': descending, newest to oldest
  $order = 'desc';

  foreach ($file->files() as $f) {
    if ($f->date() == "") {
      setdate($f);
    }
  }

  $i = 1;
  foreach ($file->files()->sortBy('date', $order) as $f) {
    $f->update(array(
      'sort'    => $i
    ));
    $i++;
  }
}

?>
