<?php
    class especialidade extends App_Controller{

        public function index_action(){
            return $this->View("indexEspecialidade");
        }

        /****************** CRUD ******************************/
        public function registar(){
                   
            //funcao que chama a view
            return $this->View("registarEspecialidade");
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
            $model = new App_Model_especialidadeModel();
            $model_inserir = $model->especialidadeRegistar($dados);
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
            $especialidade_alt = new App_Model_especialidadeModel();
            $especialidade_lista_alt = $especialidade_alt->especialidadeSelecionar($ident);
            $dados['especialidade_lista_alt'] = $especialidade_lista_alt;
          
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarEspecialidade", $dados);
            endif;
        }

        public function alterar(){
            global $start;
            $parm = $start->_params;

            $especialidade_alt = new App_Model_especialidadeModel();
            $especialidade_lista_alt = $especialidade_alt->especialidadeSelecionar($parm['ref']);
            $dados['especialidade_lista_alt'] = $especialidade_lista_alt;

            $this->View("alterarEspecialidade", $dados);

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
            $model = new App_Model_especialidadeModel();
            $model_update = $model->especialidadeAlteracao($dados);
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
            $model = new App_Model_especialidadeModel();
            $model_delete = $model->especialidadeDeletar($ident['id']);
            echo $model_delete;
        }

        /***************     PAGINA     *********************/
        public function listar(){

            $especialidade_cad = new App_Model_especialidadeModel();
            $especialidade_lista_cad = $especialidade_cad->listaEspecialidade();
            $dados['especialidade_lista'] = $especialidade_lista_cad;
            
            $this->View("inscritoEspecialidade",$dados);
            

        }

    }