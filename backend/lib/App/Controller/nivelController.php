<?php
    class nivel extends App_Controller{
        public function index_action(){
            return $this->View("indexNivel");
        }
        /****************** CRUD ******************************/
        public function registar(){
            $nivel = new App_Model_nivelModel();
            $nivel_lista = $nivel->nivelListar2();
            $dados['nivel_nivel'] = $nivel_lista;
            $permissao = new App_Model_permissaoModel();
            $permissao_lista = $permissao->permissaoListar();
            $dados['nivel_permissao'] = $permissao_lista;
            //funcao que chama a view
            return $this->View("cadastrarNivel", $dados); 
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
            $model = new App_Model_nivelModel();
            $model_inserir = $model->nivelCadastrar($dados);
            print_r($model_inserir);
            $sel_nivel = $model->nivelListar3();
            $str_dados = substr($dados["dados"],0,-1); 
            $arr_dados = explode(",", $str_dados);
            foreach ($arr_dados as $value):
                $exp_dados = explode(":", $value);
                if($exp_dados[1] == "true"):
                    $valor = 1;
                else:
                    $valor = 0;
                endif;
                $permissao[$exp_dados[0]] = $valor; 
            endforeach;
            //$perm = array_shift($permissao);
            $arr = array("per_id" => $sel_nivel[0]['niv_id']);
            $result_permissao = array_merge($permissao, $arr);
            $model_permissao = new App_Model_permissaoModel();
            $sel_permissao = $model_permissao->permissaoCadastrar($result_permissao);
            print_r($sel_permissao);
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
            $nivel_alt = new App_Model_nivelModel();
            $nivel_lista_alt = $nivel_alt->nivelSelecionar($ident);
            $dados['nivel_lista_alt'] = $nivel_lista_alt;
            $permissao = new App_Model_permissaoModel();
            $permissao_lista = $permissao->permissaoListar3($ident);
            //$permissao_lista = $permissao->permissaoListarColunas();
            //print_r($permissao_lista_alt);
            $dados['nivel_permissao'] = $permissao_lista;
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarNivel", $dados);
            endif;
        }

        public function alterar(){

            global $start;
            $parm = $start->_params;


            // /*DADOS nivel*/
            // $model = new App_Model_moduloModel();
            // $sel_modulo = $model->listaModulo();
            // $dados['modulo'] = $sel_modulo;


            $ident = $parm["id"];
            
            $nivel_alt = new App_Model_nivelModel();
            $nivel_lista_alt = $nivel_alt->nivelSelecionar($ident);
            $dados['nivel_lista_alt'] = $nivel_lista_alt;
            $permissao = new App_Model_permissaoModel();
            $permissao_lista = $permissao->permissaoListar3($ident);

            //$permissao_lista = $permissao->permissaoListarColunas();
            //print_r($permissao_lista_alt);
            $dados['nivel_permissao'] = $permissao_lista;
            $model_permissao = new App_Model_permissaoModel();

            //$perm = array_shift($permissao);
            //$sel_permissao = $model_permissao->permissaoAlteracao($dados['niv_id'],$permissao);
            //print_r($sel_permissao);
            
            $nivel_obj = new App_Model_nivelModel();
            $nivel = $nivel_obj->nivelSelecionar($parm["id"]);
            
            $dados['nivel_info'] = $nivel;
                      
            $this->View("alterarNivel", $dados);
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

            $model = new App_Model_nivelModel();
            $dados_nivel = $model->nivelSelecionar($dados['id']);
            $model_update = $model->nivelAlteracao($dados);

            //definição de permissões
            $str_dados = substr($dados["dados"],0,-1); 
            $arr_dados = explode(",", $str_dados);            
            foreach ($arr_dados as $value):
                $exp_dados = explode(":", $value);
                if($exp_dados[1] == "true"):
                    $valor = 1;
                else:
                    $valor = 0;
                endif;
                $permissao[$exp_dados[0]] = $valor; 
            endforeach;

            //$perm = array_shift($permissao);
            $model_permissao = new App_Model_permissaoModel();
            $sel_permissao = $model_permissao->permissaoAlteracao($dados['id'],$permissao);
            
            //print_r($sel_permissao);
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


            $model = new App_Model_nivelModel();
            $model_delete = $model->nivelDeletar($ident['id']);

            if($model_delete == false){
                $output["error"] = true;
                $output["message"] = "Existem utilizadores associados ao nível que pretende apagar. A redireccionar para utilizadores...";
                echo json_encode($output);
            }
            else
                print_r($model_delete);
        }
        /***************     PAGINA     *********************/
        public function listar(){
            $nivel_cad = new App_Model_nivelModel();
            $nivel_lista_cad = $nivel_cad->nivelListar2();
 
            if(!empty($nivel_lista_cad)):
                $dados['nivel_lista'] = $nivel_lista_cad;
                //echo json_encode($nivel_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoNivel",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoNivel");
            endif;
        }
    
    }