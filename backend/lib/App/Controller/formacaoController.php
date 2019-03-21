<?php
class formacao extends App_Controller{
    public function index_action(){
        return $this->View("indexformacao");
    }
    /****************** CRUD ******************************/
    public function registar(){
        
        global $start;
        $parm = $start->_params;
        
        //dependências necessárias
        $formadores_obj = new App_Model_usuarioModel();
        $formacao_obj = new App_Model_formacaoModel();
        $produtos_obj = new App_Model_produtoModel();
        $cliente_obj = new App_Model_clienteModel();
        $model = new App_Model_moduloModel();    
        
        //dados p/ view
        $dados['modulo'] = $model->listaModulo();;
        $dados["formadores"] = $formadores = $formadores_obj = $formadores_obj->listaUsuarioNivel(4);
        $dados["produtos"] = $produtos = $produtos_obj = $produtos_obj->listaProduto();
        $dados["tipos_formacao"] = $tipos_formacao = $formacao_obj->get_tipo_formacao();
        $dados["cliente"] = $cliente_obj->clienteSelecionarRef($parm["ref"]);
        $dados["cliente"] = $dados["cliente"][0];
        $dados["ref"] = $parm["ref"];
        return $this->View("cadastrarFormacao", $dados); 
    }
    
    public function inserir(){
        
        $id = new App_System();
        $id->_urlAjax = $_POST['url'];
        $id->setExplodeAjax();
        $id->setControllerAjax();
        $id->setActionAjax();
        $id->setParamsAjax();
        $dados = $id->getParamsAjax();
        
        //usable array
        parse_str($_POST["form_data"], $form_data);
        
        //trim & parse
        $date_object = DateTime::createFromFormat('d/m/Y', $form_data["form_data"]);
        $form_data["form_data"] = $date_object->format("Y/m/d h:i");
        $form_data["form_produtos"] = !empty($form_data["form_produtos"]) ? implode(",", $form_data["form_produtos"]) : "";
        
        $model = new App_Model_formacaoModel();
        $model_formando = new App_Model_formandoModel();
        $languageLIST = unserialize(LANGUAGE_LIST);
        
        //registar formandos
        $formandos = array();
        if(!empty($form_data["formando"])){
            foreach($form_data["formando"] as $formando){
                $formando_inserir = $model_formando->cadastrarFormando($formando);
                array_push($formandos, $formando_inserir);
            }
        }
        
        
        $form_data["form_formandos"] = implode("," ,$formandos);
        $ref = rand(100, 999999);
                
        $arr = array(
            "form_ref" => $ref,
            "form_client_id" => $form_data["form_cliente_ref"],
            "form_formador_id" => $form_data["form_formador"],
            "form_data_formacao" => $form_data["form_data"],
            "form_tipo" => $form_data["form_tipo_formacao"],
            "form_observacao" => $form_data["form_observacao"],
            "form_produtos" => $form_data["form_produtos"]
        );
        
        $model_inserir = $model->formacaoCadastrar($arr);
        
        //após inserção do registo é preciso registar formandos
        
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
        $formacao_alt = new App_Model_formacaoModel();
        $formacao_lista_alt = $formacao_alt->formacaoSelecionar($ident);
        $dados['formacao_lista_alt'] = $formacao_lista_alt;
        $nivel = new App_Model_nivelModel();
        $nivel_lista = $nivel->nivelListar2();
        $dados['formacao_nivel'] = $nivel_lista;
        if($view != NULL):
            return $dados;
        else:
            $this->View("alterarFormacao", $dados);
        endif;
    }
    public function alterar(){
        global $start;
        $parm = $start->_params;
        //dependencias
        $formacao_obj = new App_Model_formacaoModel();
        $model = new App_Model_moduloModel();
        $cliente_obj = new App_Model_clienteModel();
        $formadores_obj = new App_Model_formadorModel();
        $formandos_obj = new App_Model_formandoModel();
        $produtos_obj = new App_Model_produtoModel();
                
        //dados
        $dados['modulo'] = $model->listaModulo();
        $dados['formacao'] = $formacao_obj->formacaoSelecionar($parm['ref']);
        $dados['formacao'] = $dados['formacao'][0];
        $dados['cliente'] = $cliente_obj->clienteSelecionarRef($dados["formacao"]["form_client_id"]);
        $dados["cliente"] = $dados["cliente"][0];
        $dados["formadores"] = $formadores = $formadores_obj = $formadores_obj->listaFormador();
        $dados["produtos"] = $produtos = $produtos_obj = $produtos_obj->listaProduto();
        $dados["tipos_formacao"] = $tipos_formacao = $formacao_obj->get_tipo_formacao();
        $dados["formandos"] = $formandos_obj->listaFormando();
        $this->View("alterarFormacao", $dados);
    }
    //ALTERAÇÃO
    public function alteracao(){
        //usable array
        parse_str($_POST["form_data"], $form_data);
        $formacao_obj = new App_Model_formacaoModel();
        $cliente_obj = new App_Model_clienteModel();
        $model = new App_Model_formacaoModel();
        $model_formando = new App_Model_formandoModel();
        $formacao = $formacao_obj->formacaoSelecionarRef($form_data["form_cliente_ref"]);
        $cliente = $cliente_obj->clienteSelecionar($formacao[0]["form_client_id"]);
        
        //trim & parse
        $date_object = DateTime::createFromFormat('d/m/Y', $form_data["form_data"]);
        $form_data["form_data"] = $date_object->format("Y/m/d h:i");
        $form_data["form_produtos"] = !empty($form_data["form_produtos"]) ? implode(",", $form_data["form_produtos"]) : "";
        $languageLIST = unserialize(LANGUAGE_LIST);
        //registar novos formandos - manter os antigos
        $formandos = array();
        if(!empty($form_data["formando"])){
            foreach($form_data["formando"] as $formando_id => $formando){
                if(empty($formando["nome"])) continue;
    
                //verifica se existe o ID do formando
                $check_formando = $model_formando->formandoSelecionar($formando_id);
                if(!empty($check_formando)){
                    //formando existe, fazer update à info
    
                    $formando["id"] = $formando_id;
                    $id_insert_or_update = $model_formando->formandoAlteracao($formando);
    
                }
    
                else{
                    $id_insert_or_update = $model_formando->cadastrarFormando($formando);
                }
    
                //add to collection
                if(!empty($id_insert_or_update))
                    array_push($formandos, $id_insert_or_update);
            }
        }
        
        $form_data["form_formandos"] = implode("," ,$formandos);
       
        $arr = array(
            "form_id" => $form_data["form_id"],
            "form_ref" => $form_data["form_cliente_ref"],
            "form_client_id" => $cliente[0]["cli_id"],
            "form_formador_id" => $form_data["form_formador"],
            "form_data_formacao" => $form_data["form_data"],
            "form_tipo" => $form_data["form_tipo_formacao"],
            "form_produtos" => $form_data["form_produtos"],
            "form_formandos" => $form_data["form_formandos"],
            "form_observacao" => $_POST["obs"]
        );             
        $model_inserir = $model->formacaoAlteracao($arr);
        
        //após inserção do registo é preciso registar formandos
        print_r($model_inserir);
        
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
        $model = new App_Model_formacaoModel();
        $dados_formacao = $model->formacaoSelecionar($ident['id']);
        unlink($_SERVER['DOCUMENT_ROOT'].'/images/formacao/'.$dados_formacao[0]['usu_foto']);
        $model_delete = $model->formacaoDeletarIMG($ident['id']);
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
        $model = new App_Model_formacaoModel();
        $dados_formacao = $model->formacaoSelecionar($ident['id']);
        $model_delete = $model->formacaoDeletar($ident['id']);
        echo $model_delete;
    }
    
