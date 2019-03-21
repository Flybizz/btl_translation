<?php
    class followup extends App_Controller{

        public function index_action(){
            return $this->View("indexFollowup");
        }

        public function complete(){                   
            $followup = new App_Model_followupModel();
            $rg_followup = $followup->followupComplete($_POST["ref"]);

            $output["title"] = "Sucesso";
            $output["status"] = "success";
            $output["message"] = "Alteração efectuada com sucesso";

            echo json_encode($output);
        }

        public function listar(){

            $followup_cad = new App_Model_followupModel();
            $followup_lista_cad = $followup_cad->listaFollowup();

            if(!empty($followup_lista_cad)):

                $tipo = new App_Model_imovelTipoFollowupModel();
                $cliente = new App_Model_clienteModel();
                
                foreach ($followup_lista_cad as $rs):
                            
	                $sel_tipo = $tipo->imovelSelecionar($rs["foll_tipo"]); 
                    $sel_cliente = $tipo->clienteSelecionar($rs["cli_id"]); 

   	                $arr = array(
                            "foll_id"=>$rs["foll_id"],
	                		"foll_referencia"=>$rs["foll_referencia"],
	                        "foll_tipo"=>$rs["foll_tipo"],
                            "foll_texto"=>$rs["foll_texto"],
                            "foll_data"=>$rs["foll_data"],
	                        "cliente"=>$sel_cliente	                        
	                );
	                $info[] = $arr;
                endforeach;
                
	            if(isset($info)):
	                array_push($info, $arr);
	                array_pop($info);
	            endif;            

	            return $info;          
              
            else:
                return array();
            endif;

        }

        /****************** CRUD ******************************/
        public function registar(){

            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
                     
            $followup = new App_Model_followupModel();
            $rg_followup = $followup->followupRegistar($dados);
    
            print_r($rg_followup);
        }


        /* ALTERAR */
        public function selecionar($view = NULL){
            // seta o id do followup
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");
            $followup_alt = new App_Model_followupModel();
            $followup_lista_alt = $followup_alt->followupSelecionar($ident);
            $dados['followup_lista_alt'] = $followup_lista_alt;

            $model = new App_Model_areaModel();
            $lista = $model->listaArea();
            $dados["area"] = $lista;

            $model_distrito = new App_Model_distritoModel();
            $lista_distrito = $model_distrito->listaDistritoStatus();
            $dados["distrito"] = $lista_distrito;

            $model_contato = new App_Model_contatoModel();
            $lista_contato = $model_contato->contatoSelecionarFollowup($followup_lista_alt[0]["foll_id"]);
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

            $model_vendedor = new App_Model_vendedorModel();
            $lista_vendedor = $model_vendedor->listaVendedor();
            $dados["vendedor"] = $lista_vendedor;

          
            if($view != NULL):
                return $dados;
            else:
                $this->View("alterarFollowup", $dados);
            endif;
        }

        /* PERFIL */
        public function perfil(){

            global $start;
            $parm = $start->_params;
                      
            $followup_alt = new App_Model_followupModel();
            $followup_lista_alt = $followup_alt->followupSelecionarRef($parm['ref']);
            $dados['followup'] = $followup_lista_alt;

            $model_distrito = new App_Model_distritoModel();
            $lista_distrito = $model_distrito->distritoSelecionar($followup_lista_alt[0]["foll_distrito"]);
            $dados["distrito"] = $lista_distrito;

            $model_fonte = new App_Model_imovelFonteModel();
            $sel_fonte = $model_fonte->imovelSelecionar($followup_lista_alt[0]["foll_fonte"]);
            $dados['fonte'] = $sel_fonte;
     
            $model_tipo = new App_Model_imovelTipoFollowupModel();
            $sel_tipo = $model_tipo->imovelSelecionar($followup_lista_alt[0]["foll_tipo"]);
            $dados['tipo'] = $sel_tipo;

            $dados['perfil_complete'] = self::perfilComplete($parm['ref'], $followup_lista_alt);
               
            $this->View("perfilFollowup", $dados);
        }

        function percent_change($then, $now){
            return ($now-$then) / $then * 100;
        }

        private function perfilComplete($ref, $dados){
            
            $title = array(                              
                "foll_nome" => "Nome",               
                "foll_telefone" => "Telefone", 
                "foll_email" => "Email", 
                "foll_nascimento" => "Data de aniversário", 
                "foll_nif" => "NIF", 
                "foll_cc_numero" => "CC / BI - Número", 
                "foll_cc_validade" => "CC / BI - Validade", 
                "foll_tipo" => "Tipo de followup", 
                "foll_angariacao" => "Angariação", 
                "foll_fonte" => "Fonte de contato", 
                "foll_morada" => "Morada", 
                "foll_numero" => "Número",              
                "foll_cp" => "Código Postal", 
                "foll_localidade" => "Localidade", 
                "foll_distrito" => "Distrito"             
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
                      
            $followup_alt = new App_Model_followupModel();
            $followup_lista_alt = $followup_alt->followupSelecionarRef($parm['ref']);
            $dados['followup'] = $followup_lista_alt;

            $model_distrito = new App_Model_distritoModel();
            $lista_distrito = $model_distrito->listaDistritoStatus();
            $dados["distrito"] = $lista_distrito;

            $model_fonte = new App_Model_imovelFonteModel();
            $all_fonte = $model_fonte->listaimovel();
            $dados['fonte'] = $all_fonte;

            $model_tipo = new App_Model_imovelTipoFollowupModel();
            $all_tipo = $model_tipo->listaimovelOrder();
            $dados['tipo'] = $all_tipo;
               
            $this->View("alterarFollowup", $dados);
        }

        //ALTERAÇÃO
        public function alteracao(){
            // seta o id do followup
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $model = new App_Model_followupModel();
            // $dados_alt = $model->followupSelecionar($dados['id']);

            if(!empty($dados["contato"])):

                $arr_contato = explode("*", $dados["contato"]);

                $row = 0;
                foreach($arr_contato as $value):

                    $row++;
                    $arr = explode(",", $value);

                    if($arr[6] != 0):

                        $alt_item = array(
                            "nome" => $arr[1],
                            "cargo" => $arr[2],
                            "especialidade" => $arr[3],
                            "email" => $arr[4],
                            "telemovel" => $arr[5],
                            "followup" => $dados["id"],
                            "id" => $arr[0],
                            "alt" => $arr[6]
                        );

                        $arr_alt[$row] = $alt_item;

                    else:

                        $cad_item = array(
                            "nome" => $arr[1],
                            "cargo" => $arr[2],
                            "especialidade" => $arr[3],
                            "email" => $arr[4],
                            "telemovel" => $arr[5],
                            "followup" => $dados["id"],
                            "id" => $arr[0]
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
                            "followup" =>$cont["followup"],
                            "contato" => $cont["id"]
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
                            "followup" =>$cont["followup"],
                            "contato" => $cont["id"],
                            "id" => $cont["alt"]
                        );
                        
                        $rg_contato = $contato->contatoAlteracao($alt_contato);

                    endforeach;
                endif;
            endif;
            
            $model_update = $model->followupAlteracao($dados);
            print_r($model_update);
        }

        //DELETAR
        public function deletar(){
            // seta o id do followup
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();

            $model = new App_Model_followupModel(); 
            $sel_followup = $model->followupSelecionar($ident['id']);

            $model_contato = new App_Model_contatoModel();            
            $del_contato = $model_contato->contatoDeletarFollowup($sel_followup[0]['foll_id']);
           
            $model_delete = $model->followupDeletar($ident['id']);
            echo "Contato: ".$del_contato."... </br> Followup: ".$model_delete."...";
        }

        /***************     PAGINA     *********************/
        

        //PESQUISAR
        public function pesquisar(){
            // seta o id do followup
            // $id = new App_System();
            // $id->_urlAjax = $_POST['url'];
            // $id->setExplodeAjax();
            // $id->setControllerAjax();
            // $id->setActionAjax();
            // $id->setParamsAjax();
            // $ident = $id->getParamsAjax("id");
                  
            $this->View("pesquisarFollowup");
        }

        //BUSCAR
        public function resultado(){

            // seta o id do followup
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

            print_r($ident["datastart"]);

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
                $query = array("vend_id" => $ident["vendedor"]);
                $base[] = $query;
            endif;

            if(!empty($ident["area"])):
                $query = array("foll_area" => $ident["area"] );
                $base[] = $query;
            endif;

            if(!empty($ident["tipo"])):
                $query = array("foll_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;

            if(!empty($ident["distrito"])):
                $query = array("foll_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;

            //print_r($base);

            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;

            $followup = new App_Model_followupModel();
            $followup_lista = $followup->followupBuscar($base);

            $total = count($followup_lista);

            //print_r($followup_lista);

            if( !empty($followup_lista) ):            
                
                foreach ($followup_lista as $rs):
                    
                    $sel_contato = $contato->contatoSelecionarFollowup($rs["foll_id"]);                
                    $sel_distrito = $distrito->distritoSelecionar($rs["foll_distrito"]);                
                    $sel_area = $area->areaSelecionarIn($rs["foll_area_negocio"]);

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
                        $sl_distrito = $sel_distrito[0]["nome_distrito"];
                    else:
                        $sl_distrito = "";  
                    endif; 

                    if(!empty($sel_contato)):
                        $sl_contato = $sel_contato[0]["cont_nome"];
                    else:
                        $sl_contato = "";  
                    endif;

                    $arr = array(
                            "foll_id"=>$rs["foll_id"],
                            "foll_nome"=>$rs["foll_nome"],
                            "foll_rua"=>$rs["foll_rua"],
                            "foll_cp"=>$rs["foll_cp"],
                            "foll_localidade"=>$rs["foll_localidade"],
                            "foll_distrito"=>$sl_distrito,
                            "foll_email"=>$rs["foll_email"],
                            "foll_telefone"=>$rs["foll_telefone"],
                            "foll_area_negocio"=>$rs_area,
                            "foll_contato"=>$sl_contato
                    );
                    $info[] = $arr;
                endforeach;

                if(isset($info)):
                    array_push($info, $arr);
                    array_pop($info);
                endif;            

                $dados['followup'] = $info;
                $dados['followup_total'] = $total;

            else:

                $dados['followup'] = array();
                $dados['followup_total'] = array();

            endif;

            //$dados['followup'] = $followup_lista;
            $this->View("resultadoFollowup",$dados);  
                      
        }

        //BUSCAR
        public function total(){

            // seta o id do followup
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
                $query = array("vend_id" => $ident["vendedor"]);
                $base[] = $query;
            endif;

            if(!empty($ident["area"])):

                $query = array("foll_area" => $ident["area"] );

                $base[] = $query;
            endif;

            if(!empty($ident["tipo"])):
                $query = array("foll_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;

            if(!empty($ident["distrito"])):
                $query = array("foll_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;

            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;

            $followup = new App_Model_followupModel();
            $followup_lista = $followup->followupBuscar($base);

            $total = count($followup_lista);

            if(!empty($total)):
                echo $total;
            else:
                echo "";
            endif;
                      
        }


        //VIEW Followup
        public function detalhe(){
            //seta o id do followup
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");

            $followup = new App_Model_followupModel();
            $followup_lista = $followup->followupSelecionar($ident);
            
            $contato = new App_Model_contatoModel();
            $cargo = new App_Model_cargoModel();
            $especialidade = new App_Model_especialidadeModel(); 

            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            $vendedor = new App_Model_vendedorModel();
            $tipo = new App_Model_tipoModel();

            if(!empty( $followup_lista )): 
            
	            foreach ($followup_lista as $rs):
	                
	                $sel_contato = $contato->contatoSelecionarFollowup($rs["foll_id"]);
	                $sel_distrito = $distrito->distritoSelecionar($rs["foll_distrito"]); 
                    $sel_tipo = $tipo->tipoSelecionar($rs["foll_tipo"]);               
	                $sel_area = $area->areaSelecionarIn($rs["foll_area_negocio"]);
	                $sel_vendedor = $vendedor->vendedorSelecionar($rs["vend_id"]);
                    $sel_area = $area->areaSelecionarIn($rs["foll_area_negocio"]);

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
                        $sl_distrito = $sel_distrito[0]["nome_distrito"];
                    else:
                        $sl_distrito = "";  
                    endif;

                    if(!empty($sel_vendedor)):
                        $sl_vendedor = $sel_vendedor[0]["vend_nome"];
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
                                "foll_id"=>$item_contato["foll_id"],
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

                    // <select id="foll_referencia" class="form-control">
                    //   <option value="0" selected>Nenhum</option>
                    //   <option value="1">Sr.</option>
                    //   <option value="2">Sra.</option>
                    //   <option value="3">Srta.</option>
                    //   <option value="4">Dr.</option>
                    //   <option value="5">Dra.</option>
                    // </select>

                    if($rs["foll_referencia"] == 0):
                        $rf = "Nenhum";                    
                    elseif($rs["foll_referencia"] == 1): 
                        $rf = "Sr.";
                    elseif($rs["foll_referencia"] == 2): 
                        $rf = "Sra.";
                    elseif($rs["foll_referencia"] == 3): 
                        $rf = "Srta.";
                    elseif($rs["foll_referencia"] == 4): 
                        $rf = "Dr.";
                    elseif($rs["foll_referencia"] == 5): 
                        $rf = "Dra.";                  
                    endif;
           

	                $arr = array(
                            "foll_id"=>$rs["foll_id"],
                            "foll_referencia"=>$rf,
	                        "foll_nome"=>$rs["foll_nome"],
                            "foll_rua"=>$rs["foll_rua"],
                            "foll_numero"=>$rs["foll_numero"],
                            "foll_andar"=>$rs["foll_andar"],
                            "foll_cp"=>$rs["foll_cp"],
                            "foll_tipo"=>$sl_tipo,
	                        "foll_localidade"=>$rs["foll_localidade"],
	                        "foll_distrito"=>$sl_distrito,
	                        "foll_email"=>$rs["foll_email"],
	                        "foll_telefone"=>$rs["foll_telefone"],
                            "foll_url"=>$rs["foll_url"],
                            "foll_descricao"=> str_replace("|", "/", $rs["foll_descricao"]),
                            "foll_status"=>$rs["foll_status"],
	                        "foll_area_negocio"=>$rs_area,
	                        "foll_contato"=>$dados_contato,
	                        "vendedor"=>$sl_vendedor
	                );
	                $info[] = $arr;
	            endforeach;

	            if(isset($info)):
	                array_push($info, $arr);
	                array_pop($info);
	            endif;

	            $dados['followup'] = $info;
           	else:

                $dados['followup'] = array();
            endif;

            $this->View("visualizarFollowup", $dados);
        }

        public function pdf(){
            // seta o id do followup
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
                $query = array("vend_id" => $ident["vendedor"]);
                $base[] = $query;
            endif;

            if(!empty($ident["area"])):
                $query = array("foll_area" => $ident["area"]);
                $base[] = $query;
            endif;

            if(!empty($ident["tipo"])):
                $query = array("foll_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;

            if(!empty($ident["distrito"])):
                $query = array("foll_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;
          
            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;

            $followup = new App_Model_followupModel();
            $followup_lista = $followup->followupBuscar($base);

            $contato = new App_Model_contatoModel();
            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            $vendedor = new App_Model_vendedorModel();
            $tipo = new App_Model_tipoModel();

            if(!empty( $followup_lista )): 
            
                foreach ($followup_lista as $rs):
                    
                    $sel_contato = $contato->contatoSelecionarFollowup($rs["foll_id"]);                
                    $sel_distrito = $distrito->distritoSelecionar($rs["foll_distrito"]);                
                    $sel_area = $area->areaSelecionarIn($rs["foll_area_negocio"]);

                    $arr = array(
                            "foll_nome"=>$rs["foll_nome"],
                            "foll_rua"=>$rs["foll_rua"],
                            "foll_cp"=>$rs["foll_cp"],
                            "foll_localidade"=>$rs["foll_localidade"],
                            "foll_distrito"=>$sel_distrito[0]["nome_distrito"],
                            "foll_email"=>$rs["foll_email"],
                            "foll_telefone"=>$rs["foll_telefone"],
                            "foll_area_negocio"=>$sel_area,
                            "foll_contato"=>$sel_contato
                    );
                    $info[] = $arr;
                endforeach;

                if(isset($info)):
                    array_push($info, $arr);
                    array_pop($info);
                endif;

                $dados['followup'] = $info;

            else:

                $dados['followup'] = array();
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

                    if( !empty( $item["foll_nome"] ) ):

                        $query["nome"] = trim($item["foll_nome"]);
                    endif;

                    if( !empty( $item["vend_id"] ) ):

                        $sel_vendedor = $vendedor->vendedorSelecionar($item["vend_id"]);
                        $query["vendedor"] = $sel_vendedor[0]["vend_nome"];
                    endif;

                    if( !empty( $item["foll_area"] ) ):

                        $sel_area = $area->areaSelecionarIn($item["foll_area"]);
                        $vl_area = "";      
                        if(!empty($sel_area)):
                          foreach ($sel_area as $area):       
                            $vl_area .= $area["area_nome"].", ";
                          endforeach;                       
                        endif; 
                        $rs_area = substr($vl_area,0,-2);
                        $query["area"] = $rs_area;
                    endif;

                    if( !empty( $item["foll_tipo"] ) ):

                        $sel_tipo = $tipo->tipoSelecionarIn($item["foll_tipo"]);
                        $vl_tipo = "";      
                        if(!empty($sel_tipo)):
                          foreach ($sel_tipo as $tp):       
                            $vl_tipo .= $tp["tipo_nome"].", ";
                          endforeach;                       
                        endif; 
                        $rs_tipo = substr($vl_tipo,0,-2);
                        $query["tipo"] = $rs_tipo;
                    endif;

                    if( !empty( $item["foll_distrito"] ) ):

                        $sel_distrito = $distrito->distritoSelecionarIn($item["foll_distrito"]);
                        $vl_distrito = "";      
                        if(!empty($sel_distrito)):
                          foreach ($sel_distrito as $dist):       
                            $vl_distrito .= utf8_encode($dist["nome_distrito"]).", ";
                          endforeach;                       
                        endif; 
                        $rs_distrito = substr($vl_distrito,0,-2);
                        $query["distrito"] = $rs_distrito;
                    endif;

                    if( !empty( $item["foll_localidade"] ) ):

                        $query["localidade"] = trim($item["foll_localidade"]);
                    endif;

                endforeach; 

                //$parm = substr($query,0,-1);
                $dados['pesquisa'] = $query;

            endif;
          
            $config = new App_Model_configModel();
            $sel_config = $config->configBuscar();
            $dados['config'] = $sel_config;

            //print_r($dados['followup']);

            $this->View("relatorioPDF",$dados);       
        }

        public function xls(){
            // seta o id do followup
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
                $query = array("vend_id" => $ident["vendedor"]);
                $base[] = $query;
            endif;

            if(!empty($ident["area"])):
                $query = array("foll_area" => $ident["area"]);
                $base[] = $query;
            endif;

            if(!empty($ident["tipo"])):
                $query = array("foll_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;

            if(!empty($ident["distrito"])):
                $query = array("foll_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;
          
            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;

            $followup = new App_Model_followupModel();
            $followup_lista = $followup->followupBuscar($base);

            $contato = new App_Model_contatoModel();
            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            $vendedor = new App_Model_vendedorModel();
            $tipo = new App_Model_tipoModel();

            if(!empty( $followup_lista )): 
            
                foreach ($followup_lista as $rs):
                    
                    $sel_contato = $contato->contatoSelecionarFollowup($rs["foll_id"]);                
                    $sel_distrito = $distrito->distritoSelecionar($rs["foll_distrito"]);                
                    $sel_area = $area->areaSelecionarIn($rs["foll_area_negocio"]);

                    $arr = array(
                            "foll_nome"=>$rs["foll_nome"],
                            "foll_rua"=>$rs["foll_rua"],
                            "foll_cp"=>$rs["foll_cp"],
                            "foll_localidade"=>$rs["foll_localidade"],
                            "foll_distrito"=>$sel_distrito[0]["nome_distrito"],
                            "foll_email"=>$rs["foll_email"],
                            "foll_telefone"=>$rs["foll_telefone"],
                            "foll_area_negocio"=>$sel_area,
                            "foll_contato"=>$sel_contato[0]["cont_nome"]
                    );
                    $info[] = $arr;
                endforeach;

                if(isset($info)):
                    array_push($info, $arr);
                    array_pop($info);
                endif;

                $dados['followup'] = $info;

            else:

                $dados['followup'] = array();
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

                    if( !empty( $item["foll_nome"] ) ):

                        $query["nome"] = trim($item["foll_nome"]);
                    endif;

                    if( !empty( $item["vend_id"] ) ):

                        $sel_vendedor = $vendedor->vendedorSelecionar($item["vend_id"]);
                        $query["vendedor"] = $sel_vendedor[0]["vend_nome"];
                    endif;

                    if( !empty( $item["foll_area"] ) ):

                        $sel_area = $area->areaSelecionarIn($item["foll_area"]);
                        $vl_area = "";      
                        if(!empty($sel_area)):
                          foreach ($sel_area as $area):       
                            $vl_area .= $area["area_nome"].", ";
                          endforeach;                       
                        endif; 
                        $rs_area = substr($vl_area,0,-2);
                        $query["area"] = $rs_area;
                    endif;

                    if( !empty( $item["foll_tipo"] ) ):

                        $sel_tipo = $tipo->tipoSelecionarIn($item["foll_tipo"]);
                        $vl_tipo = "";      
                        if(!empty($sel_tipo)):
                          foreach ($sel_tipo as $tp):       
                            $vl_tipo .= $tp["tipo_nome"].", ";
                          endforeach;                       
                        endif; 
                        $rs_tipo = substr($vl_tipo,0,-2);
                        $query["tipo"] = $rs_tipo;
                    endif;

                    if( !empty( $item["foll_distrito"] ) ):

                        $sel_distrito = $distrito->distritoSelecionarIn($item["foll_distrito"]);
                        $vl_distrito = "";      
                        if(!empty($sel_distrito)):
                          foreach ($sel_distrito as $dist):       
                            $vl_distrito .= utf8_encode($dist["nome_distrito"]).", ";
                          endforeach;                       
                        endif; 
                        $rs_distrito = substr($vl_distrito,0,-2);
                        $query["distrito"] = $rs_distrito;
                    endif;

                    if( !empty( $item["foll_localidade"] ) ):

                        $query["localidade"] = trim($item["foll_localidade"]);
                    endif;

                endforeach; 

                //$parm = substr($query,0,-1);
                $dados['pesquisa'] = $query;

            endif;
          
            $config = new App_Model_configModel();
            $sel_config = $config->configBuscar();
            $dados['config'] = $sel_config;

            //print_r($dados['followup']);

            $this->View("relatorioXLS",$dados);       
        }


    }