<?php
    class distrito extends App_Controller{
        public function index_action(){
            return $this->View("indexDistrito");
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
            
            $model = new App_Model_distritoModel();
            $model_view = $model->distritoAlterarStatus($ident);
            echo $model_view;
           
            // if(!empty($model_view)):
            //     return $dados;
            // else:
            //     $this->View("visualizarDistrito", $dados);
            // endif;
        }

        /***************     PAGINA     *********************/
        public function listar(){
            // $distrito_cad = new App_Model_distritoModel();
            // $distrito_lista_cad = $distrito_cad->listadistrito();
            global $start;
            $parm = $start->_params;
            if(!empty($parm['p'])):
                $pagination = $parm['p'];
            else:
                $pagination = 1;
            endif;
            $pag = new App_Controller_paginacaoController();
            $paginacao = $pag->paginacao("btl_distrito",NULL,"nome_distrito ASC","/backend/distrito/listar/p",$pagination);
            if(!empty($paginacao)):
                $dados['distrito_lista'] = $paginacao;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("inscritoDistrito",$dados);
            else:
                //funcao que chama a view
                $this->View("inscritoDistrito");
            endif;
        }

    }