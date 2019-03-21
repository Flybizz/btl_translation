<?php
    class homepage extends App_Controller{
        public function index_action(){
            return $this->View("indexHomepage");
        }
        /****************** CRUD ******************************/
        public function registar(){
            /*DADOS homepage*/
           /*  $model = new App_Model_homepagecatModel();
            $model_categoria = $model->listaHomepage(); */
            $dados['homepagecat'] = array();
           
            //funcao que chama a view
            return $this->View("cadastrarHomepage",$dados); 
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
            $model = new App_Model_homepageModel();

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
                        'link' =>  $dados['link'],
                        'ordem' =>  $dados['ordem'],
                        'seo_titulo' =>  $dados['seo_titulo'],
                        'seo_descricao' =>  $dados['seo_descricao'],
                        'seo_slug' =>  $dados['seo_slug'],
                        'seo_key' =>  $dados['seo_key'],
                        'referencia' => $ref
                    );

                    $model_inserir = $model->homepageCadastrar($arr);

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
            $homepage_alt = new App_Model_homepageModel();
            $homepage_lista_alt = $homepage_alt->homepageSelecionar($ident);
            $dados['homepage_lista_alt'] = $homepage_lista_alt;
            /*DADOS CATEGORIA*/
            $model = new App_Model_homepagecatModel();
            $model_categoria = $model->listaHomepage();
            $dados['homepagecat'] = $model_categoria;
            /*DADOS GALERIA*/
            $model = new App_Model_galeriaModel();
            $model_galeria = $model->listaGaleria();
            $dados['galeria'] = $model_galeria;
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarHomepage", $dados);
            endif;
        }
        public function alterar(){

            global $start;
            $parm = $start->_params;
                      
            $homepage_alt = new App_Model_homepageModel();
            $homepage_lista_alt = $homepage_alt->homepageSelecionarRef($parm['ref'], $parm['lang']);
            $dados['homepage_lista_alt'] = $homepage_lista_alt;
                      
            $this->View("alterarHomepage", $dados);
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

            $model = new App_Model_homepageModel();
            $dados_homepage = $model->homepageSelecionar($dados['id']);

            if(empty($dados["img"])): 
                $dados["img"] = $dados_homepage[0]['D007_Foto'];
            elseif(!empty($dados_homepage[0]['D007_Foto'])):
                unlink($_SERVER['DOCUMENT_ROOT'].'/images/homepage/'.$dados_homepage[0]['D007_Foto']);
            endif;
            $model_update = $model->homepageAlteracao($dados);
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
            $model = new App_Model_homepageModel();
            $dados_homepage = $model->homepageSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/homepage/'.$dados_homepage[0]['D007_Foto']);
            $model_delete = $model->homepageDeletarIMG($ident['id']);
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
            $model = new App_Model_homepageModel();
            $dados_homepage = $model->homepageSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/homepage/'.$dados_homepage[0]['D007_Foto']);
            $model_delete = $model->homepageDeletar($ident['id']);
            echo $model_delete;
        }

        /***************     Homepage     *********************/
        public function listar(){

            $languageDEFAULT = unserialize (LANGUAGE_DEFAULT);

            $homepage_cad = new App_Model_homepageModel();
            $homepage_lista_cad = $homepage_cad->listaHomepageDefault($languageDEFAULT[0]);            

            /*DADOS homepage*/       
            if(!empty($homepage_lista_cad)):
                $dados['homepage_lista'] = $homepage_lista_cad;
                //funcao que chama a view
                $this->View("cadastradoHomepage",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoHomepage");
            endif;

        }
        /***************     CATEGORIA homepage     *********************/
        public function listarcat(){
            $homepage_cad = new App_Model_homepageModel();
            $homepage_lista_cad = $homepage_cad->listaHomepage();
            if(!empty($homepage_lista_cad)):
                $dados['homepage_lista'] = $homepage_lista_cad;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoCatHomepage",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoCatHomepage");
            endif;
        }
    }