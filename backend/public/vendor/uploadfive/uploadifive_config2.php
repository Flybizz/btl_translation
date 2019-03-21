<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/
require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/Autoloader.php';
date_default_timezone_set("Europa/Lisbon");
require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/tool/canvas.php';
$config = unserialize(CONFIG_DB);
// Set the uplaod directory
$uploadDir = $_SERVER['DOCUMENT_ROOT'].'/images/config/';
// Set the allowed file extensions
$fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'ico', 'svg'); // Allowed file extensions
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
echo $_POST['token']." / ".$verifyToken;
if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
  $tempFile = $_FILES['Filedata']['tmp_name'];
  // Validate the filetype
  $fileParts = pathinfo($_FILES['Filedata']['name']);
  if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
	  
    if(empty($config[0]['D001_Logosite'])):
      $targetFile = $uploadDir .$fileParts['filename']."-".date("s").strtolower($fileParts['extension']);
    else:
      $targetFile = $uploadDir . $config[0]['D001_Logosite'];
    endif;
    // Save the file
    move_uploaded_file($tempFile, $targetFile);
    // $img = new canvas();
    // $img->carrega($targetFile)
    // ->hexa('#FFFFFF')
    // ->redimensiona( 400, 200, 'preenchimento')
    // ->grava($targetFile,90);
    echo $config[0]['D001_Logosite'];
  } else {
    // The file type wasn't allowed
  }
}
?>