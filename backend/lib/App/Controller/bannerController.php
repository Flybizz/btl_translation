<?php
    class banner extends App_Controller{
        public function index_action(){
            return $this->View("indexBanner");
        }
        /****************** CRUD ******************************/
        public function cadastrar(){
            //funcao que chama a view
            return $this->View("cadastrarBanner"); 
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
            $model = new App_Model_bannerModel();
            $model_inserir = $model->bannerCadastrar($dados);
            echo $model_inserir;
        }
        //ALTERAR
        public function selecionar($view = NULL){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");
            $banner_alt = new App_Model_bannerModel();
            $banner_lista_alt = $banner_alt->bannerSelecionar($ident);
            $dados['banner_lista_alt'] = $banner_lista_alt;
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarBanner", $dados);
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
            $model = new App_Model_bannerModel();
            $model_update = $model->bannerAlteracao($dados);
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
            $model = new App_Model_bannerModel();
            $model_delete = $model->bannerDeletar($ident['id']);
            echo $model_delete;
        }
        /***************     PAGINA     *********************/
        public function listar(){
            $banner_cad = new App_Model_bannerModel();
            $banner_lista_cad = $banner_cad->listabanner();
            global $start;
            // $parm = $start->_params;
            // if(!empty($parm['p'])):
            //     $pagination = $parm['p'];
            // else:
            //     $pagination = 1;
            // endif;
            // $pag = new App_Controller_paginacaoController();
            // $paginacao = $pag->paginacao("d028banner",NULL,"D028_Data DESC","/backend/banner/listar/p",$pagination);
            if(!empty($banner_lista_cad)):
                $dados['banner_lista'] = $banner_lista_cad;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoBanner",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoBanner");
            endif;
        }
    }