    /***************     formacao     *********************/
    public function listar(){
        
        $languageDEFAULT = unserialize (LANGUAGE_DEFAULT);
        
        $formacao = new App_Model_formacaoModel();
        $sel_formacao = $formacao->listaformacao();
        
        $formacao_niveis_obj = new App_Model_nivelModel();
        $formacao_niveis = $formacao_niveis_obj->nivelListar2();
        
        //mapear níveis
        foreach($formacao_niveis as $nivel){
            $niveis[$nivel["niv_id"]] = $nivel["niv_nome"];
        }
        
        //readable nivel
        foreach($sel_formacao as $utilizador){
            
            $output[$utilizador["usu_id"]] = $utilizador;
            $output[$utilizador["usu_id"]]["niv_id"] = !empty($niveis[$utilizador["niv_id"]]) ? $niveis[$utilizador["niv_id"]] : "";
        }
        
        
        /*DADOS formacao*/       
        if(!empty($output)):
            $dados['formacao_lista'] = $output;
            //funcao que chama a view
            $this->View("cadastradoformacao",$dados);
        else:
            //funcao que chama a view
            $this->View("cadastradoformacao");
        endif;
        
    }
    
    /* PDF */
    public function pdf(){
       
        $model_formacao = new App_Model_formacaoModel();
        $obj_formacao = $model_formacao->prepararPdf($_POST['ref']);

        if(!empty( $obj_formacao )):
 /*            foreach ($obj_formacao as $rs):
                $sel_contato = $contato->contatoSelecionarCliente($rs["cli_id"]);
                $sel_distrito = $distrito->distritoSelecionar($rs["cli_distrito"]);
                $sel_area = $area->areaSelecionarIn($rs["cli_area_negocio"]);
                $sel_produto = $produto->produtoSelecionarIn($rs["cli_produto"]);
                $arr = array(
                        "cli_nome"=>$rs["cli_nome"],
                        "cli_rua"=>$rs["cli_rua"],
                        "cli_cp"=>$rs["cli_cp"],
                        "cli_localidade"=>$rs["cli_localidade"],
                        "cli_distrito"=>$sel_distrito[0]["sigla_distrito"],
                        "cli_email"=>$rs["cli_email"],
                        "cli_telefone"=>$rs["cli_telefone"],
                        "cli_area_negocio"=>$sel_area,
                        "cli_produto"=>$sel_produto,
                        "cli_contato"=>$sel_contato
                );
                $info[] = $arr;
            endforeach; */
            /* if(isset($info)):
                array_push($info, $arr);
                array_pop($info);
            endif; */
            $dados['formacao'] = $obj_formacao;
        else:
            $dados['formacao'] = array();
        endif;
        
        $config = new App_Model_configModel();
        $sel_config = $config->configBuscar();
        $dados['config'] = $sel_config;
        
        $this->View("viewFormacaoPDF",$dados);
    }
}