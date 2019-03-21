<?php
    class curso extends App_Controller{
        public function index_action(){
            return $this->View("indexCurso");
        }
        /****************** CRUD ******************************/
        public function cadastrar(){
            /*DADOS Curso*/
            $model = new App_Model_cursocatModel();
            $model_categoria = $model->listaCurso();
            $dados['cursocat'] = $model_categoria;
            $atracao = new App_Model_equipeModel();
            $model_atracao = $atracao->listaEquipe2();
            $dados['atracao'] = $model_atracao;
            //funcao que chama a view
            return $this->View("cadastrarCurso", $dados);
        }
        public function add(){
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $atracao = new App_Model_equipeModel();
            $model_atracao = $atracao->listaEquipe2();
            $info = '
            <div id="row'.$dados["row"].'" class="form-group form-group-default" style="position:relative;">
                <label>Atração '.$dados["row"].'</label>
                <button data-remove="remove" data-id="row'.$dados["row"].'" class="btn btn-danger btn-small" style="position: absolute; top:10px; right:15px;"> <i class="fa fa-remove"></i> </button>
                <select id="eve_atracao_'.$dados["row"].'" data-tipo="atracao" class="form-control">
                  <option value=""></option> ';
                 
                  if(!empty($model_atracao)):
                    foreach ($model_atracao as $atracao): 
                    $info .= "<option value='".$atracao['D016_Uid']."'>".$atracao['D016_Titulo']."</option>";
                    endforeach; 
                  endif;
            
            $info .= '   
                </select>
            </div>';
            echo $info;
        }
        public function inserir(){
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            //print_r($dados);
            $model = new App_Model_cursoModel();
            $model_inserir = $model->cursoCadastrar($dados);
            $model_select = $model->listaEndcurso2();
            $produtoID = $model_select[0]["D016_Uid"];
            if (is_dir($_SERVER["DOCUMENT_ROOT"] . "/images/aula/" . $produtoID)) {
            } else {
                mkdir($_SERVER["DOCUMENT_ROOT"] . "/images/aula/" . $produtoID, 0777);
            }
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
            $curso_alt = new App_Model_cursoModel();
            $curso_lista_alt = $curso_alt->cursoSelecionar($ident);
            $dados['curso_lista_alt'] = $curso_lista_alt;
            /*DADOS Curso*/
            $model = new App_Model_cursocatModel();
            $model_categoria = $model->listaCurso();
            $dados['cursocat'] = $model_categoria;
            $atracao = new App_Model_equipeModel();
            $model_atracao = $atracao->listaEquipe2();
            $dados['atracao'] = $model_atracao;
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarCurso", $dados);
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
            $model = new App_Model_cursoModel();
            $dados_alt = $model->cursoSelecionar($dados['id']);
            if(empty($dados["img"])): 
                $dados["img"] = $dados_alt[0]['D016_Foto'];
            else:
                unlink($_SERVER['DOCUMENT_ROOT'].'/images/curso/'.$dados_alt[0]['D016_Foto']);
            endif;
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
            $model = new App_Model_cursoModel();
            $dados_del = $model->cursoSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/curso/'.$dados_del[0]['D016_Foto']);
            $model_delete = $model->cursoDeletar($ident['id']);
            echo $model_delete;
        }
        /***************     PAGINA     *********************/
        public function listar(){
            $curso_cad = new App_Model_cursoModel();
           
            global $start;
            $parm = $start->_params;
            if($parm['cat'] == "all"):
                $curso_lista_cad = $curso_cad->listacurso();
            else:                
                $curso_lista_cad = $curso_cad->cursoCat($parm['cat']);
            endif;
            $dados["categoria_cur"] = $parm['cat'];
            if(!empty($curso_lista_cad)):
                $dados['curso_lista'] = $curso_lista_cad;
                /*DADOS Curso*/
                $model = new App_Model_cursocatModel();
                $model_categoria = $model->listaCurso();
                $dados['cursocat'] = $model_categoria;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoCurso",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoCurso");
            endif;
        }
          public function disciplina(){
            $curso_cad = new App_Model_cursoModel();
            // $curso_lista_cad = $curso_cad->listacurso();
            global $start;
            $parm = $start->_params;
        
            $model = new App_Model_cursocatModel();
            $sel_curso = $model->cursoSelecionarChave($parm['ref']);  
            $curso_lista_cad = $curso_cad->cursoCat($sel_curso[0]['D016_Uid']);

            $dados["categoria_cur"] = $parm['ref'];
            $dados["curso"] = $sel_curso[0]['D016_Nome'];
            $dados["curso_chave"] = $sel_curso[0]['D016_Chave'];
            if(!empty($curso_lista_cad)):
                $dados['curso_lista'] = $curso_lista_cad;            
                /*DADOS Curso*/
                $model_categoria = $model->listaCurso();
                $dados['cursocat'] = $model_categoria;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoCurso",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoCurso");
            endif;
        }
    }