<?php
    class usuario extends App_Controller{
        
        public function index_action(){
            return $this->View("indexUsuario");
        }

        /****************** CRUD ******************************/
        public function registar(){

            $model = new App_Model_moduloModel();
            $sel_modulo = $model->listaModulo();
            $dados['modulo'] = $sel_modulo;

            $nivel = new App_Model_nivelModel();
            $nivel_lista = $nivel->nivelListar2();
            $dados['usuario_nivel'] = $nivel_lista;

            
            return $this->View("cadastrarUsuario", $dados); 
        }
        
        public function inserir(){
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            $model = new App_Model_usuarioModel();
            $languageLIST = unserialize (LANGUAGE_LIST);
            $ref = rand(100, 999999);
                        
            if(!empty($languageLIST)):
                foreach ($languageLIST as $idioma):  
                                    
                    $arr = array(  
                        'nome' => trim($dados['nome']),
                        'funcao' => trim($dados['funcao']),
                        'texto' => $dados['texto'],
                        'img' => $dados['img'],
                        'controller' => $dados['controller'],
                        'idioma' => $idioma["D008_Sigla"],
                        'status' =>  $dados['status'],
                        'morada' =>  $dados['morada'],
                        'localidade' =>  $dados['localidade'],
                        'distrito' =>  $dados['distrito'],
                        'cp' =>  $dados['cp'],
                        'telefone' =>  $dados['telefone'],
                        'telemovel' =>  $dados['telemovel'],
                        'email' =>  $dados['email'],
                        'website' =>  $dados['website'],
                        'login' =>  $dados['login'],
                        'senha' =>  $dados['senha'],
                        'nivel' =>  $dados['nivel'],
                        'referencia' => $ref
                    );

                    $model_inserir = $model->usuarioCadastrar($arr);

                endforeach;
            endif;

            print_r($model_inserir);
        }

        public function selecionar($view = NULL){

            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");
            $usuario_alt = new App_Model_usuarioModel();
            $usuario_lista_alt = $usuario_alt->usuarioSelecionar($ident);
            $dados['usuario_lista_alt'] = $usuario_lista_alt;
            $nivel = new App_Model_nivelModel();
            $nivel_lista = $nivel->nivelListar2();
            $dados['usuario_nivel'] = $nivel_lista;
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarUsuario", $dados);
            endif;
        }

         public function alterar(){

            global $start;
            $parm = $start->_params;
                      
            $usuario_alt = new App_Model_usuarioModel();
            $usuario_lista_alt = $usuario_alt->usuarioSelecionarRef($parm['ref'], $parm['lang']);
            $dados['usuario_lista_alt'] = $usuario_lista_alt;

            /*DADOS usuario*/
            $model = new App_Model_moduloModel();
            $sel_modulo = $model->listaModulo();
            $dados['modulo'] = $sel_modulo;

            $nivel = new App_Model_nivelModel();
            $nivel_lista = $nivel->nivelListar2();
            $dados['usuario_nivel'] = $nivel_lista;
                      
            $this->View("alterarUsuario", $dados);
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

            $model = new App_Model_usuarioModel();
            $dados_usuario = $model->usuarioSelecionar($dados['id']);

            if(empty($dados["img"])): 
                $dados["img"] = $dados_usuario[0]['usu_foto'];
            elseif(!empty($dados_usuario[0]['usu_foto'])):
                unlink($_SERVER['DOCUMENT_ROOT'].'/images/usuario/'.$dados_usuario[0]['usu_foto']);
            endif;
            $model_update = $model->usuarioAlteracao($dados);

            if($model_update){
                echo "Dados actualizados com sucesso";
            }

            //print_r($model_update);
        }

        //DELETAR IMAGEM
        public function img_deletar(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $model = new App_Model_usuarioModel();
            $dados_usuario = $model->usuarioSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/usuario/'.$dados_usuario[0]['usu_foto']);
            $model_delete = $model->usuarioDeletarIMG($ident['id']);
            echo $model_delete;
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
            $model = new App_Model_usuarioModel();
            $dados_usuario = $model->usuarioSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/usuario/'.$dados_usuario[0]['usu_foto']);
            $model_delete = $model->usuarioDeletar($ident['id']);
            echo $model_delete;
        }

        /***************     Usuario     *********************/
        public function listar(){

            $languageDEFAULT = unserialize (LANGUAGE_DEFAULT);

            $usuario = new App_Model_usuarioModel();
            $sel_usuario = $usuario->listaUsuario();     

            /*DADOS usuario*/       
            if(!empty($sel_usuario)):
                $dados['usuario_lista'] = $sel_usuario;
                //funcao que chama a view
                $this->View("cadastradoUsuario",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoUsuario");
            endif;

        }

        /* RECUPERAR PASSWORD */        
        public function verifica(){

            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $email = trim($id->getParamsAjax("email"));

            if(!empty($email)):

                $model = new App_Model_usuarioModel();
                $model_verifica = $model->usuarioVerifica($email);

                if(empty($model_verifica)):
                    $rs = 0;
                else:
                    $rs = 1;
                endif;

                echo $rs;

            endif;

        }

        //ALTERAÇÃO
        public function update(){

            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            $model_utilizador = new App_Model_usuarioModel();
            $utilizador = $model_utilizador->usuarioUpdateSenha($dados["usuario"],$dados["senha"]);
            $clear_usuario = $model_utilizador->usuarioRecuperarClear($dados["usuario"]);

            echo $utilizador;

        }
}