<?php
    class cargo extends App_Controller{

        public function index_action(){
            return $this->View("indexCargo");
        }

        /****************** CRUD ******************************/
        public function registar(){
                   
            //funcao que chama a view
            return $this->View("registarCargo");
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
            $model = new App_Model_cargoModel();
            $model_inserir = $model->cargoRegistar($dados);
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
            $cargo_alt = new App_Model_cargoModel();
            $cargo_lista_alt = $cargo_alt->cargoSelecionar($ident);
            $dados['cargo_lista_alt'] = $cargo_lista_alt;
          
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarCargo", $dados);
            endif;
        }

        public function alterar(){
            global $start;
            $parm = $start->_params;

            $cargo_alt = new App_Model_cargoModel();
            $cargo_lista_alt = $cargo_alt->cargoSelecionar($parm['ref']);
            $dados['cargo_lista_alt'] = $cargo_lista_alt;

            $this->View("alterarCargo", $dados);

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
            $model = new App_Model_cargoModel();
            $model_update = $model->cargoAlteracao($dados);
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
            $model = new App_Model_cargoModel();
            $model_delete = $model->cargoDeletar($ident['id']);
            echo $model_delete;
        }

        /***************     PAGINA     *********************/
        public function listar(){

            $cargo_cad = new App_Model_cargoModel();
            $cargo_lista_cad = $cargo_cad->listacargo();
            $dados['cargo_lista'] = $cargo_lista_cad;            
            
            $this->View("inscritoCargo",$dados);
            

        }

    }