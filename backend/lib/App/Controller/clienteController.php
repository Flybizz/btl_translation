<?php
    class cliente extends App_Controller{
        public function index_action(){
            return $this->View("indexCliente");
        }
        public function gerar_referencia(){
          $cliente_cad = new App_Model_clienteModel();
          $cliente_lista_cad = $cliente_cad->gerar_referencia();
        }
        public function pesquisar_ajax(){
            if(empty($_GET))
                return false;
            $search_term = @$_GET["search"];
            $cliente_obj = new App_Model_clienteModel();
            $results = $cliente_obj->pesquisar_ajax($search_term);
            if(!empty($results)){
                foreach($results as $result){
                    $output["id"] = $result["cli_id"];
                    $output["text"] = $result["cli_nome"];
                }
    
                $return["results"][] = $output;
                $return["pagination"] = array("more" => true);
    
            }
            
            echo json_encode($return);
            
        }
        public function listar(){
            $cliente_cad = new App_Model_clienteModel();
            $cliente_lista_cad = $cliente_cad->listaCliente();
            if(!empty($cliente_lista_cad)):
                $contato = new App_Model_contatoModel();
	            $distrito = new App_Model_distritoModel();
                $tipo = new App_Model_tipoModel();
                $model_cargo = new App_Model_cargoModel();
                $lista_cargo = $model_cargo->listaCargoStatus();
                //change 1.0
                $distritos_lista = $distrito->get_all_into_array();
                $contatos_lista = $contato->get_all_into_array();
                $tipo_lista = $tipo->get_all_into_array();
                foreach ($cliente_lista_cad as $rs):
                    $sel_contato = (!empty($contatos_lista[$rs["cli_id"]]))? $contatos_lista[$rs["cli_id"]] : "";
                    $distrito2 = (!empty($distritos_lista[$rs["cli_distrito"]]))? $distritos_lista[$rs["cli_distrito"]] : "";
                    $sel_tipo = (!empty($tipo_lista[$rs["cli_tipo"]]))? $tipo_lista[$rs["cli_tipo"]] : "";
	                //$sel_distrito = $distrito->distritoSelecionar($rs["cli_distrito"]);
	                //$sel_tipo = $tipo->tipoSelecionar($rs["cli_tipo"]);
                    //$sel_contato = $contato->contatoSelecionarCliente($rs["cli_id"]);
                    $distrito2 = (!empty($sel_distrito))? $sel_distrito[0]["sigla_distrito"] : "";
                    $tipo2 = (!empty($sel_tipo))? $sel_tipo["tipo_nome"] : "";
   	                $arr = array(
                        "cli_id"=>$rs["cli_id"],
                        "cli_referencia"=>$rs["cli_referencia"],
                        "cli_nome"=>$rs["cli_nome"],
                        "cli_rua"=>$rs["cli_rua"],
                        "cli_cp"=>$rs["cli_cp"],
                        "cli_localidade"=>$rs["cli_localidade"],
                        "cli_distrito"=> utf8_encode($distrito2),
                        "cli_email"=>$rs["cli_email"],
                        "cli_telefone"=>$rs["cli_telefone"],
                        "cli_tipo"=>$tipo2,
                        "cli_contato"=>$sel_contato
	                );
	                $info[] = $arr;
                endforeach;
	            if(isset($info)):
	                array_push($info, $arr);
	                array_pop($info);
	            endif;
	            $dados['dados'] = json_encode($info);
	            $dados['cliente_lista'] = $cliente_lista_cad;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("inscritoCliente",$dados);
            else:
                //funcao que chama a view
                $this->View("inscritoCliente");
            endif;
        }
        /****************** CRUD ******************************/
        public function registar(){
            global $start;
            $parm = $start->_params;
            $dados['lang'] = $parm['lang'];
            $model = new App_Model_areaModel();
            $lista = $model->listaArea();
            $dados["area"] = $lista;
            $model_produto = new App_Model_produtoModel();
            $lista_produto = $model_produto->listaProdutoDefault($parm['lang']);
            $dados["produto"] = $lista_produto;
            $model_distrito = new App_Model_distritoModel();
            $lista_distrito = $model_distrito->listaDistritoStatus();
            $dados["distrito"] = $lista_distrito;
            $model_tipo = new App_Model_tipoModel();
            $lista_tipo = $model_tipo->listaTipoStatus();
            $dados["tipo"] = $lista_tipo;
            /* $model = new App_Model_vendedorModel();
            $lista_vendedor = $model->listaVendedor(); */
            $model_utilizador = new App_Model_usuarioModel();
            $lista_usuario = $model_utilizador->listaUsuarioNivel(3);          
            $dados["vendedor"] = $lista_usuario;
            //funcao que chama a view
            return $this->View("registarCliente", $dados);
        }
        public function add(){
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $model_cargo = new App_Model_cargoModel();
            $lista_cargo = $model_cargo->listaCargoStatus();
            $model_especialidade = new App_Model_especialidadeModel();
            $lista_especialidade = $model_especialidade->listaEspecialidadeStatus();
            $model_lead = new App_Model_leadModel();
            $lista_lead = $model_lead->listaLead();
            if ($dados["row"] == 1):
                $col = "4";
            elseif ($dados["row"] == 2):
                $col = "4";
            elseif ($dados["row"] == 3):
                $col = "4";
            endif;
            $info = '
            <div id="row'.$dados["row"].'" class="form-group row">
                <label class="col-lg-12 control-label text-lg-right pt-2" for="inputDefault">
                    <h4 class="pull-left"><strong>Contacto '.$dados["row"].'</strong></h4>
                    <button data-remove="remove'.$dados["row"].'" data-id="row'.$dados["row"].'" class="btn btn-primary" style="position: absolute; top:10px; right:15px;"> <i class="fa fa-trash-o fa-lg"></i> </button>
                </label>
                <div class="col-lg-12" data-tipo="contato">
                    <div class="form-group row" style="position:relative;">
                        <label class="col-lg-3 control-label text-lg-right pt-2">Nome</label>
                        <div class="col-lg-6">
                        <input type="text" id="cont_nome_'.$dados["row"].'" data-tipo="nome" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group row" style="position:relative;">
                        <label class="col-lg-3 control-label text-lg-right pt-2">Cargo</label>
                        <div class="col-lg-6">
                        <select id="cont_cargo_'.$dados["row"].'" data-tipo="cargo" class="form-control">
                          <option value=""></option> ';
                          if(!empty($lista_cargo)):
                            foreach ($lista_cargo as $cargo):
                            $info .= "<option value='".$cargo['cargo_id']."'>".$cargo['cargo_nome']."</option>";
                            endforeach;
                          endif;
                    $info .= '
                        </select>
                        </div>
                    </div>
                    <div class="form-group row" style="position:relative;">
                        <label class="col-lg-3 control-label text-lg-right pt-2">Especialidade</label>
                        <div class="col-lg-6">
                        <select id="cont_especialidade_'.$dados["row"].'" data-tipo="especialidade" class="form-control">
                          <option value=""></option> ';
                          if(!empty($lista_especialidade)):
                            foreach ($lista_especialidade as $especialidade):
                            $info .= "<option value='".$especialidade['esp_id']."'>".$especialidade['esp_nome']."</option>";
                            endforeach;
                          endif;
                    $info .= '
                        </select>
                        </div>
                    </div>
                    <div class="form-group row" style="position:relative;">
                        <label class="col-lg-3 control-label text-lg-right pt-2">Email</label>
                        <div class="col-lg-6">
                        <input type="text" id="cont_email_'.$dados["row"].'" data-tipo="email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row" style="position:relative;">
                        <label class="col-lg-3 control-label text-lg-right pt-2">Telemóvel</label>
                        <div class="col-lg-6">
                        <input type="text" id="cont_telemovel_'.$dados["row"].'" data-tipo="telemovel" class="form-control">
                        </div>
                    </div>';
                     //tipo de lead
                     $info .= '<div class="form-group row" style="position:relative;">
                     <label class="col-lg-3 control-label text-lg-right pt-2">Tipo de lead</label>
                     <div class="col-lg-6">
                     <select id="cont_lead_status_'.$dados["row"].'" data-tipo="lead" class="form-control">
                       <option value=""></option>';
 
                     if(!empty($lista_lead)){
                         foreach($lista_lead as $lead){
                             $info .= "<option value='".$lead['lead_id']."'>".utf8_encode($lead['lead_nome'])."</option>";
                         }
                     }
 
                     $info .= '
                         </select>
                         </div>
                     </div>';
                     //end tipo lead                     
                     
                    $info .= '<input type="hidden" id="cont_id_'.$dados["row"].'" data-tipo="id" class="form-control" value="0">
                </div>
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
            $cliente = new App_Model_clienteModel();
            $rg_cliente = $cliente->clienteRegistar($dados);
            $sl_cliente = $cliente->clienteUltimoRg();
            if(!empty($sl_cliente) && !empty($dados["contato"])):
                $arr_contato = explode("*", $dados["contato"]);
                $row = 0;
                foreach($arr_contato as $value):
                    $row++;
                    $arr = explode(",", $value);
                    $arr_item = array(
                        "nome" => $arr[1],
                        "cargo" => $arr[2],
                        "especialidade" => $arr[3],
                        "email" => $arr[4],
                        "telemovel" => $arr[5],
                        "cliente" => $sl_cliente[0]["cli_id"],
                        "id" => $arr[0],
                        "lead_status" => $arr[6]                        
                    );
                    $arr_full[$row] = $arr_item;
                endforeach;
                
                array_push($arr_full, $arr_item);
                array_pop($arr_full);
                $contato = new App_Model_contatoModel();
                foreach ($arr_full as $cont):
                    $send_contato = array(
                        "nome" => $cont["nome"],
                        "cargo" => $cont["cargo"],
                        "especialidade" => $cont["especialidade"],
                        "email" => $cont["email"],
                        "telemovel" => $cont["telemovel"],
                        "cliente" =>$cont["cliente"],
                        "contato" => $cont["id"],
                        "lead_status" => $cont["lead_status"]
                    );
                    $rg_contato = $contato->contatoRegistar($send_contato);
                endforeach;
            endif;
            print_r($rg_cliente);
        }
        /* ALTERAR */
        public function selecionar($view = NULL){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");
            $cliente_alt = new App_Model_clienteModel();
            $cliente_lista_alt = $cliente_alt->clienteSelecionar($ident);
            $dados['cliente_lista_alt'] = $cliente_lista_alt;
            $model_distrito = new App_Model_distritoModel();
            $lista_distrito = $model_distrito->listaDistritoStatus();
            $dados["distrito"] = $lista_distrito;
            $model_contato = new App_Model_contatoModel();
            $lista_contato = $model_contato->contatoSelecionarCliente($cliente_lista_alt[0]["cli_id"]);
            $dados["contato"] = $lista_contato;
            $model_cargo = new App_Model_cargoModel();
            $lista_cargo = $model_cargo->listaCargoStatus();
            $dados["cargo"] = $lista_cargo;
            $model_especialidade = new App_Model_especialidadeModel();
            $lista_especialidade = $model_especialidade->listaEspecialidadeStatus();
            $dados["especialidade"] = $lista_especialidade;
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarCliente", $dados);
            endif;
        }
        /* PERFIL */
        public function perfil(){
            global $start;
            $parm = $start->_params;
            $cliente_alt = new App_Model_clienteModel();
            $cliente_lista = $cliente_alt->clienteSelecionarRef($parm['ref']);
            $contato = new App_Model_contatoModel();
            $cargo = new App_Model_cargoModel();
            $especialidade = new App_Model_especialidadeModel();
            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            $produto = new App_Model_produtoModel();
            $usuario = new App_Model_usuarioModel();
            $tipo = new App_Model_tipoModel();
            
            if(isset($parm['tab'])):
                $dados['tab'] = $parm['tab'];
            endif;
            if(!empty( $cliente_lista )):                
	            foreach ($cliente_lista as $rs):
	                $sel_contato = $contato->contatoSelecionarCliente($rs["cli_id"]);
	                $sel_distrito = $distrito->distritoSelecionar($rs["cli_distrito"]);
                    $sel_tipo = $tipo->tipoSelecionar($rs["cli_tipo"]);
	                $sel_area = $area->areaSelecionarIn($rs["cli_area_negocio"]);
                    $sel_produto = $produto->produtoSelecionarIn($rs["cli_produto"]);
	                $sel_vendedor = $usuario->usuarioSelecionar($rs["usu_id"]);
                    $sel_associado = $usuario->usuarioSelecionarIn($rs["cli_associado"]);
                    
                    $vl_area = "";
                    if(!empty($sel_area)):
                      foreach ($sel_area as $area2):
                        $vl_area .= $area2["area_nome"].", ";
                      endforeach;
                    else:
                        $rs_area = "";
                    endif;
                    $rs_area = substr($vl_area,0,-2);
                    $vl_produto = "";
                    if(!empty($sel_produto)):
                      foreach ($sel_produto as $produto2):
                        $vl_produto .= $produto2["prod_nome"].", ";
                      endforeach;
                    else:
                        $rs_produto = "";
                    endif;
                    $rs_produto = substr($vl_produto,0,-2);
                    /* VENDEDORES ASSOCIADOS */
                    $vl_associado= "";
                    if(!empty($sel_associado)):
                      foreach ($sel_associado as $associado2):
                        $vl_associado .= $associado2["usu_nome"].", ";
                      endforeach;
                    else:
                        $rs_associado = "";
                    endif;
                    $rs_associado = substr($vl_associado,0,-2);
                    $vl_tipo = "";
                    if(!empty($sel_tipo)):
                      foreach ($sel_tipo as $tipo2):
                        $vl_tipo .= $tipo2["tipo_nome"].", ";
                      endforeach;
                    else:
                        $rs_tipo = "";
                    endif;
                    $rs_tipo = substr($vl_tipo,0,-2);
                    $vl_distrito = "";
                    if(!empty($sel_distrito)):
                      foreach ($sel_distrito as $distrito2):
                        $vl_distrito .= $distrito2["sigla_distrito"].", ";
                      endforeach;
                    else:
                        $rs_distrito = "";
                    endif;
                    $rs_distrito = substr($vl_distrito,0,-2);
                    if(!empty($sel_vendedor)):
                        $sl_vendedor = $sel_vendedor[0]["usu_nome"];
                    else:
                        $sl_vendedor = "";
                    endif;
                    if(!empty($sel_contato)):
                      foreach ($sel_contato as $item_contato):
                        $sel_cargo = $cargo->cargoSelecionar($item_contato["cont_cargo"]);
                        $sel_especialidade = $especialidade->especialidadeSelecionar($item_contato["cont_especialidade"]);
                        if(!empty($sel_cargo)):
                            $sl_cargo = $sel_cargo[0]["cargo_nome"];
                        else:
                            $sl_cargo = "";
                        endif;
                        if(!empty($sel_especialidade)):
                            $sl_especialidade = $sel_especialidade[0]["esp_nome"];
                        else:
                            $sl_especialidade = "";
                        endif;
                        $cont = array(
                                "cont_id"=>$item_contato["cont_id"],
                                "cont_nome"=>$item_contato["cont_nome"],
                                "cont_chave"=>$item_contato["cont_id"],
                                "cont_cargo"=>$sl_cargo,
                                "cont_especialidade"=>$sl_especialidade,
                                "cont_email"=>$item_contato["cont_email"],
                                "cont_telemovel"=>$item_contato["cont_telemovel"],
                                "cont_status"=>$item_contato["cont_status"],
                                "cli_id"=>$item_contato["cli_id"],
                                "cont_contato"=>$item_contato["cont_contato"],
                                "cont_data"=>$item_contato["cont_data"]
                        );
                        $dados_contato[] = $cont;
                      endforeach;
                    endif;
                    if(isset($dados_contato)):
                        array_push($dados_contato, $cont);
                        array_pop($dados_contato);
                    else:
                        $dados_contato = array();
                    endif;
                    if($rs["cli_saudacao"] == 0):
                        $rf = "";
                    elseif($rs["cli_saudacao"] == 1):
                        $rf = "Sr.";
                    elseif($rs["cli_saudacao"] == 2):
                        $rf = "Sra.";
                    elseif($rs["cli_saudacao"] == 3):
                        $rf = "Srta.";
                    elseif($rs["cli_saudacao"] == 4):
                        $rf = "Dr.";
                    elseif($rs["cli_saudacao"] == 5):
                        $rf = "Dra.";
                    endif;
	                $arr = array(
                        "cli_id"=>$rs["cli_id"],
                        "cli_saudacao"=>$rf,
                        "cli_referencia"=>$rs["cli_referencia"],
	                    "cli_nome"=>$rs["cli_nome"],
                        "cli_rua"=>$rs["cli_rua"],
                        "cli_numero"=>$rs["cli_numero"],
                        "cli_andar"=>$rs["cli_andar"],
                        "cli_cp"=>$rs["cli_cp"],
                        "cli_tipo"=>$rs_tipo,
	                    "cli_localidade"=>$rs["cli_localidade"],
	                    "cli_distrito"=>$rs_distrito,
	                    "cli_email"=>$rs["cli_email"],
	                    "cli_telefone"=>$rs["cli_telefone"],
                        "cli_url"=>$rs["cli_url"],
                        "cli_descricao"=> str_replace("|", "/", $rs["cli_descricao"]),
                        "cli_status"=>$rs["cli_status"],
	                    "cli_area_negocio"=>$rs_area,
                        "cli_produto"=>$rs_produto,
	                    "cli_contato"=>$dados_contato,
                        "cli_rgpd"=>$rs["cli_rgpd"],
                        "cli_nif"=>$rs["cli_nif"],
	                    "vendedor"=>$sl_vendedor,
                        "associado"=>$rs_associado,
                        "cli_imagem"=>$rs["cli_imagem"],
                        "cli_google_map" => $rs["cli_google_map"]
	                );
	                $info[] = $arr;
	            endforeach;
	            if(isset($info)):
	                array_push($info, $arr);
	                array_pop($info);
	            endif;
	            $dados['cliente'] = $info;
           	else:
                $dados['cliente'] = array();
            endif;
            $dados['perfil_complete'] = self::perfilComplete($parm['ref'], $cliente_lista);
            $followup = new App_Model_followupModel();
            $sel_followup = $followup->followupSelecionarCliente($cliente_lista[0]["cli_id"]);
            $bd_timeline = $cliente_alt->clienteTimeline($cliente_lista[0]["cli_id"]);
            $data = [];
            foreach($bd_timeline as $timeline):
                setlocale(LC_TIME, 'portuguese');
                //date_default_timezone_set('Europa/Lisbon');
                $dt = $timeline["data"];
                $date = strftime("%d %B %Y", strtotime($dt));
                $mes = strftime("%B %Y", strtotime($dt));
                switch ($timeline["tipo"]):
                    case 1 : $tipo = "Email"; break;
                    case 2 : $tipo = "Reunião"; break;
                    case 3 : $tipo = "Reunião de pré-agariação"; break;
                    case 4 : $tipo = "Telefonema"; break;
                    case 5 : $tipo = "Tarefa"; break;
                    case 6 : $tipo = "Qualificação Financeira"; break;
                    case 7 : $tipo = "Outro"; break;
                endswitch;
                switch ($timeline["prioridade"]):
                    case 1 : $prioridade = "Baixa"; $prioridade_color = "#0088cc"; break;
                    case 2 : $prioridade = "Média"; $prioridade_color = "#47a447"; break;
                    case 3 : $prioridade = "Alta"; $prioridade_color = "#fd7e14"; break;
                    case 4 : $prioridade = "Urgente"; $prioridade_color = "#dc3545"; break;
                    default: $prioridade = "Média"; $prioridade_color = "#47a447";
                endswitch;
                $arr =  array(
                    "date" => $date,
                    "tipo" => $tipo,
                    "titulo" => $timeline["titulo"],
                    "msg" => $timeline["texto"],
                    "prioridade" => $prioridade,
                    "color" => $prioridade_color,
                    "modulo" => $timeline["modulo"]
                );
                $data[$mes][] = $arr;
                //echo date_format($date, 'g:ia \o\n l j F Y');
            endforeach;
            if(isset($data)):
                array_push($data, $arr);
                array_pop($data);
            endif;
            $dados['timeline'] = $data;
            //formações
            $obj_formacoes = new App_Model_formacaoModel();
            $obj_formadores = new App_Model_formadorModel();
            $formadores_raw = $obj_formadores->listaFormador();
            $tipos_formacao_raw = $obj_formacoes->get_tipo_formacao();
            //TODO: passar isto para um worker
            foreach ($formadores_raw as $formador) {
                $formadores[$formador["usu_id"]] = $formador;
            }
            foreach ($tipos_formacao_raw as $tipo_formacao) {
                $tipos_formacao[$tipo_formacao["form_tipo_id"]] = $tipo_formacao;
            }
           
            $total_etapas_formacoes = $obj_formacoes->get_total_etapas();
            $dados["formacoes"]["lista"] = $obj_formacoes->get_list_from_client_by_ref($parm['ref']);
            $dados["formacoes"]["total_etapas"] = !empty($total_etapas_formacoes[0]["COUNT"]) ? $total_etapas_formacoes[0]["COUNT"] : false;
            $dados["formacoes"]["formadores"] = $formadores;
            $dados["formacoes"]["tipos_formacao"] = $tipos_formacao;
            
            $model_contato = new App_Model_contatoModel();
            $lista_contato = $model_contato->contatoSelecionarCliente($cliente_lista[0]["cli_id"]);
            $model_leads = new App_Model_leadModel();
            $model_leads_status = new App_Model_leadStatusModel();
            $model_users = new App_Model_usuarioModel();
            $model_especialidade = new App_Model_especialidadeModel();
            
            //contactos / leads
            foreach($lista_contato as $index => $possible_lead){
                //if($index > 0) continue; //returns first
                if($possible_lead["cont_lead_status"] > 0){
                    $contactos_lead = $possible_lead;
                }
            }
            //lista de estados de leads disponíveis
            $lista_leads = $model_leads->listaLead();
            
            foreach($lista_leads as $lead){
                $output_leads[$lead["lead_id"]] = $lead;
            }
            //histórico de leads
            $model_leads_history = $model_leads_status->get_updates_from_client($cliente_lista[0]["cli_id"]);
            //todos os users
            $lista_users = $model_users->listaUsuario();
            foreach ($lista_users as $user) {
                $users[$user["usu_id"]] = $user;
            }
            $dados["leads_history"] = $model_leads_history;
            $dados["lista_leads"] = $output_leads;
            $dados["lista_users"] = $users;
            if(isset($contactos_lead)):            
                $dados["contactos_lead"] = $contactos_lead;
            else:
                $dados["contactos_lead"] = "";
            endif;
            $this->View("perfilCliente", $dados);
        }
        public function timeline(){
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $cliente_alt = new App_Model_clienteModel();
            $cliente_lista_alt = $cliente_alt->clienteSelecionarRef($dados['ref']);
            $dados['cliente'] = $cliente_lista_alt;
            $bd_timeline = $cliente_alt->clienteTimeline($cliente_lista_alt[0]["cli_id"]);
            $data = [];
            foreach($bd_timeline as $timeline):
                setlocale(LC_TIME, 'portuguese');
                //date_default_timezone_set('Europa/Lisbon');
                $dt = $timeline["data"];
                $date = strftime("%d %B %Y", strtotime($dt));
                $mes = strftime("%B %Y", strtotime($dt));
                switch ($timeline["tipo"]):
                    case 1 : $tipo = "Email"; break;
                    case 2 : $tipo = "Reunião"; break;
                    case 3 : $tipo = "Reunião de pré-agariação"; break;
                    case 4 : $tipo = "Telefonema"; break;
                    case 5 : $tipo = "Tarefa"; break;
                    case 6 : $tipo = "Qualificação Financeira"; break;
                    case 7 : $tipo = "Outro"; break;
                    default: $tipo = "";
                endswitch;
                switch ($timeline["prioridade"]):
                    case 1 : $prioridade = "Baixa"; $prioridade_color = "#0088cc"; break;
                    case 2 : $prioridade = "Média"; $prioridade_color = "#47a447"; break;
                    case 3 : $prioridade = "Alta"; $prioridade_color = "#fd7e14"; break;
                    case 4 : $prioridade = "Urgente"; $prioridade_color = "#dc3545"; break;
                    default: $prioridade = "Urgente"; $prioridade_color = "";
                endswitch;
                $arr =  array(
                    "date" => $date,
                    "tipo" => $tipo,
                    "titulo" => $timeline["titulo"],
                    "msg" => $timeline["texto"],
                    "prioridade" => $prioridade,
                    "color" => $prioridade_color,
                    "modulo" => $timeline["modulo"]
                );
                $data[$mes][] = $arr;
            endforeach;
            if(isset($data)):
                array_push($data, $arr);
                array_pop($data);
            endif;
            $dados['timeline'] = $data;
            $html = '';
            if (!empty( $data )):
            foreach ($data as $mes => $dt):
            $html .= '<div class="tm-title">
                        <h5 class="m-0 pt-2 pb-2 text-uppercase" style="font-weight: 600;">'.utf8_encode($mes).'</h5>
                      </div>';
                if( !empty($dt) ):
            $html .= '<ol class="tm-items">';
                    foreach ($dt as $timeline):
                        if($timeline["modulo"] == "followup"):
                            $timeline["modulo"] = "anotação";
                            $bk = "#0088cc";
                            $r_prioridade = '';
                        endif;
                        if($timeline["modulo"] == "tarefa"):
                            $bk = "#47a447";
                            $r_prioridade = '<p><span>Prioridade: </span> <span style="color:'.$timeline["color"].';">'.$timeline["prioridade"].'</span></p>';
                        endif;
            $html .= '<li>
                        <div class="tm-box">
                            <p class="text-muted mb-0"><strong>'.utf8_encode($timeline["date"]).'</strong> <span class="highlight" style="background-color:'.$bk.'">'.$timeline["modulo"].'</span> <span class="text-primary"> - '.$timeline["tipo"].'</span>
                            </p>
                            <h4><strong>'.$timeline["titulo"].'</strong></h4>
                            <p>
                                '.str_replace("|", "/", $timeline["msg"]).'
                            </p>
                            '.$r_prioridade.'
                        </div>
                      </li>';
                    endforeach;
            $html .= '</ol>';
                endif;
            endforeach;
            endif;
            echo $html;
        }
        function percent_change($then, $now){
            return ($now-$then) / $then * 100;
        }
        private function perfilComplete($ref, $dados){
            $title = array(
                "cli_nome" => "Nome",
                "cli_telefone" => "Telefone",
                "cli_email" => "Email",
                "cli_nascimento" => "Data de aniversário",
                "cli_nif" => "CPF / CNPJ",
                "cli_tipo" => "Tipo de cliente",
                "cli_rua" => "Endereço",
                "cli_cp" => "Código Postal",
                "cli_localidade" => "Cidade",
                "cli_distrito" => "UF"
            );
            $total = count($title);
            if(!empty($dados[0])):
                $soma = 0;
                $title_active = [];
                foreach ($dados[0] as $key => $value):
                    if(array_key_exists($key, $title)):
                        if(empty($value)):
                            $soma++;
                            $title_active[] = $title[$key];
                        endif;
                    endif;
                endforeach;
                $calc = self::percent_change($total, $soma);
                $calc = ($calc == 0) ? 100 : $calc;
                return array("porcentagem" => str_replace("-", "", $calc), "title" => $title_active) ;
            endif;
        }
        /* END PERFIL */
        public function alterar(){
            global $start;
            $parm = $start->_params;
            $cliente_alt = new App_Model_clienteModel();
            $cliente_lista_alt = $cliente_alt->clienteSelecionarRef($parm['ref']);
            $dados['cliente'] = $cliente_lista_alt;
            $model = new App_Model_areaModel();
            $lista = $model->listaArea();
            $dados["area"] = $lista;
            $model_produto = new App_Model_produtoModel();
            $lista_produto = $model_produto->listaProduto();
            $dados["produto"] = $lista_produto;
            $model_distrito = new App_Model_distritoModel();
            $lista_distrito = $model_distrito->listaDistritoStatus();
            $dados["distrito"] = $lista_distrito;
            $model_contato = new App_Model_contatoModel();
            $lista_contato = $model_contato->contatoSelecionarCliente($cliente_lista_alt[0]["cli_id"]);
            $dados["contato"] = $lista_contato;
            $model_cargo = new App_Model_cargoModel();
            $lista_cargo = $model_cargo->listaCargoStatus();
            $dados["cargo"] = $lista_cargo;
            $model_tipo = new App_Model_tipoModel();
            $lista_tipo = $model_tipo->listaTipoStatus();
            $dados["tipo"] = $lista_tipo;
            $model_especialidade = new App_Model_especialidadeModel();
            $lista_especialidade = $model_especialidade->listaEspecialidadeStatus();
            $dados["especialidade"] = $lista_especialidade;
            
            $model_lead = new App_Model_leadModel();
            $lista_leads = $model_lead->listaLead();
            $dados["leads"] = $lista_leads;
            $model_utilizador = new App_Model_usuarioModel();
            $lista_usuario = $model_utilizador->listaUsuarioNivel(3);          
            $dados["vendedor"] = $lista_usuario;
  /*           $model_vendedor = new App_Model_vendedorModel();
            $lista_vendedor = $model_vendedor->listaVendedor();
            $dados["vendedor"] = $lista_vendedor; */
            $this->View("alterarCliente", $dados);
        }
        public function alteracao(){
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $model = new App_Model_clienteModel();
            if(!empty($dados["contato"])):
                $arr_contato = explode("*", $dados["contato"]);
                $row = 0;
                foreach($arr_contato as $value):
                    $row++;
                    $arr = explode(",", $value);
                    //isto éo que define se é uma alteração ou não
                    if($arr[7] != 0):
                        $alt_item = array(
                            "nome" => $arr[1],
                            "cargo" => $arr[2],
                            "especialidade" => $arr[3],
                            "email" => $arr[4],
                            "telemovel" => $arr[5],
                            "cliente" => $dados["id"],
                            "id" => $arr[0],
                            "lead_status" => intval($arr[6]),
                            "alt" => $arr[7]
                        );
                        $arr_alt[$row] = $alt_item;
                    else:
                        $cad_item = array(
                            "nome" => $arr[1],
                            "cargo" => $arr[2],
                            "especialidade" => $arr[3],
                            "email" => $arr[4],
                            "telemovel" => $arr[5],
                            "cliente" => $dados["id"],
                            "id" => $arr[0],
                            "lead_status" => intval($arr[6])
                        );
                        $arr_cad[$row] = $cad_item;
                    endif;
                endforeach;
                if(isset($arr_cad)):
                    array_push($arr_cad, $cad_item);
                    array_pop($arr_cad);
                endif;
                if(isset($arr_alt)):
                    array_push($arr_alt, $alt_item);
                    array_pop($arr_alt);
                endif;
                $contato = new App_Model_contatoModel();
                if(isset($arr_cad)):
                    foreach ($arr_cad as $cont):
                        $send_contato = array(
                            "nome" => $cont["nome"],
                            "cargo" => $cont["cargo"],
                            "especialidade" => $cont["especialidade"],
                            "email" => $cont["email"],
                            "telemovel" => $cont["telemovel"],
                            "cliente" =>$cont["cliente"],
                            "contato" => $cont["id"],
                            "lead_status" => !empty($cont["lead_status"]) ? $cont["lead_status"] : 0
                        );
                        $rg_contato = $contato->contatoRegistar($send_contato);
                    endforeach;
                endif;
                if(isset($arr_alt)):
                    foreach ($arr_alt as $cont):
                        $alt_contato = array(
                            "nome" => $cont["nome"],
                            "cargo" => $cont["cargo"],
                            "especialidade" => $cont["especialidade"],
                            "email" => $cont["email"],
                            "telemovel" => $cont["telemovel"],
                            "cliente" =>$cont["cliente"],
                            "contato" => $cont["id"],
                            "id" => $cont["alt"],
                            "lead_status" => $cont["lead_status"]
                        );
                        $rg_contato = $contato->contatoAlteracao($alt_contato);
                        //obter info do contacto actual
                        $current_contact = $contato->contatoSelecionar($cont["alt"]);
                        //não houve alteração no status
                        if($current_contact[0]["cont_lead_status"] == $cont["lead_status"]){
                            
                        }
                        //houve alteração no status, guardar
                        else{
                            //ao alterar o contacto, registar na tabela de registo de leads
                            $model_lead_status = new App_Model_leadStatusModel();
                        
                            //registar o quê?
                            $log = array(
                                "client_id" => $cont["cliente"],
                                "status_message" => "Actualização de estado: <strong>" . $cont["nome"] . "</strong> para <label class=\"label label-success\">" . $model_lead_status->get_label($cont["lead_status"]) . "</label>",
                                "status_user_id" => $_SESSION["id_usuario"],
                                "status_type" => false,
                                "lead_status" => $cont["lead_status"]
                            );
                            //inserir na db
                            $register = $model_lead_status->lead_statusRegistar($log);
                        }                        
                    endforeach;
                endif;
            endif;
            $model_update = $model->clienteAlteracao($dados);
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
            $model = new App_Model_clienteModel();
            $sel_cliente = $model->clienteSelecionar($ident['id']);
            $model_contato = new App_Model_contatoModel();
            $del_contato = $model_contato->contatoDeletarCliente($sel_cliente[0]['cli_id']);
            $model_delete = $model->clienteDeletar($ident['id']);
            echo "Contato: ".$del_contato."... </br> Cliente: ".$model_delete."...";
        }
        public function img_deletar(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $model = new App_Model_clienteModel();
            $dados = $model->clienteSelecionar($ident['id']);             
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/cliente/'.$dados[0]['cli_imagem']);       
            $model_delete = $model->clienteDeletarIMG($dados[0]["cli_id"]);
            echo $model_delete;
        }
        /***************     PAGINA     *********************/
        //PESQUISAR
        public function pesquisar(){
            // seta o id do cliente
            // $id = new App_System();
            // $id->_urlAjax = $_POST['url'];
            // $id->setExplodeAjax();
            // $id->setControllerAjax();
            // $id->setActionAjax();
            // $id->setParamsAjax();
            // $ident = $id->getParamsAjax("id");
            $this->View("pesquisarCliente");
        }
        //BUSCAR
        public function resultado(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $contato = new App_Model_contatoModel();
            $distrito = new App_Model_distritoModel();
            $tipo = new App_Model_tipoModel();
            $area = new App_Model_areaModel();
            $produto = new App_Model_produtoModel();
            if(!empty($ident["datastart"]) && !empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => $ident["dataend"]);
                $base[] = $query;
            endif;
            if(!empty($ident["datastart"]) && empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => "");
                $base[] = $query;
            endif;
            if(!empty($ident["campo"])):
                $query = array("campo" => $ident["campo"]);
                $base[] = $query;
            endif;
            if(!empty($ident["vendedor"])):
                $query = array("usu_id" => $ident["vendedor"]);
                $base[] = $query;
            endif;
            if(!empty($ident["area"])):
                $query = array("cli_area" => $ident["area"] );
                $base[] = $query;
            endif;
            if(!empty($ident["produto"])):
                $query = array("cli_produto" => $ident["produto"] );
                $base[] = $query;
            endif;
            if(!empty($ident["tipo"])):
                $query = array("cli_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;
            if(!empty($ident["distrito"])):
                $query = array("cli_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;
            if(isset($base)):
                array_push($base, $query);
                array_pop($base);                
                $cliente = new App_Model_clienteModel();
                $cliente_lista = $cliente->clienteBuscar($base);
                $total = count($cliente_lista);
            else:                
                $cliente_lista = array();    
                $total = 0;
            endif;
            if( !empty($cliente_lista) ):
                //change 1.0
                $distritos_lista = $distrito->get_all_into_array();
                $contatos_lista = $contato->get_all_into_array();
                $tipo_lista = $tipo->get_all_into_array();
                foreach ($cliente_lista as $rs):
                    //$sel_contato = $contato->contatoSelecionarCliente($rs["cli_id"]);
                    //$sel_distrito = $distrito->distritoSelecionar($rs["cli_distrito"]);
                    //$sel_area = $area->areaSelecionarIn($rs["cli_area_negocio"]);
                    $sel_contato = (!empty($contatos_lista[$rs["cli_id"]]))? $contatos_lista[$rs["cli_id"]] : "";
                    $distrito2 = (!empty($distritos_lista[$rs["cli_distrito"]]))? $distritos_lista[$rs["cli_distrito"]] : "";
                    $sel_tipo = (!empty($tipo_lista[$rs["cli_tipo"]]))? $tipo_lista[$rs["cli_tipo"]] : "";
                    $vl_area = "";
                    if(!empty($sel_area)):
                      foreach ($sel_area as $area2):
                        $vl_area .= $area2["area_nome"].", ";
                      endforeach;
                    else:
                        $rs_area = "";
                    endif;
                    $rs_area = substr($vl_area,0,-2);
                    if(!empty($sel_distrito)):
                        $sl_distrito = $sel_distrito["sigla_distrito"];
                    else:
                        $sl_distrito = "";
                    endif;
                    if(!empty($sel_contato)):
                        $sl_contato = $sel_contato["cont_nome"];
                    else:
                        $sl_contato = "";
                    endif;
                    $arr = array(
                            "cli_id"=>$rs["cli_id"],
                            "cli_nome"=>$rs["cli_nome"],
                            "cli_rua"=>$rs["cli_rua"],
                            "cli_cp"=>$rs["cli_cp"],
                            "cli_localidade"=>$rs["cli_localidade"],
                            "cli_distrito"=>$sl_distrito,
                            "cli_email"=>$rs["cli_email"],
                            "cli_telefone"=>$rs["cli_telefone"],
                            "cli_area_negocio"=>$rs_area,
                            "cli_contato"=>$sl_contato,
                            "cli_referencia"=>$rs["cli_referencia"],
                            "cli_imagem"=>$rs["cli_imagem"]
                    );
                    $info[] = $arr;
                endforeach;
                if(isset($info)):
                    array_push($info, $arr);
                    array_pop($info);
                endif;
                $dados['cliente'] = $info;
                $dados['cliente_total'] = $total;
            else:
                $dados['cliente'] = array();
                $dados['cliente_total'] = array();
            endif;
            //$dados['cliente'] = $cliente_lista;
            $this->View("resultadoCliente",$dados);
        }
        //BUSCAR
        public function total(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $contato = new App_Model_contatoModel();
            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            if(!empty($ident["datastart"]) && !empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => $ident["dataend"]);
                $base[] = $query;
            endif;
            if(!empty($ident["datastart"]) && empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => "");
                $base[] = $query;
            endif;
            if(!empty($ident["campo"])):
                $query = array("campo" => $ident["campo"]);
                $base[] = $query;
            endif;
            if(!empty($ident["vendedor"])):
                $query = array("usu_id" => $ident["vendedor"]);
                $base[] = $query;
            endif;
            if(!empty($ident["area"])):
                $query = array("cli_area" => $ident["area"] );
                $base[] = $query;
            endif;
            if(!empty($ident["produto"])):
                $query = array("cli_produto" => $ident["produto"] );
                $base[] = $query;
            endif;
            if(!empty($ident["tipo"])):
                $query = array("cli_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;
            if(!empty($ident["distrito"])):
                $query = array("cli_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;
            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;
            $cliente = new App_Model_clienteModel();
            $cliente_lista = $cliente->clienteBuscar($base);
            $total = count($cliente_lista);
            if(!empty($total)):
                echo $total;
            else:
                echo "";
            endif;
        }
        //VIEW CLIENTE
        public function detalhe(){
            //seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");
            $cliente = new App_Model_clienteModel();
            $cliente_lista = $cliente->clienteSelecionar($ident);
            $contato = new App_Model_contatoModel();
            $cargo = new App_Model_cargoModel();
            $especialidade = new App_Model_especialidadeModel();
            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            $vendedor = new App_Model_usuarioModel();
            $tipo = new App_Model_tipoModel();
            if(!empty( $cliente_lista )):
	            foreach ($cliente_lista as $rs):
	                $sel_contato = $contato->contatoSelecionarCliente($rs["cli_id"]);
	                $sel_distrito = $distrito->distritoSelecionar($rs["cli_distrito"]);
                    $sel_tipo = $tipo->tipoSelecionar($rs["cli_tipo"]);
	                $sel_area = $area->areaSelecionarIn($rs["cli_area_negocio"]);
	                $sel_vendedor = $vendedor->usuarioSelecionar($rs["usu_id"]);
                    $sel_area = $area->areaSelecionarIn($rs["cli_area_negocio"]);
                    $vl_area = "";
                    if(!empty($sel_area)):
                      foreach ($sel_area as $area2):
                        $vl_area .= $area2["area_nome"].", ";
                      endforeach;
                    else:
                        $rs_area = "";
                    endif;
                    $rs_area = substr($vl_area,0,-2);
                    if(!empty($sel_tipo)):
                        $sl_tipo = $sel_tipo[0]["tipo_nome"];
                    else:
                        $sl_tipo = "";
                    endif;
                    if(!empty($sel_distrito)):
                        $sl_distrito = $sel_distrito[0]["sigla_distrito"];
                    else:
                        $sl_distrito = "";
                    endif;
                    if(!empty($sel_vendedor)):
                        $sl_vendedor = $sel_vendedor[0]["usu_nome"];
                    else:
                        $sl_vendedor = "";
                    endif;
                    if(!empty($sel_contato)):
                      foreach ($sel_contato as $item_contato):
                        $sel_cargo = $cargo->cargoSelecionar($item_contato["cont_cargo"]);
                        $sel_especialidade = $especialidade->especialidadeSelecionar($item_contato["cont_especialidade"]);
                        if(!empty($sel_cargo)):
                            $sl_cargo = $sel_cargo[0]["cargo_nome"];
                        else:
                            $sl_cargo = "";
                        endif;
                        if(!empty($sel_especialidade)):
                            $sl_especialidade = $sel_especialidade[0]["esp_nome"];
                        else:
                            $sl_especialidade = "";
                        endif;
                        $cont = array(
                                "cont_id"=>$item_contato["cont_id"],
                                "cont_nome"=>$item_contato["cont_nome"],
                                "cont_chave"=>$item_contato["cont_id"],
                                "cont_cargo"=>$sl_cargo,
                                "cont_especialidade"=>$sl_especialidade,
                                "cont_email"=>$item_contato["cont_email"],
                                "cont_telemovel"=>$item_contato["cont_telemovel"],
                                "cont_status"=>$item_contato["cont_status"],
                                "cli_id"=>$item_contato["cli_id"],
                                "cont_contato"=>$item_contato["cont_contato"],
                                "cont_data"=>$item_contato["cont_data"]
                        );
                        $dados_contato[] = $cont;
                      endforeach;
                    endif;
                    if(isset($dados_contato)):
                        array_push($dados_contato, $cont);
                        array_pop($dados_contato);
                    endif;
                    // <select id="cli_referencia" class="form-control">
                    //   <option value="0" selected>Nenhum</option>
                    //   <option value="1">Sr.</option>
                    //   <option value="2">Sra.</option>
                    //   <option value="3">Srta.</option>
                    //   <option value="4">Dr.</option>
                    //   <option value="5">Dra.</option>
                    // </select>
                    if($rs["cli_referencia"] == 0):
                        $rf = "";
                    elseif($rs["cli_referencia"] == 1):
                        $rf = "Sr.";
                    elseif($rs["cli_referencia"] == 2):
                        $rf = "Sra.";
                    elseif($rs["cli_referencia"] == 3):
                        $rf = "Srta.";
                    elseif($rs["cli_referencia"] == 4):
                        $rf = "Dr.";
                    elseif($rs["cli_referencia"] == 5):
                        $rf = "Dra.";
                    endif;
	                $arr = array(
                        "cli_id"=>$rs["cli_id"],
                        "cli_referencia"=>$rf,
                        "cli_nome"=>$rs["cli_nome"],
                        "cli_rua"=>$rs["cli_rua"],
                        "cli_numero"=>$rs["cli_numero"],
                        "cli_andar"=>$rs["cli_andar"],
                        "cli_cp"=>$rs["cli_cp"],
                        "cli_tipo"=>$sl_tipo,
                        "cli_localidade"=>$rs["cli_localidade"],
                        "cli_distrito"=>$sl_distrito,
                        "cli_email"=>$rs["cli_email"],
                        "cli_telefone"=>$rs["cli_telefone"],
                        "cli_url"=>$rs["cli_url"],
                        "cli_descricao"=> str_replace("|", "/", $rs["cli_descricao"]),
                        "cli_status"=>$rs["cli_status"],
                        "cli_area_negocio"=>$rs_area,
                        "cli_contato"=>$dados_contato,
                        "vendedor"=>$sl_vendedor,
                        "cli_imagem"=>$rs["cli_imagem"]
	                );
	                $info[] = $arr;
	            endforeach;
	            if(isset($info)):
	                array_push($info, $arr);
	                array_pop($info);
	            endif;
	            $dados['cliente'] = $info;
           	else:
                $dados['cliente'] = array();
            endif;
            $this->View("visualizarCliente", $dados);
        }
        public function pdf(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $base = [];
            if(!empty($ident["datastart"]) && !empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => $ident["dataend"]);
                $base[] = $query;
            endif;
            if(!empty($ident["datastart"]) && empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => "");
                $base[] = $query;
            endif;
            if(!empty($ident["campo"])):
                $query = array("campo" => $ident["campo"]);
                $base[] = $query;
            endif;
            if(!empty($ident["vendedor"])):
                $query = array("usu_id" => $ident["vendedor"]);
                $base[] = $query;
            endif;
            if(!empty($ident["area"])):
                $query = array("cli_area" => $ident["area"]);
                $base[] = $query;
            endif;
            if(!empty($ident["produto"])):
                $query = array("cli_produto" => $ident["produto"] );
                $base[] = $query;
            endif;
            if(!empty($ident["tipo"])):
                $query = array("cli_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;
            if(!empty($ident["distrito"])):
                $query = array("cli_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;
            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;
            $cliente = new App_Model_clienteModel();
            $cliente_lista = $cliente->clienteBuscar($base);
            $contato = new App_Model_contatoModel();
            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            $produto = new App_Model_produtoModel();
            $vendedor = new App_Model_usuarioModel();
            $tipo = new App_Model_tipoModel();
            if(!empty( $cliente_lista )):
                foreach ($cliente_lista as $rs):
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
                endforeach;
                if(isset($info)):
                    array_push($info, $arr);
                    array_pop($info);
                endif;
                $dados['cliente'] = $info;
            else:
                $dados['cliente'] = array();
            endif;
            $query = [];
            if(!empty( $base )):
                foreach($base as $item):
                    if( !empty( $item["dtstart"] ) && !empty( $item["dtend"] )):
                        $query["periodo"] = $item["dtstart"]." à ".$item["dtend"];
                    endif;
                    if( !empty( $item["dtstart"] ) && empty( $item["dtend"] )):
                        $query["dtstart"] = $item["dtstart"];
                    endif;
                    if( !empty( $item["cli_nome"] ) ):
                        $query["nome"] = trim($item["cli_nome"]);
                    endif;
                    if( !empty( $item["usu_id"] ) ):
                        $sel_vendedor = $vendedor->usuarioSelecionar($item["usu_id"]);
                        $query["vendedor"] = $sel_vendedor[0]["usu_nome"];
                    endif;
                    if( !empty( $item["cli_area"] ) ):
                        $sel_area = $area->areaSelecionarIn($item["cli_area"]);
                        $vl_area = "";
                        if(!empty($sel_area)):
                          foreach ($sel_area as $area):
                            $vl_area .= $area["area_nome"].", ";
                          endforeach;
                        endif;
                        $rs_area = substr($vl_area,0,-2);
                        $query["area"] = $rs_area;
                    endif;
                    if( !empty( $item["cli_produto"] ) ):
                        $sel_produto = $produto->produtoSelecionarIn($item["cli_produto"]);
                        $vl_produto = "";
                        if(!empty($sel_produto)):
                          foreach ($sel_produto as $produto):
                            $vl_produto .= $produto["prod_nome"].", ";
                          endforeach;
                        endif;
                        $rs_produto = substr($vl_produto,0,-2);
                        $query["produto"] = $rs_produto;
                    endif;
                    if( !empty( $item["cli_tipo"] ) ):
                        $sel_tipo = $tipo->tipoSelecionarIn($item["cli_tipo"]);
                        $vl_tipo = "";
                        if(!empty($sel_tipo)):
                          foreach ($sel_tipo as $tp):
                            $vl_tipo .= $tp["tipo_nome"].", ";
                          endforeach;
                        endif;
                        $rs_tipo = substr($vl_tipo,0,-2);
                        $query["tipo"] = $rs_tipo;
                    endif;
                    if( !empty( $item["cli_distrito"] ) ):
                        $sel_distrito = $distrito->distritoSelecionarIn($item["cli_distrito"]);
                        $vl_distrito = "";
                        if(!empty($sel_distrito)):
                          foreach ($sel_distrito as $dist):
                            $vl_distrito .= utf8_encode($dist["sigla_distrito"]).", ";
                          endforeach;
                        endif;
                        $rs_distrito = substr($vl_distrito,0,-2);
                        $query["distrito"] = $rs_distrito;
                    endif;
                    if( !empty( $item["cli_localidade"] ) ):
                        $query["localidade"] = trim($item["cli_localidade"]);
                    endif;
                endforeach;
                //$parm = substr($query,0,-1);
                $dados['pesquisa'] = $query;
            endif;
            $config = new App_Model_configModel();
            $sel_config = $config->configBuscar();
            $dados['config'] = $sel_config;
            $m_log = new App_Model_logDBGeradosModel();
            $insert_log = $m_log->logRegistar("pdf", $base);
            $this->View("relatorioPDF",$dados);
        }
        public function xls(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            if(!empty($ident["datastart"]) && !empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => $ident["dataend"]);
                $base[] = $query;
            endif;
            if(!empty($ident["datastart"]) && empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => "");
                $base[] = $query;
            endif;
            if(!empty($ident["campo"])):
                $query = array("campo" => $ident["campo"]);
                $base[] = $query;
            endif;
            if(!empty($ident["vendedor"])):
                $query = array("usu_id" => $ident["vendedor"]);
                $base[] = $query;
            endif;
            if(!empty($ident["area"])):
                $query = array("cli_area" => $ident["area"]);
                $base[] = $query;
            endif;
            if(!empty($ident["tipo"])):
                $query = array("cli_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;
            if(!empty($ident["distrito"])):
                $query = array("cli_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;
            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;
            $cliente = new App_Model_clienteModel();
            $cliente_lista = $cliente->clienteBuscar($base);
            $contato = new App_Model_contatoModel();
            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            $vendedor = new App_Model_usuarioModel();
            $tipo = new App_Model_tipoModel();
            if(!empty( $cliente_lista )):
                foreach ($cliente_lista as $rs):
                    $sel_contato = $contato->contatoSelecionarCliente($rs["cli_id"]);
                    $sel_distrito = $distrito->distritoSelecionar($rs["cli_distrito"]);
                    $sel_area = $area->areaSelecionarIn($rs["cli_area_negocio"]);
                    $arr = array(
                            "cli_nome"=>$rs["cli_nome"],
                            "cli_rua"=>$rs["cli_rua"],
                            "cli_cp"=>$rs["cli_cp"],
                            "cli_localidade"=>$rs["cli_localidade"],
                            "cli_distrito"=>$sel_distrito[0]["sigla_distrito"],
                            "cli_email"=>$rs["cli_email"],
                            "cli_telefone"=>$rs["cli_telefone"],
                            "cli_area_negocio"=>$sel_area,
                            "cli_contato"=>$sel_contato[0]["cont_nome"]
                    );
                    $info[] = $arr;
                endforeach;
                if(isset($info)):
                    array_push($info, $arr);
                    array_pop($info);
                endif;
                $dados['cliente'] = $info;
            else:
                $dados['cliente'] = array();
            endif;
            //campo/nome/vendedor/area/localidade/distrito/tipo/datastart/dataend/
            $query = "";
            if(!empty( $base )):
                foreach($base as $item):
                    if( !empty( $item["dtstart"] ) && !empty( $item["dtend"] )):
                        $query["periodo"] = $item["dtstart"]." à ".$item["dtend"];
                    endif;
                    if( !empty( $item["dtstart"] ) && empty( $item["dtend"] )):
                        $query["dtstart"] = $item["dtstart"];
                    endif;
                    if( !empty( $item["cli_nome"] ) ):
                        $query["nome"] = trim($item["cli_nome"]);
                    endif;
                    if( !empty( $item["usu_id"] ) ):
                        $sel_vendedor = $vendedor->usuarioSelecionar($item["usu_id"]);
                        $query["vendedor"] = $sel_vendedor[0]["usu_nome"];
                    endif;
                    if( !empty( $item["cli_area"] ) ):
                        $sel_area = $area->areaSelecionarIn($item["cli_area"]);
                        $vl_area = "";
                        if(!empty($sel_area)):
                          foreach ($sel_area as $area):
                            $vl_area .= $area["area_nome"].", ";
                          endforeach;
                        endif;
                        $rs_area = substr($vl_area,0,-2);
                        $query["area"] = $rs_area;
                    endif;
                    if( !empty( $item["cli_tipo"] ) ):
                        $sel_tipo = $tipo->tipoSelecionarIn($item["cli_tipo"]);
                        $vl_tipo = "";
                        if(!empty($sel_tipo)):
                          foreach ($sel_tipo as $tp):
                            $vl_tipo .= $tp["tipo_nome"].", ";
                          endforeach;
                        endif;
                        $rs_tipo = substr($vl_tipo,0,-2);
                        $query["tipo"] = $rs_tipo;
                    endif;
                    if( !empty( $item["cli_distrito"] ) ):
                        $sel_distrito = $distrito->distritoSelecionarIn($item["cli_distrito"]);
                        $vl_distrito = "";
                        if(!empty($sel_distrito)):
                          foreach ($sel_distrito as $dist):
                            $vl_distrito .= utf8_encode($dist["sigla_distrito"]).", ";
                          endforeach;
                        endif;
                        $rs_distrito = substr($vl_distrito,0,-2);
                        $query["distrito"] = $rs_distrito;
                    endif;
                    if( !empty( $item["cli_localidade"] ) ):
                        $query["localidade"] = trim($item["cli_localidade"]);
                    endif;
                endforeach;
                //$parm = substr($query,0,-1);
                $dados['pesquisa'] = $query;
            endif;
            $config = new App_Model_configModel();
            $sel_config = $config->configBuscar();
            $dados['config'] = $sel_config;
            //print_r($dados['cliente']);
            $this->View("relatorioXLS",$dados);
        }
        public function bdemail(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            if(!empty($ident["datastart"]) && !empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => $ident["dataend"]);
                $base[] = $query;
            endif;
            if(!empty($ident["datastart"]) && empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => "");
                $base[] = $query;
            endif;
            if(!empty($ident["campo"])):
                $query = array("campo" => $ident["campo"]);
                $base[] = $query;
            endif;
            if(!empty($ident["vendedor"])):
                $query = array("usu_id" => $ident["vendedor"]);
                $base[] = $query;
            endif;
            if(!empty($ident["area"])):
                $query = array("cli_area" => $ident["area"]);
                $base[] = $query;
            endif;
            if(!empty($ident["produto"])):
                $query = array("cli_produto" => $ident["produto"]);
                $base[] = $query;
            endif;
            if(!empty($ident["tipo"])):
                $query = array("cli_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;
            if(!empty($ident["distrito"])):
                $query = array("cli_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;
          
            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;
            $cliente = new App_Model_clienteModel();
            $cliente_lista = $cliente->clienteBuscar($base);
            $contato = new App_Model_contatoModel();
            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            $produto = new App_Model_produtoModel();
            $vendedor = new App_Model_usuarioModel();
            $tipo = new App_Model_tipoModel();
            if(!empty( $cliente_lista )):
                $arr = ""; 
            
                foreach ($cliente_lista as $rs):
                    
                    $sel_contato = $contato->contatoSelecionarCliente($rs["cli_id"]);                
                    $sel_distrito = $distrito->distritoSelecionar($rs["cli_distrito"]);                
                    $sel_area = $area->areaSelecionarIn($rs["cli_area_negocio"]);
                    $sel_produto = $produto->produtoSelecionarIn($rs["cli_produto"]);
                    if(!empty($rs["cli_email"])):
                        $arr .= strtolower(str_replace(" ", "", $rs["cli_email"])).",";
                    endif;
                endforeach;
                $resp = substr($arr,0,-1);
                
            else:
                $resp = "";
            endif;
            
            $html = "<textarea id='result_email' class='form-control' cols='100%' style='min-height:450px!important;'>".$resp."</textarea>";
            echo $html;
            //campo/nome/vendedor/area/localidade/distrito/tipo/datastart/dataend/
            
          
            //print_r($dados['cliente']);
            //$this->View("relatorioXLS",$dados);       
        }
    }
