<?php
    class vendedor extends App_Controller{

        public function index_action(){
            return $this->View("indexVendedor");
        }

        /****************** CRUD ******************************/
        public function registar(){
                   
            //funcao que chama a view
            return $this->View("registarVendedor");
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
            $model = new App_Model_vendedorModel();
            $model_inserir = $model->vendedorRegistar($dados);
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
            $vendedor_alt = new App_Model_vendedorModel();
            $vendedor_lista_alt = $vendedor_alt->vendedorSelecionar($ident);
            $dados['vendedor_lista_alt'] = $vendedor_lista_alt;
          
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarVendedor", $dados);
            endif;
        }

        public function alterar(){
            global $start;
            $parm = $start->_params;

            $vendedor_alt = new App_Model_vendedorModel();
            $vendedor_lista_alt = $vendedor_alt->vendedorSelecionar($parm['ref']);
            $dados['vendedor_lista_alt'] = $vendedor_lista_alt;

            $areas_negocio_alt = new App_Model_areaModel();
            $areas_negocio_lista_alt = $areas_negocio_alt->listaArea();
            $dados["areas_negocio"] = $areas_negocio_lista_alt;

            $this->View("alterarVendedor", $dados);

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
            $model = new App_Model_vendedorModel();
            $model_update = $model->vendedorAlteracao($dados);
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
            $model = new App_Model_vendedorModel();
            $model_delete = $model->vendedorDeletar($ident['id']);
            echo $model_delete;
        }

        /***************     PAGINA     *********************/
        public function listar(){

            $vendedor_cad = new App_Model_vendedorModel();
            $vendedor_lista_cad = $vendedor_cad->listavendedor();
            $dados['vendedor_lista'] = $vendedor_lista_cad;            
            
            $this->View("inscritoVendedor",$dados);
            

        }

    }