<?php
    class cursocat extends App_Controller{
        public function index_action(){
            return $this->View("indexCurso");
        }
        /****************** CRUD ******************************/
        public function cadastrar(){
            //funcao que chama a view
            return $this->View("cadastrarCursocat"); 
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
            $model = new App_Model_cursocatModel();
            $model_inserir = $model->cursoCadastrar($dados);
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
            $curso_alt = new App_Model_cursocatModel();
            $curso_lista_alt = $curso_alt->cursoSelecionar($ident);
            $dados['curso_lista_alt'] = $curso_lista_alt;
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarCursocat", $dados);
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
            $model = new App_Model_cursocatModel();
            $model_update = $model->cursoAlteracao($dados);
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
            $model = new App_Model_cursocatModel();
            $model_delete = $model->cursoDeletar($ident['id']);
            echo $model_delete;
        }
        /***************     PAGINA     *********************/
        public function listar(){
            $curso_cad = new App_Model_cursocatModel();
            $curso_lista_cad = $curso_cad->listaCurso();
            global $start;
            // $parm = $start->_params;
            // if(!empty($parm['p'])):
            //     $pagination = $parm['p'];
            // else:
            //     $pagination = 1;
            // endif;
            // $pag = new App_Controller_paginacaoController();
            // $paginacao = $pag->paginacao("d016curso_cat",NULL,"D016_Nome ASC","/backend/cursocat/listar/p",$pagination);
            if(!empty($curso_lista_cad)):
                $dados['curso_lista'] = $curso_lista_cad;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoCursocat",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoCursocat");
            endif;
        }
    }