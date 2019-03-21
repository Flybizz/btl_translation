<?php
    require_once 'lib/App1/Autoloader.php';
    
    $logout = new App1_Logar();
    $logout->logout($_SESSION['id_usuario2']);    