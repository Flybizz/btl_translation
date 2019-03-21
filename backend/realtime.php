<?php
    require_once 'lib/App/Autoloader.php'; 
                     
    define("CONTROLLERS", "lib/App/Controller/");
    define("MODELS", "lib/App/Model/");
    define("VIEWS", "lib/App/View/");    


    require_once 'lib/App/Model.php';
    require_once 'lib/App/System.php';
    require_once 'lib/App/Controller.php';
    
    $controller = $_GET['controller'];
    $action = $_GET['action'];
    
    $url = $controller."/".$action;
            
    $startAjax = new App_System();
    $startAjax->_urlAjax = $url;
    $startAjax->setExplodeAjax();
    $startAjax->setControllerAjax();
    $startAjax->setActionAjax();
    $startAjax->setParamsAjax();
    $startAjax->runAjax();     

?>