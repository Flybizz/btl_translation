<?php
    require_once 'lib/App/Autoloader.php';
    
    $logout = new App_Logar();
    $logout->logout($_SESSION['id_usuario2']);    