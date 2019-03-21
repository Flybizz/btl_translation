<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/
date_default_timezone_set("Europe/Lisbon");
require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/tool/canvas.php';
// Set the uplaod directory
$uploadDir = $_SERVER['DOCUMENT_ROOT'].'/images/produto/';
// Set the allowed file extensions
$fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // Allowed file extensions
if (!empty($_FILES)) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
  	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
	  
		$nomeimg = $fileParts['filename']."-".date("s").".". $fileParts['extension'];		
		$targetFile = $uploadDir . $nomeimg;	  
	  
	    // Save the file
	    move_uploaded_file($tempFile, $targetFile);
	    // $img = new canvas();
	    // $img->carrega($targetFile)
	    // ->hexa('#FFFFFF')
	    // ->redimensiona( 880, 480, 'crop')
	    // ->grava($targetFile,72);
		echo $nomeimg;
	} else {
		// The file type wasn't allowed
	}
}
?>