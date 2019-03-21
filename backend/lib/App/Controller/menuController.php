<?php
    class menu extends App_Controller{

        public function index_action(){

            return $this->View("indexMenu");

        }

        /****************** CRUD ******************************/

        public function cadastrar(){


            /*DADOS menu*/
            /*$model = new App_Model_menucatModel();
            $model_categoria = $model->listamenu();
            $dados['menucat'] = $model_categoria;
*/
            //funcao que chama a view
            return $this->View("cadastrarMenu");

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

            $model = new App_Model_menuModel();
            $model_inserir = $model->menuCadastrar($dados);

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

            $menu_alt = new App_Model_menuModel();
            $menu_lista_alt = $menu_alt->menuSelecionar($ident);

            $dados['menu_lista_alt'] = $menu_lista_alt;

            /*DADOS CATEGORIA*/
            /*$model = new App_Model_menucatModel();
            $model_categoria = $model->listamenu();
            $dados['menucat'] = $model_categoria;*/


            if($view != NULL):

                return $dados;

            else:

                $this->View("alterarMenu", $dados);

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

            $model = new App_Model_menuModel();
            $model_update = $model->menuAlteracao($dados);

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

            $model = new App_Model_menuModel();
            $model_delete = $model->menuDeletar($ident['id']);

            echo $model_delete;

        }

        /***************     PAGINA     *********************/

        public function listar(){

            $menu_cad = new App_Model_menuModel();
            $menu_lista_cad = $menu_cad->listaMenu2();

            $config = unserialize (CONFIG_DB);
            /********************************************/

            //CONFIGURAÇÃO DO SITE
            $dados['config'] = $config;

            $sub_master = $menu_cad->listaMaster();
            $sub_1 = $menu_cad->listaSub1();
            $sub_2 = $menu_cad->listaSub2();
            $sub_3 = $menu_cad->listaSub3();
            $sub_4 = $menu_cad->listaSub4();
            $sub_5 = $menu_cad->listaSub5();

            //print_r($sub_master);

            if(!empty($menu_lista_cad)):
                $dados['menu_lista'] = $menu_lista_cad;
                $dados['sub_master'] = $sub_master;
                $dados['sub_1'] = $sub_1;
                $dados['sub_2'] = $sub_2;
                $dados['sub_3'] = $sub_3;
                $dados['sub_4'] = $sub_4;
                $dados['sub_5'] = $sub_5;

                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoMenu",$dados);

            else:
                //funcao que chama a view
                $this->View("cadastradoMenu");
            endif;

        }

        /*SUBMENU*/

        public function scadastrar(){

            //funcao que chama a view
            return $this->View("cadastrarSubmenu");

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

            $model = new App_Model_menuModel();

            $model_sel = $model->menuSelecionar($dados['id']);

            print_r($model_sel);

            //NIVEL1
            if($model_sel[0]['D008_PertenceCodigoMaster'] == 0 && $model_sel[0]['D008_PertenceCodigoSub1'] == 0):

                $model_inserir = $model->smenuCadastrar($dados,$dados['id'],0,0,0,0,0);

                echo "NIVEL1";

            //NIVEL1
            elseif($model_sel[0]['D008_PertenceCodigoMaster'] != 0 && $model_sel[0]['D008_PertenceCodigoSub1'] == 0):

                $model_inserir = $model->smenuCadastrar($dados,$model_sel[0]['D008_PertenceCodigoMaster'],$dados['id'],0,0,0,0);

                echo "NIVEL2";
         
            //NIVEL2
            elseif($model_sel[0]['D008_PertenceCodigoMaster'] != 0 && $model_sel[0]['D008_PertenceCodigoSub1'] != 0 && $model_sel[0]['D008_PertenceCodigoSub2'] == 0):

                $model_inserir = $model->smenuCadastrar($dados,$model_sel[0]['D008_PertenceCodigoMaster'],$model_sel[0]['D008_PertenceCodigoSub1'],$dados['id'],0,0,0);
                
                echo "NIVEL3";
            //NIVEL3
            elseif($model_sel[0]['D008_PertenceCodigoMaster'] != 0 && $model_sel[0]['D008_PertenceCodigoSub1'] != 0 && $model_sel[0]['D008_PertenceCodigoSub2'] != 0 && $model_sel[0]['D008_PertenceCodigoSub3'] == 0):

                $model_inserir = $model->smenuCadastrar($dados,$model_sel[0]['D008_PertenceCodigoMaster'],$model_sel[0]['D008_PertenceCodigoSub1'],$model_sel[0]['D008_PertenceCodigoSub2'],$dados['id'],0,0);
                echo "NIVEL4";

            //NIVEL4
            elseif($model_sel[0]['D008_PertenceCodigoMaster'] != 0 && $model_sel[0]['D008_PertenceCodigoSub1'] != 0 && $model_sel[0]['D008_PertenceCodigoSub2'] != 0 && $model_sel[0]['D008_PertenceCodigoSub3'] != 0 && $model_sel[0]['D008_PertenceCodigoSub4'] == 0):
            
                $model_inserir = $model->smenuCadastrar($dados,$model_sel[0]['D008_PertenceCodigoMaster'],$model_sel[0]['D008_PertenceCodigoSub1'],$model_sel[0]['D008_PertenceCodigoSub2'],$model_sel[0]['D008_PertenceCodigoSub3'],$dados['id'],0);
                echo "NIVEL5";

            //NIVEL5
            elseif($model_sel[0]['D008_PertenceCodigoMaster'] != 0 && $model_sel[0]['D008_PertenceCodigoSub1'] != 0 && $model_sel[0]['D008_PertenceCodigoSub2'] != 0 && $model_sel[0]['D008_PertenceCodigoSub3'] != 0 && $model_sel[0]['D008_PertenceCodigoSub4'] != 0 && $model_sel[0]['D008_PertenceCodigoSub5'] == 0):

                $model_inserir = $model->smenuCadastrar($dados,$model_sel[0]['D008_PertenceCodigoMaster'],$model_sel[0]['D008_PertenceCodigoSub1'],$model_sel[0]['D008_PertenceCodigoSub2'],$model_sel[0]['D008_PertenceCodigoSub3'],$model_sel[0]['D008_PertenceCodigoSub4'],$dados['id']);
                echo "NIVEL6";

            endif;


            //$model_inserir = $model->smenuCadastrar($dados);

            //echo $model_inserir;

        }


        //ALTERAR SUBMENU
        public function salterar($view = NULL){

            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");

            $menu_alt = new App_Model_menuModel();
            $menu_lista_alt = $menu_alt->menuSelecionar($ident);

            $dados['menu_lista_alt'] = $menu_lista_alt;

       
            $this->View("alterarSubmenu", $dados);


        }        

    }