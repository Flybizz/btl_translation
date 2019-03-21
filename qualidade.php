<?php
require( 'backend/lib/App/tool/canvas.php' );
$img = new canvas();
$img->carrega( $_GET['imagem'] )->hexa( '#FFFFFF' )->redimensiona( $_GET['lar'], $_GET['alt'], 'crop' )->grava();
exit;
?>