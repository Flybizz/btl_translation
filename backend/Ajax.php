<?php   
        require_once $_SERVER['DOCUMENT_ROOT'] . '/backend/lib/App/Autoloader.php'; 
              
        define("CONTROLLERS", "lib/App/Controller/");
        define("MODELS", "lib/App/Model/");
        define("VIEWS", "lib/App/View/");   
        define("SITES", "lib/App/Site/");
                
        require_once 'lib/App/Model.php';
        require_once 'lib/App/System.php';
        require_once 'lib/App/Controller.php';

        $verifica_backend = $_SERVER['REQUEST_URI'];
        $url = $_REQUEST["url"];  

        $startAjax = new App_System();  
        if(strpos($verifica_backend, 'backend')):
            $startAjax->_urlAjax = $url;
        else:
            if(!isset( $_GET["url"] )): 
                $_GET["url"] = "index/index_action";
            else:                   
                $_GET["url"] = "page/".$_GET["url"];
            endif;
        endif;

        $startAjax->setExplodeAjax();
        $startAjax->setControllerAjax();
        $startAjax->setActionAjax();
        $startAjax->setParamsAjax();
        $startAjax->runAjax();   
        
?>