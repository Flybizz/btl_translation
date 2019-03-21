<?php

/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

date_default_timezone_set("America/Sao_Paulo");

require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/tool/canvas.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/tool/imgCompressor.php';

// Set the uplaod directory

$uploadDir = $_SERVER['DOCUMENT_ROOT'].'/audio/';

// Set the allowed file extensions

$fileTypes = array('mp3'); // Allowed file extensions
$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {

	$tempFile   = $_FILES['Filedata']['tmp_name'];

	// Validate the filetype

	$fileParts = pathinfo($_FILES['Filedata']['name']);

	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {

		$nomeimg = time() . md5("musica") .".". $fileParts['extension'];		
		$targetFile = $uploadDir . $nomeimg;	
		
		// Save the file
		move_uploaded_file($tempFile, $targetFile);

		$imgCompressor = new App_tool_ImgCompressor();
	    $imgCompressor->progressive($targetFile,$uploadDir.$nomeimg, 80);

		echo $nomeimg;

	} else {

		// The file type wasn't allowed

	}

}

?>