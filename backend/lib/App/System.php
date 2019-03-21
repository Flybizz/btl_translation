<?php
class App_System
{

    private $_url;
    private $_explode;
    public $_language;
    public $_controller;
    public $_action;
    public $_params;

    //AJAX
    public $_urlAjax;
    public $_explodeAjax;
    public $_languageAjax;
    public $_controllerAjax;
    public $_actionAjax;
    public $_paramsAjax;

    public function __construct()
    {

        $this->setUrl();
        $this->setExplode();
        $this->setController();
        $this->setAction();
        $this->setParams();

        //AJAX
        //$this->urlAjax();
        $this->setExplodeAjax();
        $this->setControllerAjax();
        $this->setActionAjax();
        $this->setParamsAjax();

    }

    public function setUrl()
    {

        $languageSIGLA = unserialize (LANGUAGE_SIGLA);
        $languageDEFAULT = unserialize (LANGUAGE_DEFAULT);

        if (strpos($_SERVER['REQUEST_URI'], 'backend')):

            //CHAMA BACKEND
            $url_set = (isset($_GET["url"]) ? $_GET["url"] : "/index/index_action");
            $_GET["url"] = $url_set;

        else:

            //CHAMA FRONTEND
            if(isset($_GET["url"])):

                /* explode o array */
                $urlDefault = explode("/",  $_GET["url"]);
                /* pega o primeiro paramentro do array */
                $first_parm = array_shift($urlDefault);
                /* pega o restante dos paramentros da url */
                $urlLast = implode("/",$urlDefault);

                /* verifica se o primeiro paramentro faz parte do conjunto de language ativas */
                if(in_array($first_parm, $languageSIGLA)):

                    /* variavel _language recebe o parametro language da URL */
                    $this->_language = $first_parm;

                    if (empty($urlDefault) || $urlDefault[0] == ""):
                        /* FRONTEND COM IDIOMA SELECIONADO - CHAMA PAGINA PRINCIPAL INDEX */
                        $url_set =  $this->_language."/indexs/index_action";
                    else:
                        /* CHAMA PAGE FRONTEND */
                        $url_set = $this->_language."/page/".$urlLast;
                    endif;

                else:
                    /* FRONTEND PAGE DEFAULT */
                    $url_set = strtolower($languageDEFAULT[0])."/page/".$first_parm."/".$urlLast;
                endif;

            else:
                /* FRONTEND DEFAULT (PAGINA PRINCIPAL INDEX) */
                $url_set =  strtolower($languageDEFAULT[0])."/indexs/index_action";
            endif;

        endif;

        $this->_url = $url_set;
    }

    public function setExplode()
    {

        $this->_explode = explode("/", $this->_url);

        //print_r($this->_explode);

    }

    public function setController()
    {
        if (strpos($_SERVER['REQUEST_URI'], 'backend')):

            //CHAMA BACKEND
            $this->_controller = ($this->_explode[0] == "index" ? $this->_explode[0] : $this->_explode[0]);

        else:

            //CHAMA INDEX FRONTEND
            $this->_controller = ($this->_explode[1] == "index" ? "indexs" : $this->_explode[1]);

        endif;

        //print_r($this->_controller);

    }

    public function setAction()
    {

        if (strpos($_SERVER['REQUEST_URI'], 'backend')):

            //CHAMA BACKEND
            $ac = (!isset($this->_explode[1]) || $this->_explode[1] == null || $this->_explode[1] == "index" ? "index_action" : $this->_explode[1]);
            $this->_action = str_replace("-", "", $ac);

        else:

            //CHAMA INDEX FRONTEND
            $ac = (!isset($this->_explode[2]) || $this->_explode[2] == null || $this->_explode[2] == "index" ? "index_action" : $this->_explode[2]);

            $menuLIST = unserialize (MENU_LIST);

            foreach($menuLIST as $menu):
                $link = explode("|",$menu["D008_Link"]);
                array_shift($link);
                
                if($link[0] == ""):
                    $link[0] = "index_action";
                endif;
                //verifica o link com a action
                if($ac == $link[0]):
                    if($link[0] == "index_action"):
                        $menu["D008_Controller"] = "index_action";
                    endif;
                    $this->_action = str_replace("-", "", $menu["D008_Controller"]);
                endif;

            endforeach;

        endif;

        //echo $this->_action;

    }

    public function getAction($name = null)
    {

        if ($name != null) {

            return $this->_action[$name];

        } else {

            return $this->_action;

        }


    }

    public function setParams()
    {

        if (strpos($_SERVER['REQUEST_URI'], 'backend')):

            //CHAMA BACKEND
            unset($this->_explode[0], $this->_explode[1]);

        else:

            //CHAMA INDEX FRONTEND
            unset($this->_explode[0], $this->_explode[1], $this->_explode[2]);

        endif;



        if (end($this->_explode) == null)

            array_pop($this->_explode);

        $i = 0;

        $ind = array();
        $value = array();

        if (!empty($this->_explode)) {

            foreach ($this->_explode as $valor) {

                if ($i % 2 == 0) {

                    $ind[] = $valor;

                } else {

                    $value[] = $valor;

                }

                $i++;

            }

        }

        if (!empty($value)):

            if (count($ind) == count($value) && !empty($ind) && !empty($value)):

                $this->_params = array_combine($ind, $value);

            else:

                $this->_params = array();

            endif;

        else:

            $this->_params = array();

        endif;

    }


    public function getParams($name = null)
    {

        if ($name != null) {

            return $this->_params[$name];

        } else {

            return $this->_params;

        }


    }


    /********************* AJAX ***********************************/


