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

            //CHAMA BACKEND
            $startAjax->_urlAjax = $url;

        else:

            //CHAMA INDEX FRONTEND

            if(!isset( $_GET["url"] )):             

                $_GET["url"] = "index/index_action";

            else:              

                //CHAMA INDEX FRONTEND
                $_GET["url"] = "page/".$_GET["url"];                    

            endif;


        endif;

        //strpos($_SERVER['REQUEST_URI'], 'backend');

        //$this->_url = $_GET["url"];

        //echo $this->_urlAjax;

        
        $startAjax->setExplodeAjax();
        $startAjax->setControllerAjax();
        $startAjax->setActionAjax();
        $startAjax->setParamsAjax();
        $startAjax->runAjax();   
        
?>