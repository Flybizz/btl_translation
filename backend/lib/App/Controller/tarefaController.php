<?php
    class tarefa extends App_Controller{

        public function index_action(){
            return $this->View("indexTarefa");
        }

        public function complete(){                   
            $followup = new App_Model_tarefaModel();
            $rg_followup = $followup->tarefaComplete($_POST["ref"]);

            $output["title"] = "Sucesso";
            $output["status"] = "success";
            $output["message"] = "Alteração efectuada com sucesso";

            echo json_encode($output);
        }

        public function listar(){

            $tarefa_cad = new App_Model_tarefaModel();
            $tarefa_lista_cad = $tarefa_cad->listaTarefa();

            if(!empty($tarefa_lista_cad)):

                $tipo = new App_Model_imovelTipoTarefaModel();
                $cliente = new App_Model_clienteModel();
                
                foreach ($tarefa_lista_cad as $rs):
                            
	                $sel_tipo = $tipo->imovelSelecionar($rs["tar_tipo"]); 
                    $sel_cliente = $tipo->clienteSelecionar($rs["cli_id"]); 

   	                $arr = array(
                            "tar_id"=>$rs["tar_id"],
	                		"tar_referencia"=>$rs["tar_referencia"],
	                        "tar_tipo"=>$rs["tar_tipo"],
                            "tar_texto"=>$rs["tar_texto"],
                            "tar_data"=>$rs["tar_data"],
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
                     
            $tarefa = new App_Model_tarefaModel();
            $rg_tarefa = $tarefa->tarefaRegistar($dados);
    
            print_r($rg_tarefa);
        }


        /* ALTERAR */
        public function selecionar($view = NULL){
            // seta o id do tarefa
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");
            $tarefa_alt = new App_Model_tarefaModel();
            $tarefa_lista_alt = $tarefa_alt->tarefaSelecionar($ident);
            $dados['tarefa_lista_alt'] = $tarefa_lista_alt;

            $model = new App_Model_areaModel();
            $lista = $model->listaArea();
            $dados["area"] = $lista;

            $model_distrito = new App_Model_distritoModel();
            $lista_distrito = $model_distrito->listaDistritoStatus();
            $dados["distrito"] = $lista_distrito;

            $model_contato = new App_Model_contatoModel();
            $lista_contato = $model_contato->contatoSelecionarTarefa($tarefa_lista_alt[0]["tar_id"]);
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
                $this->View("alterarTarefa", $dados);
            endif;
        }

        /* PERFIL */
        public function perfil(){

            global $start;
            $parm = $start->_params;
                      
            $tarefa_alt = new App_Model_tarefaModel();
            $tarefa_lista_alt = $tarefa_alt->tarefaSelecionarRef($parm['ref']);
            $dados['tarefa'] = $tarefa_lista_alt;

            $model_distrito = new App_Model_distritoModel();
            $lista_distrito = $model_distrito->distritoSelecionar($tarefa_lista_alt[0]["tar_distrito"]);
            $dados["distrito"] = $lista_distrito;

            $model_fonte = new App_Model_imovelFonteModel();
            $sel_fonte = $model_fonte->imovelSelecionar($tarefa_lista_alt[0]["tar_fonte"]);
            $dados['fonte'] = $sel_fonte;
     
            $model_tipo = new App_Model_imovelTipoTarefaModel();
            $sel_tipo = $model_tipo->imovelSelecionar($tarefa_lista_alt[0]["tar_tipo"]);
            $dados['tipo'] = $sel_tipo;

            $dados['perfil_complete'] = self::perfilComplete($parm['ref'], $tarefa_lista_alt);
               
            $this->View("perfilTarefa", $dados);
        }

        function percent_change($then, $now){
            return ($now-$then) / $then * 100;
        }

        private function perfilComplete($ref, $dados){
            
            $title = array(                              
                "tar_nome" => "Nome",               
                "tar_telefone" => "Telefone", 
                "tar_email" => "Email", 
                "tar_nascimento" => "Data de aniversário", 
                "tar_nif" => "NIF", 
                "tar_cc_numero" => "CC / BI - Número", 
                "tar_cc_validade" => "CC / BI - Validade", 
                "tar_tipo" => "Tipo de tarefa", 
                "tar_angariacao" => "Angariação", 
                "tar_fonte" => "Fonte de contato", 
                "tar_morada" => "Morada", 
                "tar_numero" => "Número",              
                "tar_cp" => "Código Postal", 
                "tar_localidade" => "Localidade", 
                "tar_distrito" => "Distrito"             
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
                      
            $tarefa_alt = new App_Model_tarefaModel();
            $tarefa_lista_alt = $tarefa_alt->tarefaSelecionarRef($parm['ref']);
            $dados['tarefa'] = $tarefa_lista_alt;

            $model_distrito = new App_Model_distritoModel();
            $lista_distrito = $model_distrito->listaDistritoStatus();
            $dados["distrito"] = $lista_distrito;

            $model_fonte = new App_Model_imovelFonteModel();
            $all_fonte = $model_fonte->listaimovel();
            $dados['fonte'] = $all_fonte;

            $model_tipo = new App_Model_imovelTipoTarefaModel();
            $all_tipo = $model_tipo->listaimovelOrder();
            $dados['tipo'] = $all_tipo;
               
            $this->View("alterarTarefa", $dados);
        }

        //ALTERAÇÃO
        public function alteracao(){
            // seta o id do tarefa
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $model = new App_Model_tarefaModel();
            // $dados_alt = $model->tarefaSelecionar($dados['id']);

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
                            "tarefa" => $dados["id"],
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
                            "tarefa" => $dados["id"],
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
                            "tarefa" =>$cont["tarefa"],
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
                            "tarefa" =>$cont["tarefa"],
                            "contato" => $cont["id"],
                            "id" => $cont["alt"]
                        );
                        
                        $rg_contato = $contato->contatoAlteracao($alt_contato);

                    endforeach;
                endif;
            endif;
            
            $model_update = $model->tarefaAlteracao($dados);
            print_r($model_update);
        }

        //DELETAR
        public function deletar(){
            // seta o id do tarefa
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();

            $model = new App_Model_tarefaModel(); 
            $sel_tarefa = $model->tarefaSelecionar($ident['id']);

            $model_contato = new App_Model_contatoModel();            
            $del_contato = $model_contato->contatoDeletarTarefa($sel_tarefa[0]['tar_id']);
           
            $model_delete = $model->tarefaDeletar($ident['id']);
            echo "Contato: ".$del_contato."... </br> Tarefa: ".$model_delete."...";
        }

        /***************     PAGINA     *********************/
        

        //PESQUISAR
        public function pesquisar(){
            // seta o id do tarefa
            // $id = new App_System();
            // $id->_urlAjax = $_POST['url'];
            // $id->setExplodeAjax();
            // $id->setControllerAjax();
            // $id->setActionAjax();
            // $id->setParamsAjax();
            // $ident = $id->getParamsAjax("id");
                  
            $this->View("pesquisarTarefa");
        }

        //BUSCAR
        public function resultado(){

            // seta o id do tarefa
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
                $query = array("tar_area" => $ident["area"] );
                $base[] = $query;
            endif;

            if(!empty($ident["tipo"])):
                $query = array("tar_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;

            if(!empty($ident["distrito"])):
                $query = array("tar_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;

            //print_r($base);

            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;

            $tarefa = new App_Model_tarefaModel();
            $tarefa_lista = $tarefa->tarefaBuscar($base);

            $total = count($tarefa_lista);

            //print_r($tarefa_lista);

            if( !empty($tarefa_lista) ):            
                
                foreach ($tarefa_lista as $rs):
                    
                    $sel_contato = $contato->contatoSelecionarTarefa($rs["tar_id"]);                
                    $sel_distrito = $distrito->distritoSelecionar($rs["tar_distrito"]);                
                    $sel_area = $area->areaSelecionarIn($rs["tar_area_negocio"]);

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
                            "tar_id"=>$rs["tar_id"],
                            "tar_nome"=>$rs["tar_nome"],
                            "tar_rua"=>$rs["tar_rua"],
                            "tar_cp"=>$rs["tar_cp"],
                            "tar_localidade"=>$rs["tar_localidade"],
                            "tar_distrito"=>$sl_distrito,
                            "tar_email"=>$rs["tar_email"],
                            "tar_telefone"=>$rs["tar_telefone"],
                            "tar_area_negocio"=>$rs_area,
                            "tar_contato"=>$sl_contato
                    );
                    $info[] = $arr;
                endforeach;

                if(isset($info)):
                    array_push($info, $arr);
                    array_pop($info);
                endif;            

                $dados['tarefa'] = $info;
                $dados['tarefa_total'] = $total;

            else:

                $dados['tarefa'] = array();
                $dados['tarefa_total'] = array();

            endif;

            //$dados['tarefa'] = $tarefa_lista;
            $this->View("resultadoTarefa",$dados);  
                      
        }

        //BUSCAR
        public function total(){

            // seta o id do tarefa
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

                $query = array("tar_area" => $ident["area"] );

                $base[] = $query;
            endif;

            if(!empty($ident["tipo"])):
                $query = array("tar_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;

            if(!empty($ident["distrito"])):
                $query = array("tar_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;

            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;

            $tarefa = new App_Model_tarefaModel();
            $tarefa_lista = $tarefa->tarefaBuscar($base);

            $total = count($tarefa_lista);

            if(!empty($total)):
                echo $total;
            else:
                echo "";
            endif;
                      
        }


        //VIEW Tarefa
        public function detalhe(){
            //seta o id do tarefa
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");

            $tarefa = new App_Model_tarefaModel();
            $tarefa_lista = $tarefa->tarefaSelecionar($ident);
            
            $contato = new App_Model_contatoModel();
            $cargo = new App_Model_cargoModel();
            $especialidade = new App_Model_especialidadeModel(); 

            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            $vendedor = new App_Model_vendedorModel();
            $tipo = new App_Model_tipoModel();

            if(!empty( $tarefa_lista )): 
            
	            foreach ($tarefa_lista as $rs):
	                
	                $sel_contato = $contato->contatoSelecionarTarefa($rs["tar_id"]);
	                $sel_distrito = $distrito->distritoSelecionar($rs["tar_distrito"]); 
                    $sel_tipo = $tipo->tipoSelecionar($rs["tar_tipo"]);               
	                $sel_area = $area->areaSelecionarIn($rs["tar_area_negocio"]);
	                $sel_vendedor = $vendedor->vendedorSelecionar($rs["vend_id"]);
                    $sel_area = $area->areaSelecionarIn($rs["tar_area_negocio"]);

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
                                "tar_id"=>$item_contato["tar_id"],
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

                    // <select id="tar_referencia" class="form-control">
                    //   <option value="0" selected>Nenhum</option>
                    //   <option value="1">Sr.</option>
                    //   <option value="2">Sra.</option>
                    //   <option value="3">Srta.</option>
                    //   <option value="4">Dr.</option>
                    //   <option value="5">Dra.</option>
                    // </select>

                    if($rs["tar_referencia"] == 0):
                        $rf = "Nenhum";                    
                    elseif($rs["tar_referencia"] == 1): 
                        $rf = "Sr.";
                    elseif($rs["tar_referencia"] == 2): 
                        $rf = "Sra.";
                    elseif($rs["tar_referencia"] == 3): 
                        $rf = "Srta.";
                    elseif($rs["tar_referencia"] == 4): 
                        $rf = "Dr.";
                    elseif($rs["tar_referencia"] == 5): 
                        $rf = "Dra.";                  
                    endif;
           

	                $arr = array(
                            "tar_id"=>$rs["tar_id"],
                            "tar_referencia"=>$rf,
	                        "tar_nome"=>$rs["tar_nome"],
                            "tar_rua"=>$rs["tar_rua"],
                            "tar_numero"=>$rs["tar_numero"],
                            "tar_andar"=>$rs["tar_andar"],
                            "tar_cp"=>$rs["tar_cp"],
                            "tar_tipo"=>$sl_tipo,
	                        "tar_localidade"=>$rs["tar_localidade"],
	                        "tar_distrito"=>$sl_distrito,
	                        "tar_email"=>$rs["tar_email"],
	                        "tar_telefone"=>$rs["tar_telefone"],
                            "tar_url"=>$rs["tar_url"],
                            "tar_descricao"=> str_replace("|", "/", $rs["tar_descricao"]),
                            "tar_status"=>$rs["tar_status"],
	                        "tar_area_negocio"=>$rs_area,
	                        "tar_contato"=>$dados_contato,
	                        "vendedor"=>$sl_vendedor
	                );
	                $info[] = $arr;
	            endforeach;

	            if(isset($info)):
	                array_push($info, $arr);
	                array_pop($info);
	            endif;

	            $dados['tarefa'] = $info;
           	else:

                $dados['tarefa'] = array();
            endif;

            $this->View("visualizarTarefa", $dados);
        }

        public function pdf(){
            // seta o id do tarefa
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
                $query = array("tar_area" => $ident["area"]);
                $base[] = $query;
            endif;

            if(!empty($ident["tipo"])):
                $query = array("tar_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;

            if(!empty($ident["distrito"])):
                $query = array("tar_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;
          
            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;

            $tarefa = new App_Model_tarefaModel();
            $tarefa_lista = $tarefa->tarefaBuscar($base);

            $contato = new App_Model_contatoModel();
            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            $vendedor = new App_Model_vendedorModel();
            $tipo = new App_Model_tipoModel();

            if(!empty( $tarefa_lista )): 
            
                foreach ($tarefa_lista as $rs):
                    
                    $sel_contato = $contato->contatoSelecionarTarefa($rs["tar_id"]);                
                    $sel_distrito = $distrito->distritoSelecionar($rs["tar_distrito"]);                
                    $sel_area = $area->areaSelecionarIn($rs["tar_area_negocio"]);

                    $arr = array(
                            "tar_nome"=>$rs["tar_nome"],
                            "tar_rua"=>$rs["tar_rua"],
                            "tar_cp"=>$rs["tar_cp"],
                            "tar_localidade"=>$rs["tar_localidade"],
                            "tar_distrito"=>$sel_distrito[0]["nome_distrito"],
                            "tar_email"=>$rs["tar_email"],
                            "tar_telefone"=>$rs["tar_telefone"],
                            "tar_area_negocio"=>$sel_area,
                            "tar_contato"=>$sel_contato
                    );
                    $info[] = $arr;
                endforeach;

                if(isset($info)):
                    array_push($info, $arr);
                    array_pop($info);
                endif;

                $dados['tarefa'] = $info;

            else:

                $dados['tarefa'] = array();
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

                    if( !empty( $item["tar_nome"] ) ):

                        $query["nome"] = trim($item["tar_nome"]);
                    endif;

                    if( !empty( $item["vend_id"] ) ):

                        $sel_vendedor = $vendedor->vendedorSelecionar($item["vend_id"]);
                        $query["vendedor"] = $sel_vendedor[0]["vend_nome"];
                    endif;

                    if( !empty( $item["tar_area"] ) ):

                        $sel_area = $area->areaSelecionarIn($item["tar_area"]);
                        $vl_area = "";      
                        if(!empty($sel_area)):
                          foreach ($sel_area as $area):       
                            $vl_area .= $area["area_nome"].", ";
                          endforeach;                       
                        endif; 
                        $rs_area = substr($vl_area,0,-2);
                        $query["area"] = $rs_area;
                    endif;

                    if( !empty( $item["tar_tipo"] ) ):

                        $sel_tipo = $tipo->tipoSelecionarIn($item["tar_tipo"]);
                        $vl_tipo = "";      
                        if(!empty($sel_tipo)):
                          foreach ($sel_tipo as $tp):       
                            $vl_tipo .= $tp["tipo_nome"].", ";
                          endforeach;                       
                        endif; 
                        $rs_tipo = substr($vl_tipo,0,-2);
                        $query["tipo"] = $rs_tipo;
                    endif;

                    if( !empty( $item["tar_distrito"] ) ):

                        $sel_distrito = $distrito->distritoSelecionarIn($item["tar_distrito"]);
                        $vl_distrito = "";      
                        if(!empty($sel_distrito)):
                          foreach ($sel_distrito as $dist):       
                            $vl_distrito .= utf8_encode($dist["nome_distrito"]).", ";
                          endforeach;                       
                        endif; 
                        $rs_distrito = substr($vl_distrito,0,-2);
                        $query["distrito"] = $rs_distrito;
                    endif;

                    if( !empty( $item["tar_localidade"] ) ):

                        $query["localidade"] = trim($item["tar_localidade"]);
                    endif;

                endforeach; 

                //$parm = substr($query,0,-1);
                $dados['pesquisa'] = $query;

            endif;
          
            $config = new App_Model_configModel();
            $sel_config = $config->configBuscar();
            $dados['config'] = $sel_config;

            //print_r($dados['tarefa']);

            $this->View("relatorioPDF",$dados);       
        }

        public function xls(){
            // seta o id do tarefa
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
                $query = array("tar_area" => $ident["area"]);
                $base[] = $query;
            endif;

            if(!empty($ident["tipo"])):
                $query = array("tar_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;

            if(!empty($ident["distrito"])):
                $query = array("tar_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;
          
            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;

            $tarefa = new App_Model_tarefaModel();
            $tarefa_lista = $tarefa->tarefaBuscar($base);

            $contato = new App_Model_contatoModel();
            $distrito = new App_Model_distritoModel();
            $area = new App_Model_areaModel();
            $vendedor = new App_Model_vendedorModel();
            $tipo = new App_Model_tipoModel();

            if(!empty( $tarefa_lista )): 
            
                foreach ($tarefa_lista as $rs):
                    
                    $sel_contato = $contato->contatoSelecionarTarefa($rs["tar_id"]);                
                    $sel_distrito = $distrito->distritoSelecionar($rs["tar_distrito"]);                
                    $sel_area = $area->areaSelecionarIn($rs["tar_area_negocio"]);

                    $arr = array(
                            "tar_nome"=>$rs["tar_nome"],
                            "tar_rua"=>$rs["tar_rua"],
                            "tar_cp"=>$rs["tar_cp"],
                            "tar_localidade"=>$rs["tar_localidade"],
                            "tar_distrito"=>$sel_distrito[0]["nome_distrito"],
                            "tar_email"=>$rs["tar_email"],
                            "tar_telefone"=>$rs["tar_telefone"],
                            "tar_area_negocio"=>$sel_area,
                            "tar_contato"=>$sel_contato[0]["cont_nome"]
                    );
                    $info[] = $arr;
                endforeach;

                if(isset($info)):
                    array_push($info, $arr);
                    array_pop($info);
                endif;

                $dados['tarefa'] = $info;

            else:

                $dados['tarefa'] = array();
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

                    if( !empty( $item["tar_nome"] ) ):

                        $query["nome"] = trim($item["tar_nome"]);
                    endif;

                    if( !empty( $item["vend_id"] ) ):

                        $sel_vendedor = $vendedor->vendedorSelecionar($item["vend_id"]);
                        $query["vendedor"] = $sel_vendedor[0]["vend_nome"];
                    endif;

                    if( !empty( $item["tar_area"] ) ):

                        $sel_area = $area->areaSelecionarIn($item["tar_area"]);
                        $vl_area = "";      
                        if(!empty($sel_area)):
                          foreach ($sel_area as $area):       
                            $vl_area .= $area["area_nome"].", ";
                          endforeach;                       
                        endif; 
                        $rs_area = substr($vl_area,0,-2);
                        $query["area"] = $rs_area;
                    endif;

                    if( !empty( $item["tar_tipo"] ) ):

                        $sel_tipo = $tipo->tipoSelecionarIn($item["tar_tipo"]);
                        $vl_tipo = "";      
                        if(!empty($sel_tipo)):
                          foreach ($sel_tipo as $tp):       
                            $vl_tipo .= $tp["tipo_nome"].", ";
                          endforeach;                       
                        endif; 
                        $rs_tipo = substr($vl_tipo,0,-2);
                        $query["tipo"] = $rs_tipo;
                    endif;

                    if( !empty( $item["tar_distrito"] ) ):

                        $sel_distrito = $distrito->distritoSelecionarIn($item["tar_distrito"]);
                        $vl_distrito = "";      
                        if(!empty($sel_distrito)):
                          foreach ($sel_distrito as $dist):       
                            $vl_distrito .= utf8_encode($dist["nome_distrito"]).", ";
                          endforeach;                       
                        endif; 
                        $rs_distrito = substr($vl_distrito,0,-2);
                        $query["distrito"] = $rs_distrito;
                    endif;

                    if( !empty( $item["tar_localidade"] ) ):

                        $query["localidade"] = trim($item["tar_localidade"]);
                    endif;

                endforeach; 

                //$parm = substr($query,0,-1);
                $dados['pesquisa'] = $query;

            endif;
          
            $config = new App_Model_configModel();
            $sel_config = $config->configBuscar();
            $dados['config'] = $sel_config;

            //print_r($dados['tarefa']);

            $this->View("relatorioXLS",$dados);       
        }


    }