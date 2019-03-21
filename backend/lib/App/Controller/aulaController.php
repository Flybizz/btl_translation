<?php
    class aula extends App_Controller{
        public function index_action(){
            return $this->View("indexAula");
        }
        /****************** CRUD ******************************/
        public function cadastrar(){
            /*DADOS Aula*/
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();   
            
            $equipe = new App_Model_equipeModel();
            $model_equipe = $equipe->listaEquipe2();
            $dados['tutor'] = $model_equipe;

            $model = new App_Model_cursoModel();
            $sel_disciplina = $model->cursoChave($ident['ref']);
            $dados['disciplina'] = $sel_disciplina[0]['D016_Uid'];


            //funcao que chama a view
            return $this->View("cadastrarAula", $dados);
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
                <label>Tutor '.$dados["row"].'</label>
                <button data-remove="remove" data-id="row'.$dados["row"].'" class="btn btn-danger btn-small" style="position: absolute; top:10px; right:15px;"> <i class="fa fa-remove"></i> </button>
                <select id="aula_tutor_'.$dados["row"].'" data-tipo="tutor" class="form-control">
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
            $model = new App_Model_aulaModel();
            $model_inserir = $model->aulaCadastrar($dados);
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
            $aula_alt = new App_Model_aulaModel();
            $aula_lista_alt = $aula_alt->aulaSelecionar($ident);
            $dados['aula_lista_alt'] = $aula_lista_alt;
           
            /*DADOS Aula*/

            $atracao = new App_Model_equipeModel();
            $model_atracao = $atracao->listaEquipe2();
            $dados['tutor'] = $model_atracao;
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarAula", $dados);
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
            $model = new App_Model_aulaModel();
            $dados_alt = $model->aulaSelecionar($dados['id']);
            if(empty($dados["img"])): 
                $dados["img"] = $dados_alt[0]['aula_Foto'];
            else:
                unlink($_SERVER['DOCUMENT_ROOT'].'/images/aula/'.$dados_alt[0]['aula_Foto']);
            endif;
            $model_update = $model->aulaAlteracao($dados);
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
            $model = new App_Model_aulaModel();
            $dados_del = $model->aulaSelecionar($ident['id']);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/aula/'.$dados_del[0]['aula_Foto']);
            $model_delete = $model->aulaDeletar($ident['id']);
            echo $model_delete;
        }
        /***************     PAGINA     *********************/
        public function listar(){
            $aula_cad = new App_Model_aulaModel();
            global $start;
            $parm = $start->_params;
            
            $model = new App_Model_cursoModel();
            $sel_disciplina = $model->cursoChave($parm['ref']);

            $curso = new App_Model_cursocatModel();
            $sel_curso = $curso->cursoSelecionar($sel_disciplina[0]['D016_Categoria']);
      
            $aula_lista_cad = $aula_cad->listaCat($sel_disciplina[0]['D016_Uid']);
        
            $dados["referencia"] = $parm['ref'];
            $dados["curso"] = $sel_curso[0]['D016_Nome'];
            $dados["curso_chave"] = $sel_curso[0]['D016_Chave'];
            $dados["disciplina"] = $sel_disciplina[0]['D016_Titulo'];
            $dados["disciplina_chave"] = $sel_disciplina[0]['D016_Chave'];


            if(!empty($aula_lista_cad)):
                $dados['aula_lista'] = $aula_lista_cad;

                /*DADOS Aula*/
               
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("cadastradoAula",$dados);
            else:
                //funcao que chama a view
                $this->View("cadastradoAula");
            endif;
        }
      
    }