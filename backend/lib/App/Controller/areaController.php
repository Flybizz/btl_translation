<?php
    class area extends App_Controller{

        public function index_action(){
            return $this->View("indexArea");
        }

        /****************** CRUD ******************************/
        public function registar(){
                   
            //funcao que chama a view
            return $this->View("registarArea");
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
            $model = new App_Model_areaModel();
            $model_inserir = $model->areaRegistar($dados);
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
            $area_alt = new App_Model_areaModel();
            $area_lista_alt = $area_alt->areaSelecionar($ident);
            $dados['area_lista_alt'] = $area_lista_alt;
          
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarArea", $dados);
            endif;
        }

        public function alterar(){
            global $start;
            $parm = $start->_params;

            $area_alt = new App_Model_areaModel();
            $area_lista_alt = $area_alt->areaSelecionar($parm['ref']);
            $dados['area_lista_alt'] = $area_lista_alt;

            $this->View("alterarArea", $dados);

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
            $model = new App_Model_areaModel();
            $model_update = $model->areaAlteracao($dados);
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
            $model = new App_Model_areaModel();
            $model_delete = $model->areaDeletar($ident['id']);
            echo $model_delete;
        }

        /***************     PAGINA     *********************/
        public function listar(){

            $area_cad = new App_Model_areaModel();
            $area_lista_cad = $area_cad->listaarea();
            $dados['area_lista'] = $area_lista_cad;            
            
            $this->View("inscritoArea",$dados);
            

        }

    }