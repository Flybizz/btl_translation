<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/
date_default_timezone_set("America/Sao_Paulo");
//require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/tool/canvas.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/tool/imgCompressor.php';
// Set the uplaod directory
$uploadDir = $_SERVER['DOCUMENT_ROOT'].'/images/config/bg/';
// Set the allowed file extensions
$fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'ico', 'svg'); // Allowed file extensions
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
  $tempFile   = $_FILES['Filedata']['tmp_name'];
  // Validate the filetype
  $fileParts = pathinfo($_FILES['Filedata']['name']);
  if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
    $nomeimg = $fileParts['filename']."-".date("s").".". $fileParts['extension'];
    //$targetFile = $uploadDir . date("dmYHi") . md5($fileParts['filename']) .".". $fileParts['extension'];
    $targetFile = $uploadDir.$nomeimg;
    // Save the file
    move_uploaded_file($tempFile, $targetFile);
    $imgCompressor = new App_tool_ImgCompressor();
    $imgCompressor->compress($targetFile,$uploadDir.$nomeimg, 80);
    // $img = new canvas();
    // $img->carrega($targetFile)
    // ->hexa('#FFFFFF')
    // ->redimensiona( 1900, 911, 'preenchimento')
    // ->grava($targetFile,96);
    echo $nomeimg;
  } else {
    // The file type wasn't allowed
  }
}
?>