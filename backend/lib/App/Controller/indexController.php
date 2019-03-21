<?php
    class index extends App_Controller{
        /* public function index_action(){
          	
          	$config = unserialize (CONFIG_DB);  

          	$model = new App_Model_clienteModel();
            if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
                $parm = "";
            else:
                $parm = " vend_id = ".$_SESSION['vendedor_acesso']."";
            endif;

            $total = $model->parmCliente("COUNT(*) as TOTAL",$parm,"");
            $dados["parm_total"] = $total;

            $lista = $model->listaClienteLimit();
            $dados["cliente"] = $lista;

            $colunas = $model->selecionaColunas();
            $dados["colunas"] = $colunas;

            $vendedor = new App_Model_vendedorModel();
            $vendedor_lista = $vendedor->listaVendedor();
            $dados['vendedor'] = $vendedor_lista;

            $area = new App_Model_areaModel();
            $area_lista = $area->listaArea();
            $dados['area'] = $area_lista;

            $produto = new App_Model_produtoModel();
            $produto_lista = $produto->listaProduto();
            $dados['produto'] = $produto_lista;

            $tipo = new App_Model_tipoModel();
            $tipo_lista = $tipo->listaTipo();
            $dados['tipo'] = $tipo_lista;

            $distrito = new App_Model_distritoModel();
            $distrito_lista = $distrito->listaDistritoStatus();
            $dados['distrito'] = $distrito_lista;        

            $score = new App_Model_scoreModel();
            $score_lista = $score->listaScore();
            $dados['score'] = $score_lista;
   
          //CHAMA INDEX BACKEND
          parent::View("index", $dados);
        }  */

        public function index_action(){

            $config = unserialize (CONFIG_DB);  

          	$model = new App_Model_clienteModel();
            if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
                $parm = "";
            else:
                $parm = " usu_id = ".$_SESSION['id_usuario']."";
            endif;

            $total = $model->parmCliente("COUNT(*) as TOTAL",$parm,"");    
            $dados["cliente_total"] = $total[0]['TOTAL'];

            $formacao_obj = new App_Model_formacaoModel();
            $total_formacao = $formacao_obj->countFormacao("COUNT(*) as COUNT", null, null);  
            $dados["count_formacao"] = $total_formacao[0]['COUNT'];
            
            $total_rgpd = $model->parmCliente("COUNT(*) as RGPD"," cli_rgpd = 'true' ","");  
            $dados["cliente_rgpd"] = $total_rgpd[0]['RGPD'];

            /* LEADS CONVERSION */
            $m_contato = new App_Model_contatoModel();
            $contato_nclassification = $model->clienteSClassification();
            /* $contato_leads = $m_contato->contatoLeads(); */
            $contato_leads = $m_contato->contatoWithCliente(1);
            $contato_prospects = $m_contato->contatoWithCliente(2);
            $contato_clients = $m_contato->contatoWithCliente(3);

            $dados["funil_visitantes"] = count($contato_nclassification);
            $dados["funil_leads"] = count($contato_leads);
            $dados["funil_conversao"] = count($contato_prospects);
            $dados["funil_clientes"] = count($contato_clients);
                        
            /* END LEAD CONVERSION */

            $lista = $model->listaClienteLimit();
            $dados["cliente"] = $lista;

            $colunas = $model->selecionaColunas();
            $dados["colunas"] = $colunas;

            
            $vendedor = new App_Model_usuarioModel();
            $vendedor_lista = $vendedor->listaUsuarioNivel(3);
            $dados['vendedor'] = $vendedor_lista;
            
            $area = new App_Model_areaModel();
            $area_lista = $area->listaArea();
            $dados['area'] = $area_lista;
            
            $produto = new App_Model_produtoModel();
            $produto_lista = $produto->listaProduto();
            $dados['produto'] = $produto_lista;
            
            $tipo = new App_Model_tipoModel();
            $tipo_lista = $tipo->listaTipo();
            $dados['tipo'] = $tipo_lista;
            
            $distrito = new App_Model_distritoModel();
            $distrito_lista = $distrito->listaDistritoStatus();
            $dados['distrito'] = $distrito_lista;        
            
            $score = new App_Model_scoreModel();
            $score_lista = $score->listaScore();
            $dados['score'] = $score_lista;
            
            $cliente_cad = new App_Model_clienteModel();
            $cliente_lista_cad = $cliente_cad->listaCliente();
           
            $tarefa_obj = new App_Model_tarefaModel();
            $follow_up = new App_Model_followupModel();
            
            $dados["tarefas_dashboard"] = $tarefa_obj->listaTarefasDashboard();
            $dados["tarefas_dashboard_timeline"] = $tarefa_obj->listaTarefasDashboardFiltradas();
            //$dados["tarefas_resumo"] = $tarefa_obj->listaResumoDashboard();          

            if(!empty($lista)):
                
                $contato = new App_Model_contatoModel();
                $tipo = new App_Model_tipoModel();
                $model_cargo = new App_Model_cargoModel();
                $lista_cargo = $model_cargo->listaCargoStatus();

                //change 1.0
                $distritos_lista = $distrito->get_all_into_array();
                $contatos_lista = $contato->get_all_into_array();
               

                foreach ($lista as $rs):

	                //$sel_distrito = $distrito->distritoSelecionar($rs["cli_distrito"]);
	                //$sel_tipo = $tipo->tipoSelecionar($rs["cli_tipo"]);
                    //$sel_contato = $contato->contatoSelecionarCliente($rs["cli_id"]);
                    //$distrito2 = (!empty($sel_distrito))? $sel_distrito[0]["nome_distrito"] : "";
                    $sel_contato = (!empty($contatos_lista[$rs["cli_id"]]))? $contatos_lista[$rs["cli_id"]] : "";
                    $distrito2 = (!empty($distritos_lista[$rs["cli_distrito"]]))? $distritos_lista[$rs["cli_distrito"]] : "";

                    
                    $tipo2 = (!empty($sel_tipo))? $sel_tipo[0]["tipo_nome"] : "";

   	                $arr = array(
                        "cli_id"=>$rs["cli_id"],
                        "cli_referencia"=>$rs["cli_referencia"],
                        "cli_nome"=>$rs["cli_nome"],
                        "cli_rua"=>$rs["cli_rua"],
                        "cli_numero"=>$rs["cli_numero"],
                        "cli_andar"=>$rs["cli_andar"],                        
                        "cli_cp"=>$rs["cli_cp"],
                        "cli_localidade"=>$rs["cli_localidade"],
                        "cli_distrito"=> $distrito2,
                        "cli_email"=>$rs["cli_email"],
                        "cli_telefone"=>$rs["cli_telefone"],
                        "cli_tipo"=>$tipo2,
                        "cli_contato"=>$sel_contato,
                        "cli_imagem"=>$rs["cli_imagem"]
	                );
	                $info[] = $arr;
                endforeach;

	            if(isset($info)):
	                array_push($info, $arr);
	                array_pop($info);
	            endif;

	            $dados['dados'] = $info;
	            $dados['cliente_all'] = $cliente_lista_cad;
                //echo json_encode($usuario_lista_cad);
                //funcao que chama a view
                $this->View("index",$dados);
            else:
                //funcao que chama a view
                $this->View("index",$dados);
            endif;

        }
  
                
    }