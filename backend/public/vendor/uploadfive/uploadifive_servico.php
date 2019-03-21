<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/
date_default_timezone_set("America/Sao_Paulo");
require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/tool/canvas.php';
if(!empty($_POST['id_servico'])):
	$pasta = $_POST['id_servico'];
else:
	$pasta = "";
endif;
// Set the uplaod directory
$uploadDir = $_SERVER['DOCUMENT_ROOT'] .'/images/servico/'.$pasta.'/';
// Set the allowed file extensions
$fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // Allowed file extensions
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	//print_r($fileParts);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {

		$file_name = md5($fileParts['filename']) .".". $fileParts['extension']; 
		$targetFile = $uploadDir . $file_name;
		// Save the file
		move_uploaded_file($tempFile, $targetFile);

		$img = new canvas();
		$img->carrega($targetFile)
		->hexa('#FFFFFF')
		->redimensiona( 950, 550, 'preenchimento')
		->grava($targetFile,72);
		
		echo $file_name;

	} else {
		// The file type wasn't allowed
	}
}
?>