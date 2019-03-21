<?php
    class idioma extends App_Controller{
        public function index_action(){
            return $this->View("indexIdioma");
        }
        /****************** CRUD ******************************/
        public function registar(){
            /*DADOS idioma*/
            /*$model = new App_Model_idiomacatModel();
            $model_categoria = $model->listaidioma();
            $dados['idiomacat'] = $model_categoria;
*/
            //funcao que chama a view
            return $this->View("cadastrarIdioma");
        }

        public function inserir(){
             // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            //print_r($dados);
            $model = new App_Model_idiomaModel();
            $model_inserir = $model->idiomaCadastrar($dados);
            $sel_ultimorg = $model->idiomaSelecionarUltimoRg();

            $languageDEFAULT = unserialize (LANGUAGE_DEFAULT);

            $m_menu = new App_Model_menuModel();
            $sel_menuIdioma = $m_menu->menuSelecionarIdioma($languageDEFAULT[0]);

            foreach ($sel_menuIdioma as $menu):
                $arr = array(
                    'D008_DescriCategoria' => $menu['D008_DescriCategoria'],                  
                    'D008_Chave' => $menu['D008_Chave'],
                    'D008_PertenceCodigoMaster' => $menu['D008_PertenceCodigoMaster'],
                    'D008_PertenceCodigoSub1' => $menu['D008_PertenceCodigoSub1'],
                    'D008_PertenceCodigoSub2' => $menu['D008_PertenceCodigoSub2'],
                    'D008_PertenceCodigoSub3' => $menu['D008_PertenceCodigoSub3'],
                    'D008_PertenceCodigoSub4' => $menu['D008_PertenceCodigoSub4'],
                    'D008_PertenceCodigoSub5' => $menu['D008_PertenceCodigoSub5'],
                    'D008_Link' => $menu['D008_Link'],
                    'D008_Ordem' =>  $menu['D008_Ordem'],
                    'D008_Tipo' =>  $menu['D008_Tipo'],
                    'D008_Idioma' =>  $sel_ultimorg[0]["D008_Sigla"],
                    'D008_Controller' =>  $menu["D008_Controller"]                    
                 );
                 $sel_menucopy = $m_menu->menuCadastrarCopy($arr);
                 echo $sel_menucopy;
            endforeach;

            echo $model_inserir."\n";
            echo $sel_ultimorg[0]["D008_Sigla"];

        }
        //ALTERAR
        public function alterar(){

            global $start;
            $parm = $start->_params;

            $idioma_alt = new App_Model_idiomaModel();
            $idioma_lista_alt = $idioma_alt->idiomaSelecionar($parm['ref']);
            $dados['idioma_lista_alt'] = $idioma_lista_alt;
            $this->View("alterarIdioma", $dados);
        }
        public function selecionar($view = NULL){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");
            $idioma_alt = new App_Model_idiomaModel();
            $idioma_lista_alt = $idioma_alt->idiomaSelecionar($ident);
            $dados['idioma_lista_alt'] = $idioma_lista_alt;
            /*DADOS CATEGORIA*/
            /*$model = new App_Model_idiomacatModel();
            $model_categoria = $model->listaidioma();
            $dados['idiomacat'] = $model_categoria;*/
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarIdioma", $dados);
            endif;
        }
        //ALTERAÇÃO
        public function alteracao(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $model = new App_Model_idiomaModel();
            $model_update = $model->idiomaAlteracao($dados);
            echo $model_update;
        }
        //DELETAR
        public function deletar(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $model = new App_Model_idiomaModel();
            $model_delete = $model->idiomaDeletar($ident['id']);
            echo $model_delete;
        }
        /***************     PAGINA     *********************/
        public function listar(){
            $idioma_cad = new App_Model_idiomaModel();
            $idioma_lista_cad = $idioma_cad->listaIdioma();
            $config = unserialize (CONFIG_DB);
            /********************************************/
            //CONFIGURAÇÃO DO SITE
            $dados['config'] = $config;
            //print_r($sub_master);
            if(!empty($idioma_lista_cad)):
                $dados['idioma_lista'] = $idioma_lista_cad; 
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoIdioma",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoIdioma");
            endif;
        }
   
    }