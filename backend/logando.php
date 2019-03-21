<?php
require_once 'lib/App/Autoloader.php';
 
$login = addslashes( $_POST['username'] );
$senha = md5( $_POST['pwd'] );

$conectar = new App_Logar();
echo $conectar->logar($login, $senha);

?>