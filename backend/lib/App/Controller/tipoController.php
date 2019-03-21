<?php
    class tipo extends App_Controller{

        public function index_action(){
            return $this->View("indexTipo");
        }

        /****************** CRUD ******************************/
        public function registar(){
                   
            //funcao que chama a view
            return $this->View("registarTipo");
        }
      
        public function inserir(){
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            // print_r($dados);
            $model = new App_Model_tipoModel();
            $model_inserir = $model->tipoRegistar($dados);
            print_r($model_inserir);
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
            $tipo_alt = new App_Model_tipoModel();
            $tipo_lista_alt = $tipo_alt->tipoSelecionar($ident);
            $dados['tipo_lista_alt'] = $tipo_lista_alt;
          
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarTipo", $dados);
            endif;
        }

        public function alterar(){
            global $start;
            $parm = $start->_params;

            $tipo_alt = new App_Model_tipoModel();
            $tipo_lista_alt = $tipo_alt->tipoSelecionar($parm['ref']);
            $dados['tipo_lista_alt'] = $tipo_lista_alt;

            $this->View("alterarTipo", $dados);

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
            $model = new App_Model_tipoModel();
            $model_update = $model->tipoAlteracao($dados);
            print_r($model_update);
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
            $model = new App_Model_tipoModel();
            $model_delete = $model->tipoDeletar($ident['id']);
            echo $model_delete;
        }

        /***************     PAGINA     *********************/
        public function listar(){

            $tipo_cad = new App_Model_tipoModel();
            $tipo_lista_cad = $tipo_cad->listatipo();
            $dados['tipo_lista'] = $tipo_lista_cad;            
            
            $this->View("inscritoTipo",$dados);
            

        }

    }