    public function setExplodeAjax()
    {

        $this->_explodeAjax = explode("/", $this->_urlAjax);

    }


    public function setControllerAjax()
    {


        $this->_controllerAjax = $this->_explodeAjax[0];


        if (strpos($_SERVER['REQUEST_URI'], 'backend')):


            //CHAMA BACKEND
            $this->_controllerAjax = ($this->_explodeAjax[0] == "index" ? $this->_explodeAjax[0] : $this->_explodeAjax[0]);

        else:

            //CHAMA INDEX FRONTEND
            //$this->_controllerAjax = ($this->_explodeAjax[1] == "index" ? "indexs" : $this->_explodeAjax[1]);

        endif;


    }


    public function setActionAjax()
    {

        if (strpos($_SERVER['REQUEST_URI'], 'backend')):

            //CHAMA BACKEND
            $ac = (!isset($this->_explodeAjax[1]) || $this->_explodeAjax[1] == null || $this->_explodeAjax[1] == "index" ? "index_action" : $this->_explodeAjax[1]);
            $this->_actionAjax = str_replace("-", "", $ac);

        else:

            //CHAMA INDEX FRONTEND
            $ac = (!isset($this->_explodeAjax[2]) || $this->_explodeAjax[2] == null || $this->_explodeAjax[2] == "index" ? "index_action" : $this->_explodeAjax[2]);
            $this->_actionAjax = str_replace("-", "", $ac);

        endif;

    }


    public function setParamsAjax()
    {

        if (strpos($_SERVER['REQUEST_URI'], 'backend')):

            //CHAMA BACKEND
            unset($this->_explodeAjax[0], $this->_explodeAjax[1]);

        else:

            //CHAMA INDEX FRONTEND
            unset($this->_explodeAjax[0], $this->_explodeAjax[1], $this->_explodeAjax[2]);

        endif;


        if (end($this->_explodeAjax) == null)

            array_pop($this->_explodeAjax);


        $i = 0;

        if (!empty($this->_explodeAjax)) {

            foreach ($this->_explodeAjax as $valor) {

                if ($i % 2 == 0) {

                    $ind[] = $valor;

                } else {

                    $value[] = $valor;

                }

                $i++;

            }

        } else {

            $ind = array();

            $value = array();

        }


        if (count($ind) == count($value) && !empty($ind) && !empty($value)) {


            $this->_paramsAjax = array_combine($ind, $value);


        } else {


            $this->_paramsAjax = array();

        }


    }


    public function getParamsAjax($name = NULL)
    {


        if ($name != NULL) {

            return $this->_paramsAjax[$name];

        } else {

            return $this->_paramsAjax;

        }


    }


    /*RUN*/
    public function run()
    {
        //echo $this->_controller;

        $controller_path = CONTROLLERS . $this->_controller . "Controller.php";

        if (!file_exists($controller_path)) {


            if (strpos($_SERVER['REQUEST_URI'], 'backend')):


                die('<div class="error"><i class="fa fa-exclamation-circle fa-3x"></i><span> Ocorreu um erro!</br></br> favor entrar em contato com o suporte: helpdesk@flybizz.net </br><p>MOTIVO: "CONTROLLER" não existe.</p></span><br><br> <a href="/backend/index" class="btn btn-info">Voltar</a><br></div>');


            else:

                //CHAMA INDEX FRONTEND

                //echo "<script>window.open('/error/type/controller','_self');</script>";


            endif;


        } else {

            require_once $controller_path;
                        
            $app = new $this->_controller();
            
            $action = $this->_action;

            if (!method_exists($app, $this->_action)):
                
                if (strpos($_SERVER['REQUEST_URI'], 'backend')):

                    die('<div class="error"><i class="fa fa-exclamation-circle fa-3x"></i><span> Ocorreu um erro!</br></br> favor entrar em contato com o suporte: helpdesk@flybizz.net </br><p>MOTIVO: "ACTION" não existe.</p></span> <br><br><a href="/backend/index" class="btn btn-info">Voltar</a><br></div>');

                else:

                    //echo "<script>window.open('/error/type/action','_self');</script>";

                endif;

            else:
                $app->$action();                            
            endif;
                        
        }

    }


    public function runAjax()
    {


        $controller_pathAjax = CONTROLLERS . $this->_controllerAjax . "Controller.php";


        if (!file_exists($controller_pathAjax)) {


            if (strpos($_SERVER['REQUEST_URI'], 'backend')):

                die('<div class="error"><i class="fa fa-exclamation-circle fa-3x"></i><span> Ocorreu um erro!</br></br> favor entrar em contato com o suporte: helpdesk@flybizz.net </br><p>MOTIVO: "CONTROLLER" não existe.</p></span> <br><br><a href="/backend/index" class="btn btn-info">Voltar</a><br></div>');

            else:

                //echo "<script>window.open('/error/type/controller','_self');</script>";

            endif;


        } else {


            require_once $controller_pathAjax;

            $app = new $this->_controllerAjax();

            $action = $this->_actionAjax;

            if (!method_exists($app, $this->_actionAjax)):

                if (strpos($_SERVER['REQUEST_URI'], 'backend')):

                    die('<div class="error"><i class="fa fa-exclamation-circle fa-3x"></i><span> Ocorreu um erro!</br></br> favor entrar em contato com o suporte: helpdesk@flybizz.net </br><p>MOTIVO: "ACTION" não existe.</p></span> <br><br><a href="/backend/index" class="btn btn-info">Voltar</a><br></div>');

                else:

                    //echo "<script>window.open('/error/type/action','_self');</script>";

                endif;

            endif;

            $app->$action();


        }

    }


}
