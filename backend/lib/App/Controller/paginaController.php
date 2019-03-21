<?php
    class pagina extends App_Controller{
        public function index_action(){
            return $this->View("indexPagina");
        }
        /****************** CRUD ******************************/
        public function registar(){
            /*DADOS pagina*/
           /*  $model = new App_Model_paginacatModel();
            $model_categoria = $model->listaPagina(); */
            $dados['paginacat'] = array();
           
            //funcao que chama a view
            return $this->View("cadastrarPagina",$dados); 
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
            $model = new App_Model_paginaModel();

            $languageLIST = unserialize (LANGUAGE_LIST);
            $ref = rand(100, 999999);
            
            if(!empty($languageLIST)):
                foreach ($languageLIST as $idioma):  
                                    
                    $arr = array(  
                        'titulo' => trim($dados['titulo']),
                        'subtitulo' => trim($dados['subtitulo']),
                        'seo_slug' => $dados['seo_slug'],
                        'chamada' => $dados['chamada'],
                        'texto' => $dados['texto'],
                        'img' => $dados['img'],
                        'controller' => $dados['controller'],
                        'idioma' => $idioma["D008_Sigla"],
                        'destaque' =>  $dados['destaque'],
                        'tipovideo' =>  $dados['tipovideo'],
                        'codevideo' =>  $dados['codevideo'],
                        'seo_titulo' =>  $dados['seo_titulo'],
                        'seo_descricao' =>  $dados['seo_descricao'],
                        'seo_slug' =>  $dados['seo_slug'],
                        'seo_key' =>  $dados['seo_key'],
                        'referencia' => $ref
                    );

                    $model_inserir = $model->paginaCadastrar($arr);

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
            $pagina_alt = new App_Model_paginaModel();
            $pagina_lista_alt = $pagina_alt->paginaSelecionar($ident);
            $dados['pagina_lista_alt'] = $pagina_lista_alt;
            /*DADOS CATEGORIA*/
            $model = new App_Model_paginacatModel();
            $model_categoria = $model->listaPagina();
            $dados['paginacat'] = $model_categoria;
            /*DADOS GALERIA*/
            $model = new App_Model_galeriaModel();
            $model_galeria = $model->listaGaleria();
            $dados['galeria'] = $model_galeria;
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarPagina", $dados);
            endif;
        }
        public function alterar(){

            global $start;
            $parm = $start->_params;
                      
            $pagina_alt = new App_Model_paginaModel();
            $pagina_lista_alt = $pagina_alt->paginaSelecionarRef($parm['ref'], $parm['lang']);
            $dados['pagina_lista_alt'] = $pagina_lista_alt;
                      
            $this->View("alterarPagina", $dados);
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

            $model = new App_Model_paginaModel();
            $dados_pagina = $model->paginaSelecionar($dados['id']);

            if(empty($dados["img"])): 
                $dados["img"] = $dados_pagina[0]['D007_Foto'];
            elseif(!empty($dados_pagina[0]['D007_Foto'])):
                unlink($_SERVER['DOCUMENT_ROOT'].'/images/pagina/'.$dados_pagina[0]['D007_Foto']);
            endif;
            $model_update = $model->paginaAlteracao($dados);
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
            $model = new App_Model_paginaModel();
            $dados_pagina = $model->paginaSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/pagina/'.$dados_pagina[0]['D007_Foto']);
            $model_delete = $model->paginaDeletarIMG($ident['id']);
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
            $model = new App_Model_paginaModel();
            $dados_pagina = $model->paginaSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/pagina/'.$dados_pagina[0]['D007_Foto']);
            $model_delete = $model->paginaDeletar($ident['id']);
            echo $model_delete;
        }

        /***************     PAGINA     *********************/
        public function listar(){

            $languageDEFAULT = unserialize (LANGUAGE_DEFAULT);

            $pagina_cad = new App_Model_paginaModel();
            $pagina_lista_cad = $pagina_cad->listaPaginaDefault($languageDEFAULT[0]);            

            /*DADOS pagina*/       
            if(!empty($pagina_lista_cad)):
                $dados['pagina_lista'] = $pagina_lista_cad;
                //funcao que chama a view
                $this->View("cadastradoPagina",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoPagina");
            endif;

        }
        /***************     CATEGORIA pagina     *********************/
        public function listarcat(){
            $pagina_cad = new App_Model_paginaModel();
            $pagina_lista_cad = $pagina_cad->listaPagina();
            if(!empty($pagina_lista_cad)):
                $dados['pagina_lista'] = $pagina_lista_cad;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoCatPagina",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoCatPagina");
            endif;
        }
    }