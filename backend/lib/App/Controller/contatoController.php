<?php

    class contato extends App_Controller{

        public function index_action(){

            return $this->View("indexContato");

        }

        /****************** CRUD ******************************/

        public function cadastrar(){

            //funcao que chama a view

            return $this->View("cadastrarContato"); 

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

            $model = new App_Model_contatoModel();

            $model_inserir = $model->contatoCadastrar($dados);

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

            $contato_alt = new App_Model_contatoModel();

            $contato_lista_alt = $contato_alt->contatoSelecionar($ident);

            $dados['contato_lista_alt'] = $contato_lista_alt;

            if($view != NULL):

                return $dados;

            else:

                $this->View("alterarContato", $dados);

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

            $model = new App_Model_contatoModel();

            $model_update = $model->contatoAlteracao($dados);

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

            $model = new App_Model_contatoModel();

            $model_delete = $model->contatoDeletar($ident['id']);

            echo $model_delete;

        }

        //VIEW

        public function viewcontato(){

            // seta o id do cliente

            $id = new App_System();

            $id->_urlAjax = $_POST['url'];

            $id->setExplodeAjax();

            $id->setControllerAjax();

            $id->setActionAjax();

            $id->setParamsAjax();

            $ident = $id->getParamsAjax();

            $model = new App_Model_contatoModel();

            $model_view = $model->contatoSelecionar($ident['id']);



            $dados['contato'] = $model_view;



            $this->View("visualizarContato", $dados);

           

            // if(!empty($model_view)):

            //     return $dados;

            // else:

            //     $this->View("visualizarContato", $dados);

            // endif;

        }

        //VIEW

        public function check(){

            // seta o id do cliente

            $id = new App_System();

            $id->_urlAjax = $_POST['url'];

            $id->setExplodeAjax();

            $id->setControllerAjax();

            $id->setActionAjax();

            $id->setParamsAjax();

            $ident = $id->getParamsAjax();

            

            $model = new App_Model_contatoModel();

            $model_view = $model->contatoAlterarStatus($ident);



            echo $model_view;

           

            // if(!empty($model_view)):

            //     return $dados;

            // else:

            //     $this->View("visualizarContato", $dados);

            // endif;

        }

        /***************     PAGINA     *********************/

        public function listar(){

            $contato_cad = new App_Model_contatoModel();
            $contato_lista_cad = $contato_cad->listacontato();            

            if(!empty($contato_lista_cad)):
                $dados['contato_lista'] = $contato_lista_cad;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoContato",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoContato");
            endif;

        }

    }