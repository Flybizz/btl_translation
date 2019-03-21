<?php
class formando extends App_Controller{
    public function index_action(){
        return $this->View("indexformando");
    }
    /****************** CRUD ******************************/
    public function registar(){
        
        global $start;
        $parm = $start->_params;
        
        //dependências necessárias
        $formando_obj = new App_Model_formandoModel();
        $formacao_obj = new App_Model_formacaoModel();        
        $cliente_obj = new App_Model_clienteModel();
        $model = new App_Model_moduloModel();    
                
        //dados p/ view
        $dados['modulo'] = $model->listaModulo();;
        $dados["formandos"] = $formandos = $formando_obj = $formando_obj->listaFormador();        
        $dados["tipos_formacao"] = $tipos_formacao = $formacao_obj->get_tipo_formacao();        
        $dados["cliente"] = $cliente_obj->clienteSelecionarRef($parm["ref"]);
        $dados["cliente"] = $dados["cliente"][0];
        $dados["ref"] = $parm["ref"];
        
        return $this->View("registarFormando", $dados); 
    }
    
    public function inserir(){
              
        //usable array
        parse_str($_POST["form_data"], $form_data);
                              
        $model = new App_Model_formacaoModel();
        $model_formando = new App_Model_formandoModel();

        $ver_formando = $model_formando->formandoSelecionarNIF($form_data["form_nif"]);

        $list = $model->get_list_from_formacao_by_ref($form_data["form_formacao"]);

        if(!empty($ver_formando)):
            $formando_alterar = $model_formando->formandoAlteracao($form_data);
        else:            
            
            if(!empty($list[0]["form_formandos"])):
                $list[0]["form_formandos"] =  $list[0]["form_formandos"].",";
            endif;

            $list[0]["form_formandos"] = explode(",",$list[0]["form_formandos"]);
            array_pop($list[0]["form_formandos"]);
            
            $formando_inserir = $model_formando->cadastrarFormando($form_data);
            array_push($list[0]["form_formandos"], $formando_inserir);

            $form_data["form_formandos"] = implode("," ,$list[0]["form_formandos"]);        

            $model_alterar = $model->formacaoAlteracaoFormando($form_data["form_formandos"],$form_data["form_formacao"]);

        endif;

        $list_all = $model->get_list_from_formacao_by_ref($form_data["form_formacao"]);

        if(!empty($list_all)):
            $sel_formando = $model_formando->formandoSelecionarIn($list_all[0]['form_formandos']);
        else:
            $sel_formando = array();
        endif;        

        $html = '';

        if (!empty( $sel_formando )):
            foreach ($sel_formando as $formando):

        $html .= '<li id="'.$formando["form_id"].'" name="item_formando" class="p-4 col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color: rgba(255,255,255,1);" >
                <span class="pull-right mt-0 pt-0" style="position: absolute; right: 15px; top: 5px;"> 
                    <a class="formUpdate" href="javascript:void(0);" data-id="'.$formando["form_id"].'" data-name="'.$formando["form_nome"].'"  data-email="'.$formando["form_email"].'"  data-nif="'.$formando["form_nif"].'"><i class="fa fa-pencil fa-lg mr-1"></i></a>
                    <a class="formDel" href="#modalAnim" data-id="'.$formando["form_id"].'" data-name="'.$formando["form_nome"].'" data-formacao="'.$form_data["form_formacao"].'" ><i class="fa fa-remove fa-lg"></i></a>
                </span>
                <div class="">                    
                    <h5 class="title fs-18"><strong>'.$formando["form_nome"].'</strong></h5>                                     
                    <span class="message truncate">
                        <i class="fa fa-envelope-square fa-lg"></i> <a href="mailto:'.$formando["form_email"].'" target="_blank">'.$formando["form_email"].'</a> | <i class="fa fa-address-card fa-lg"></i> '.$formando["form_nif"].'</span>
                </div>										
            </li>';	
                                    
            endforeach;
        endif;

        echo $html;

                
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
            $this->View("alterarformacao", $dados);
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
        $dados['formacao'] = $formacao_obj->get_list_from_formacao_by_ref($parm['form']);
        $dados['formacao'] = $dados['formacao'][0];
        $dados['cliente'] = $cliente_obj->clienteSelecionarRef($dados["formacao"]["form_client_id"]);
        $dados["cliente"] = $dados["cliente"][0];
        $dados["formadores"] = $formadores = $formadores_obj = $formadores_obj->listaFormador();
        $dados["produtos"] = $produtos = $produtos_obj = $produtos_obj->listaProduto();
        $dados["tipos_formacao"] = $tipos_formacao = $formacao_obj->get_tipo_formacao();
        $dados["formando"] = $formandos_obj->formandoSelecionar($parm['ref']);
        $dados["formando"] = $dados["formando"][0];
                
        $this->View("alterarFormando", $dados);
    }
    //ALTERAÇÃO
    public function alteracao(){
             
        //usable array
        parse_str($_POST["form_data"], $form_data);        
        $model_formando = new App_Model_formandoModel();                        
        $sel_formando = $model_formando->formandoAlteracao($form_data);
        
        print_r($sel_formando);
        
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
    
    /* DELETAR */
    public function deletar(){ 

        $form_data = $_POST["form_data"];
        
        $model_formando = new App_Model_formandoModel();
        $model = new App_Model_formacaoModel();

        $model_delete = $model_formando->formandoDeletar($form_data['id']);

        $list_all = $model->get_list_from_formacao_by_ref($form_data["formacao"]);

        if(!empty($list_all)):
            $sel_formando = $model_formando->formandoSelecionarIn($list_all[0]['form_formandos']);
        else:
            $sel_formando = array();
        endif;        

        $html = '';

        if (!empty( $sel_formando )):
            foreach ($sel_formando as $formando):

        $html .= '<li id="'.$formando["form_id"].'" name="item_formando" class="p-4 col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color: rgba(255,255,255,1);" >
                <span class="pull-right mt-0 pt-0" style="position: absolute; right: 15px; top: 5px;"> 
                    <a class="formUpdate" href="javascript:void(0);" data-id="'.$formando["form_id"].'" data-name="'.$formando["form_nome"].'"  data-email="'.$formando["form_email"].'"  data-nif="'.$formando["form_nif"].'"><i class="fa fa-pencil fa-lg mr-1"></i></a>
                    <a class="formDel" href="#modalAnim" data-id="'.$formando["form_id"].'" data-name="'.$formando["form_nome"].' data-formacao="'.$form_data["formacao"].'"><i class="fa fa-remove fa-lg"></i></a>
                </span>
                <div class="">                    
                    <h5 class="title fs-18"><strong>'.$formando["form_nome"].'</strong></h5>                                   
                    <span class="message truncate">
                        <i class="fa fa-envelope-square fa-lg"></i> <a href="mailto:'.$formando["form_email"].'" target="_blank">'.$formando["form_email"].'</a> | <i class="fa fa-address-card fa-lg"></i> '.$formando["form_nif"].'</span>
                </div>										
            </li>';	
                                    
            endforeach;
        endif;

        echo $html;
    }
    
    /***************     formacao     *********************/
    public function listar(){

        global $start;
        $parm = $start->_params;
                
        $languageDEFAULT = unserialize (LANGUAGE_DEFAULT);
                
        $formacao = new App_Model_formacaoModel();
        $sel_formacao = $formacao->formacaoSelecionar($parm['ref']);

        $sel_tipo = $formacao->get_tipo_formacao_by_id($sel_formacao[0]['form_tipo']);

        $cliente = new App_Model_clienteModel();
        $sel_cliente = $cliente->clienteSelecionarRef($sel_formacao[0]['form_client_id']);

        $formando = new App_Model_formandoModel();        

        if(!empty($sel_formacao)):
            $sel_formando = $formando->formandoSelecionarIn($sel_formacao[0]['form_formandos']);
        else:
            $sel_formando = array();
        endif;

        /* $formacao_niveis_obj = new App_Model_nivelModel();
        $formacao_niveis = $formacao_niveis_obj->nivelListar2();
       
     
        foreach($formacao_niveis as $nivel){
            $niveis[$nivel["niv_id"]] = $nivel["niv_nome"];
        }
        
       
        foreach($sel_formacao as $utilizador){
            
            $output[$utilizador["usu_id"]] = $utilizador;
            $output[$utilizador["usu_id"]]["niv_id"] = !empty($niveis[$utilizador["niv_id"]]) ? $niveis[$utilizador["niv_id"]] : "";
        } */

        $dados['tipo'] = $sel_tipo[0]; 
        $dados['cliente'] = $sel_cliente[0];    
        $dados['formando'] = $sel_formando;               
        $dados['formacao'] = $sel_formacao[0];            
        $this->View("inscritoFormando",$dados);
      
        
    }
}