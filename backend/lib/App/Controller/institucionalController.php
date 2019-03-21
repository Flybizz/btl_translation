<?php
    class institucional extends App_Controller{
        public function index_action(){
            return $this->View("indexInstitucional");
        }
        /****************** CRUD ******************************/
        public function registar(){
            /*DADOS institucional*/
           /*  $model = new App_Model_institucionalcatModel();
            $model_categoria = $model->listaInstitucional(); */
            $dados['institucionalcat'] = array();
           
            //funcao que chama a view
            return $this->View("cadastrarInstitucional",$dados); 
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
            $model = new App_Model_institucionalModel();

            $languageLIST = unserialize (LANGUAGE_LIST);
            $ref = rand(100, 999999);
            echo $ref;
            
            if(!empty($languageLIST)):
                foreach ($languageLIST as $idioma):  
                                    
                    $arr = array(  
                        'titulo' => trim($dados['titulo']),
                        'seo_slug' => $dados['seo_slug'],
                        'chamada' => $dados['chamada'],
                        'texto' => $dados['texto'],
                        'img' => $dados['img'],
                        'controller' => $dados['controller'],
                        'idioma' => $idioma["D008_Sigla"],
                        'destaque' =>  $dados['destaque'],
                        'seo_titulo' =>  $dados['seo_titulo'],
                        'seo_descricao' =>  $dados['seo_descricao'],
                        'seo_slug' =>  $dados['seo_slug'],
                        'seo_key' =>  $dados['seo_key'],
                        'referencia' => $ref
                    );

                    $model_inserir = $model->institucionalCadastrar($arr);

                endforeach;
            endif;

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
            $institucional_alt = new App_Model_institucionalModel();
            $institucional_lista_alt = $institucional_alt->institucionalSelecionar($ident);
            $dados['institucional_lista_alt'] = $institucional_lista_alt;
            /*DADOS CATEGORIA*/
            $model = new App_Model_institucionalcatModel();
            $model_categoria = $model->listaInstitucional();
            $dados['institucionalcat'] = $model_categoria;
            /*DADOS GALERIA*/
            $model = new App_Model_galeriaModel();
            $model_galeria = $model->listaGaleria();
            $dados['galeria'] = $model_galeria;
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarInstitucional", $dados);
            endif;
        }
        public function alterar(){

            global $start;
            $parm = $start->_params;
                      
            $institucional_alt = new App_Model_institucionalModel();
            $institucional_lista_alt = $institucional_alt->institucionalSelecionarRef($parm['ref'], $parm['lang']);
            $dados['institucional_lista_alt'] = $institucional_lista_alt;
                      
            $this->View("alterarInstitucional", $dados);
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

            $model = new App_Model_institucionalModel();
            $dados_institucional = $model->institucionalSelecionar($dados['id']);

            if(empty($dados["img"])): 
                $dados["img"] = $dados_institucional[0]['D007_Foto'];
            elseif(!empty($dados_institucional[0]['D007_Foto'])):
                unlink($_SERVER['DOCUMENT_ROOT'].'/images/institucional/'.$dados_institucional[0]['D007_Foto']);
            endif;
            $model_update = $model->institucionalAlteracao($dados);
            echo $model_update;
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
            $model = new App_Model_institucionalModel();
            $dados_institucional = $model->institucionalSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/institucional/'.$dados_institucional[0]['D007_Foto']);
            $model_delete = $model->institucionalDeletarIMG($ident['id']);
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
            $model = new App_Model_institucionalModel();
            $dados_institucional = $model->institucionalSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/institucional/'.$dados_institucional[0]['D007_Foto']);
            $model_delete = $model->institucionalDeletar($ident['id']);
            echo $model_delete;
        }

        /***************     Institucional     *********************/
        public function listar(){

            $languageDEFAULT = unserialize (LANGUAGE_DEFAULT);

            $institucional_cad = new App_Model_institucionalModel();
            $institucional_lista_cad = $institucional_cad->listaInstitucionalDefault($languageDEFAULT[0]);            

            /*DADOS institucional*/       
            if(!empty($institucional_lista_cad)):
                $dados['institucional_lista'] = $institucional_lista_cad;
                //funcao que chama a view
                $this->View("cadastradoInstitucional",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoInstitucional");
            endif;

        }
        /***************     CATEGORIA institucional     *********************/
        public function listarcat(){
            $institucional_cad = new App_Model_institucionalModel();
            $institucional_lista_cad = $institucional_cad->listaInstitucional();
            if(!empty($institucional_lista_cad)):
                $dados['institucional_lista'] = $institucional_lista_cad;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoCatInstitucional",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoCatInstitucional");
            endif;
        }
    }