<?php
	date_default_timezone_set("Europe/Lisbon");
	require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/tool/canvas.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/tool/imgCompressor.php';
	if(!empty($_POST['codigo'])):
		$pasta = $_POST['codigo'];
	else:
		$pasta = "";
	endif;

	$uploadDir = $_SERVER['DOCUMENT_ROOT'] .'/images/imovel/'.$pasta.'/';

	$fileTypes = array('jpg', 'jpeg', 'gif', 'png');
	$verifyToken = md5('unique_salt' . $_POST['timestamp']);
	if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
		$tempFile   = $_FILES['Filedata']['tmp_name'];

		$fileParts = pathinfo($_FILES['Filedata']['name']);
	
		if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
			$file_name = $fileParts['filename'].date("s").".". $fileParts['extension']; 

			$targetFile = $uploadDir . $file_name;

			move_uploaded_file($tempFile, $targetFile);

			// $imgCompressor = new App_tool_ImgCompressor();
		 //    $imgCompressor->progressive($targetFile,$uploadDir.$file_name, 80);

			echo $file_name;

		} else {
			
		}
	}
?>