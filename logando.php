<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/backend/lib/App/Autoloader.php';

 

$login = addslashes( $_POST['login'] );

$senha = md5( $_POST['senha'] );

 

$conectar = new App_Logar();

echo $conectar->logar_cliente($login, $senha);


?>