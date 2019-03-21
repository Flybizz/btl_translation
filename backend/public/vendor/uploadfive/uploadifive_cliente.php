<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/
//date_default_timezone_set("Europa/Lisbon");
require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/tool/canvas.php';

// Set the uplaod directory
$uploadDir = $_SERVER['DOCUMENT_ROOT'] .'/images/cliente/';
// Set the allowed file extensions
$fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // Allowed file extensions
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	//print_r($fileParts);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
		$name_file = strtolower(str_replace(" ","_",$fileParts['filename']));
		$format = strtolower($fileParts['extension']);
		$file_name = $name_file."_".date("s").".". $format;
		$targetFile = $uploadDir . $file_name;

		// Save the file
		move_uploaded_file($tempFile, $targetFile);

		// $img = new canvas();
		// $img->carrega($targetFile)
		// ->hexa('#FFFFFF')
		// ->redimensiona( 1920, 1100, 'preenchimento')
		// ->grava($targetFile,96);

		echo $file_name;
	} else {
		// The file type wasn't allowed
	}
}
?>
