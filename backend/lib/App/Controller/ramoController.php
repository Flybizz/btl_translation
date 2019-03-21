<?php
    class ramo extends App_Controller{

        public function index_action(){

            return $this->View("indexRamo");

        }

        /****************** CRUD ******************************/

        public function cadastrar(){

            /*
                DADOS ramo
                $model = new App_Model_ramocatModel();
                $model_categoria = $model->listaramo();
                $dados['ramocat'] = $model_categoria;
            */

            //funcao que chama a view
            return $this->View("cadastrarRamo");

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

            $model = new App_Model_ramoModel();
            $model_inserir = $model->ramoCadastrar($dados);

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

            $ramo_alt = new App_Model_ramoModel();
            $ramo_lista_alt = $ramo_alt->ramoSelecionar($ident);

            $dados['ramo_lista_alt'] = $ramo_lista_alt;

            /*DADOS CATEGORIA*/
            /*$model = new App_Model_ramocatModel();
            $model_categoria = $model->listaramo();
            $dados['ramocat'] = $model_categoria;*/


            if($view != NULL):

                return $dados;

            else:

                $this->View("alterarRamo", $dados);

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

            $model = new App_Model_ramoModel();
            $model_update = $model->ramoAlteracao($dados);

            echo $model_update;

        }

        //ALTERAR SUB RAMO
        public function selecionars($view = NULL){

            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");

            $ramo_alt = new App_Model_ramoModel();
            $ramo_lista_alt = $ramo_alt->ramoSelecionar($ident);

            $dados['ramo_lista_sub'] = $ramo_lista_alt;

            /*DADOS CATEGORIA*/
            /*$model = new App_Model_ramocatModel();
            $model_categoria = $model->listaramo();
            $dados['ramocat'] = $model_categoria;*/


            if($view != NULL):

                return $dados;

            else:

                $this->View("alterarSramo", $dados);

            endif;

        }

        //ALTERAÇÃO
        public function alteracaos(){

            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            $model = new App_Model_ramoModel();
            $model_update = $model->ramoAlteracaos($dados);

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

            $model = new App_Model_ramoModel();

            $model_delete = $model->sramoDeletar($ident['id']);
            $model_delete = $model->ramoDeletar($ident['id']);

            echo $model_delete;

        }

        /***************     PAGINA     *********************/

        public function listar(){

            $ramo_cad = new App_Model_ramoModel();
            // $ramo_lista_cad = $ramo_cad->listaramo2();



            $sub_master = $ramo_cad->listaMaster();
            //print_r($sub_master);

            global $start;
            $parm = $start->_params;

            if(!empty($parm['p'])):
                $pagination = $parm['p'];
            else:
                $pagination = 1;
            endif;

            $pag = new App_Controller_paginacaoController();
            $paginacao = $pag->paginacao("d008ramo",NULL,"D008_Ordem DESC","/backend/ramo/listar/p",$pagination);

            if(!empty($paginacao)):

                $dados['ramo_lista'] = $paginacao;
                $dados['sub_master'] = $sub_master;

                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoRamo",$dados);

            else:
                //funcao que chama a view
                $this->View("cadastradoRamo");
            endif;

        }

        /*SUBramo*/

        public function scadastrar(){

            //funcao que chama a view
            return $this->View("cadastrarSubramo");

        }

        public function sinserir(){

            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            //print_r($dados);

            $model = new App_Model_ramoModel();

            $model_sel = $model->ramoSelecionar($dados['id']);

            //print_r($model_sel);

            //NIVEL1
            if($model_sel[0]['D008_PertenceCodigoMaster'] == 0):

                $model_inserir = $model->sramoCadastrar($dados,$dados['id']);

                //echo "NIVEL1";

                echo $model_inserir;

            endif;

        }


    }