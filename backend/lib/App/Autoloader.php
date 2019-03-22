<?php
    session_start();
    date_default_timezone_set("Europe/Lisbon");
    define("PUBLIC_DIR", '/backend/Public/frontend');
    define("PUBLIC_DIR_PLUGIN_GOOGLE", '/backend/Google');
    define("DOC_ROOT", $_SERVER['DOCUMENT_ROOT']);
    set_include_path($_SERVER['DOCUMENT_ROOT'] .
    PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/backend/' .
    PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/backend/lib/' .
    PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/backend/lib/App/' .
    PATH_SEPARATOR . get_include_path());
    function __autoload ( $class )
    {        
        $class = str_replace('_', '/', $class) . '.php';
        require_once $class;
    }
    switch($_SERVER["HTTP_HOST"]){
        case "localhost":
        $config_path = $_SERVER['DOCUMENT_ROOT'] . '/configs-localhost.ini';
        break;
        default:
        $config_path = $_SERVER['DOCUMENT_ROOT'] . '/configs.ini';
        break;
    }
    
    $config = App_Config::factory($config_path);
    $config_datas = $config->catchObject();
    App_Registry::Set(App_Config::NAME_CONFIG_REGISTRY, $config_datas);
    /*DB CONSTANT*/
    $db = new App_Db;
    $query1 = $db->query("SELECT * FROM d001configuracoes WHERE D001_Uid = 1");
    $rg = $query1->fetchAll();
    define ("CONFIG_DB", serialize ($rg));
    if(isset( $_SESSION['nivel_acesso'] )):
    $query2 = $db->query("SELECT * FROM permissao WHERE per_id = ".$_SESSION['nivel_acesso']."");
    $rg2 = $query2->fetch(PDO::FETCH_ASSOC);
    define ("PERMISSAO_DB", serialize ($rg2));
    endif;
    $query3 = $db->query("SELECT D008_Sigla FROM d008idioma WHERE D008_Status = 'sim'");
    $rg3 = $query3->fetchAll(PDO::FETCH_COLUMN);
    define ("LANGUAGE_SIGLA", serialize ($rg3));
    $query4 = $db->query("SELECT D008_Sigla FROM d008idioma WHERE D008_Destaque = 'sim' AND D008_Status = 'sim'");
    $rg4 = $query4->fetchAll(PDO::FETCH_COLUMN);
    define ("LANGUAGE_DEFAULT", serialize ($rg4));
    $query5 = $db->query("SELECT * FROM d008idioma WHERE D008_Status = 'sim'");
    $rg5 = $query5->fetchAll(PDO::FETCH_ASSOC);
    define ("LANGUAGE_LIST", serialize ($rg5));
    //CHAMA FRONTEND
    if(isset($_GET["url"])):
        /* explode o array */
        $urlDefault = explode("/",  $_GET["url"]);
        $urlVerifica = explode("/",  $_GET["url"]);
        /* pega o primeiro paramentro do array */
        $first_parm = array_shift($urlDefault);
        /* pega o restante dos paramentros da url */
        /* verifica se o primeiro paramentro faz parte do conjunto de language ativas */
        if(in_array($first_parm, $rg3)):
            /* variavel _language recebe o parametro language da URL */
            $_language = $first_parm;
            if(!empty($urlDefault)):
                $action = $urlDefault[0];
            else:
                $action = "";
            endif;
        else:
            /* FRONTEND PAGE DEFAULT */
            $_language = strtolower($rg4[0]);
            if(!empty($urlVerifica)):
                $action = $urlVerifica[0];
            else:
                $action = "";
            endif;
        endif;
    else:
        /* FRONTEND DEFAULT (PAGINA PRINCIPAL INDEX) */
        if(isset($rg4[0])):
            $_language =  strtolower($rg4[0]);
        else:
            $_language =  "pt";
        endif;
        $action = "";
    endif;



    $backend_arr = explode("/", $_SERVER["REQUEST_URI"] );
    if($backend_arr[1] != "backend"):

        $query6 = $db->query("SELECT * FROM d008idioma WHERE D008_Sigla = '".$_language."' AND D008_Status = 'sim'");
        $rg6 = $query6->fetchAll(PDO::FETCH_ASSOC);
        define ("LANGUAGE_ATUAL", serialize ($rg6));

        /* MENU */
        $menu = $db->query("SELECT * FROM d008categoriasmenu WHERE D008_PertenceCodigoMaster = 0 AND D008_Tipo = '_self' AND D008_Idioma = '".$_language."' ORDER BY D008_Ordem ASC");
        $sel_menu = $menu->fetchAll(PDO::FETCH_ASSOC);
        define ("MENU_LIST", serialize ($sel_menu));

        /* VERIFICA O LINK ATUAL */
        if(!empty($action) && $action != "index"):
            $menu2 = $db->query("SELECT * FROM d007pagina WHERE D007_Idioma = '".$_language."' AND SEO_Slug LIKE '%".$action."%'");
            $sel_controller = $menu2->fetchAll(PDO::FETCH_ASSOC);
            $controller = $sel_controller[0]["D007_Controle"];
        else:
            $sel_controller = array();
            $controller = "index";
        endif;

        define ("MENU_LINK", serialize (["sigla"=>$_language, "action"=> $action, "controller"=> $controller, "param"=> ""]));
        $menu_link = unserialize(MENU_LINK);
      
    else:
      $controller = $backend_arr[2];
      $pagina = array();
    endif;

    //lang
    $lang = $_language;
    $defines = json_decode(file_get_contents( $_SERVER['DOCUMENT_ROOT'] . "/backend/lib/App/Config/lang/{$lang}.json"), true);
    
    foreach($defines as $key => $value){
        define($key, $value);
    }

    //com defines php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/backend/lib/App/Config/lang/{$lang}.php");
    

    //function translate
    function translate($slug){

        $defined = defined($slug);

        //o define não existe no idioma actual
        if(!$defined){

            $string = "define(\"{$slug}\", \"_____\");\n";
            $handle = fopen("translation.txt", "a+");
            $write = fwrite($handle, $string);
            $close = fclose($handle);

            return "++" . $slug . "++";
            //caso não exista, pode retornar no idioma default
            //include_once(.../pt.php"));
        }

        //return "||" . constant($slug) . "||";
        return constant($slug);
        
    }
?>
