<?php
    class agenda extends App_Controller{

        public function index_action(){

            return $this->View("indexAgenda");

        }

        /****************** CRUD ******************************/

        public function cadastrar(){

            //funcao que chama a view
            return $this->View("cadastrarAgenda"); 

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

            $model = new App_Model_agendaModel();
            $model_inserir = $model->agendaCadastrar($dados);

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

            $agenda_alt = new App_Model_agendaModel();
            $agenda_lista_alt = $agenda_alt->agendaSelecionar($ident);

            $dados['agenda_lista_alt'] = $agenda_lista_alt;

            if($view != NULL):

                return $dados;

            else:

                $this->View("alterarAgenda", $dados);

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

            $model = new App_Model_agendaModel();
            $model_update = $model->agendaAlteracao($dados);

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

            $model = new App_Model_agendaModel();
            $model_delete = $model->agendaDeletar($ident['id']);

            echo $model_delete;

        }

        /***************     PAGINA     *********************/

        public function listar(){

            // $agenda_cad = new App_Model_agendaModel();
            // $agenda_lista_cad = $agenda_cad->listaAgenda();

            global $start;
            $parm = $start->_params;

            if(!empty($parm['p'])):
                $pagination = $parm['p'];
            else:
                $pagination = 1;
            endif;

            $pag = new App_Controller_paginacaoController();
            $paginacao = $pag->paginacao("d040agenda",NULL,"D040_Data,D040_Hora DESC","/backend/agenda/listar/p",$pagination);

            if(!empty($paginacao)):
                $dados['agenda_lista'] = $paginacao;

                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoAgenda",$dados);

            else:
                //funcao que chama a view
                $this->View("cadastradoAgenda");
            endif;

        }



    }