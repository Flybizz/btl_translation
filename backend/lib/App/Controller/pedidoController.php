<?php
    class pedido extends App_Controller{
        
        public function index_action(){
            
            return $this->View("indexPedido");
            
        }
        
        /****************** CRUD ******************************/       
       

        public function inserir(){
            
             // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            //VERIFICAR cLIENTE
            $model_cliente = new App_Model_clienteModel();
            $sel_cliente = $model_cliente->clienteSelecionar2($dados['usuario']);

            $numero_pedido = date("ymdhis").$sel_cliente[0]["cli_id"];

            $model = new App_Model_pedidoModel();                  
    
            $arr_ped = array( 'ped_numero'=>$numero_pedido,
                              'cli_id'=>$sel_cliente[0]['cli_id'],
                              'ped_status'=>0
                             ); 

            //print_r($arr_ped);
            
            
            $model_inserir = $model->pedidoCadastrar($arr_ped);

            $sel_pedido = $model->pedidoSelecionar2($numero_pedido);

            //print_r($sel_pedido);

            $id_pedido = $sel_pedido[0]["ped_id"];

            //print_r($_SESSION['pedido']);

            $item_count = 0;

            $model_item = new App_Model_itemModel();
            $model_curso = new App_Model_cursoModel();

            foreach ($_SESSION['pedido'] as $item):

                $item_count++;

                $arr_item = array(
                    'ped_id' => $id_pedido,                          
                    'prod_id' => $item['produto'],                          
                    'item_qtd' => $item['qtd'],
                    'item_valor' => $item['valor'],
                    'item_total' => $item['total']);   

                //print_r($arr_item);

                
                $model_inserir_item = $model_item->itemCadastrar($arr_item);
                $sel_item = $model_item->itemSelecionarEnd();

                $sel_curso = $model_curso->cursoSelecionar($item['produto']);

                //Itens
                $data['pagseguro']['itemId'.$item_count] = $sel_item[0]['item_id'];
                $data['pagseguro']['itemDescription'.$item_count] = $sel_curso[0]['D016_Titulo'];
                $data['pagseguro']['itemAmount'.$item_count] = number_format($item['valor'],2,".","");
                $data['pagseguro']['itemQuantity'.$item_count] = $item['qtd'];
                $data['pagseguro']['itemWeight'.$item_count] = '0';
                
               
            endforeach;

            //print_r($model_inserir_item);
           
            if(!empty($model_inserir_item)):

                unset($_SESSION["pedido"]);
                unset( $_SESSION["produto"]);
                unset( $_SESSION["cupom-valido"]);
                unset( $_SESSION["cupom-valor"]);
                unset( $_SESSION["cupom-curso"]);
                unset( $_SESSION["cupom-error"]);

            endif;  

            //print_r($model_inserir_item);
                       
            
            //Dados do pedido
            $data['pagseguro']['reference'] = $numero_pedido;      
                
            //Dados do comprador
            
            //Tratar telefone
            $telefone = implode("",explode("-",substr($sel_cliente[0]["cli_telefone"],5,strlen($sel_cliente[0]["cli_telefone"]))));
            $ddd = substr($sel_cliente[0]["cli_telefone"],1,2);
            
            //Tratar CEP
            $cep = implode("",explode("-",$sel_cliente[0]["cli_cep"]));
            $cep = implode("",explode(".",$cep));
            
            $data['pagseguro']['senderName'] = $sel_cliente[0]["cli_nome"];
            // $data['pagseguro']['senderAreaCode'] = $ddd;
            // $data['pagseguro']['senderPhone'] = $telefone;
            $data['pagseguro']['senderEmail'] = $sel_cliente[0]["cli_email"];
            $data['pagseguro']['shippingType'] = '3';
            $data['pagseguro']['shippingAddressStreet'] = $sel_cliente[0]["cli_endereco"];
            $data['pagseguro']['shippingAddressNumber'] = $sel_cliente[0]["cli_numero"];
            $data['pagseguro']['shippingAddressComplement'] = " ";
            $data['pagseguro']['shippingAddressDistrict'] = $sel_cliente[0]["cli_bairro"];
            $data['pagseguro']['shippingAddressPostalCode'] = $cep;
            $data['pagseguro']['shippingAddressCity'] = $sel_cliente[0]["cli_cidade"];
            $data['pagseguro']['shippingAddressState'] = strtoupper($sel_cliente[0]["cli_uf"]);
            $data['pagseguro']['shippingAddressCountry'] = 'BRA';
            $data['pagseguro']['codigo_pagseguro'] = '';

            //print_r($data);

            $this->View("checkout", $data);         
            
        }


        public function transaction(){

           // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            $pedido = new App_Model_pedidoModel();
            $code_pedido = $pedido->pedidoCode($dados);

            //$status['notificationCode'] = $dados['code'];

            $PagSeguro = new App_pagseguro();
            $response = $PagSeguro->getStatusByReference($dados['ref']);

            $pedido_status = $pedido->pedidoStatus($response,$dados['ref']);


            print_r($pedido_status);


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
                                   
            $cliente_alt = new App_Model_clienteModel();
            $cliente_lista_alt = $cliente_alt->clienteSelecionar($ident);                       
           
      
            //cria o array para a view
            $var = array('cli_id' => $cliente_lista_alt[0]['cli_id'],      
               'cli_razao' => strtoupper($cliente_alt->removerAcentos($cliente_alt->removerBarra(utf8_decode($cliente_lista_alt[0]['cli_razao'])),"")),
               'cli_fantasia' => strtoupper($cliente_alt->removerAcentos($cliente_alt->removerBarra($cliente_lista_alt[0]['cli_fantasia']),"")),
               'cli_cnpj' => $cliente_alt->removerBarra($cliente_lista_alt[0]['cli_cnpj']),
               'cli_insc' => $cliente_alt->removerBarra($cliente_lista_alt[0]['cli_insc']),
               'cli_contato' => strtoupper($cliente_alt->removerAcentos($cliente_alt->removerBarra($cliente_lista_alt[0]['cli_contato']),"")),
               'cli_email' => $cliente_lista_alt[0]['cli_email'],
               'cli_endereco' => strtoupper($cliente_alt->removerAcentos($cliente_alt->removerBarra($cliente_lista_alt[0]['cli_endereco']),"")),
               'cli_bairro' => strtoupper($cliente_alt->removerAcentos($cliente_alt->removerBarra($cliente_lista_alt[0]['cli_bairro']),"")),
               'cli_cidade' => strtoupper($cliente_alt->removerAcentos($cliente_alt->removerBarra($cliente_lista_alt[0]['cli_cidade']),"")),
               'cli_uf' => $cliente_lista_alt[0]['cli_uf'],
               'cli_cep' => $cliente_lista_alt[0]['cli_cep'],
               'cli_telefone' => $cliente_alt->removerBarra($cliente_lista_alt[0]['cli_telefone']),
               'cli_telefone2' => $cliente_alt->removerBarra($cliente_lista_alt[0]['cli_telefone2']),
               'cli_celular' => $cliente_alt->removerBarra($cliente_lista_alt[0]['cli_celular']),
               'cli_fax' => $cliente_alt->removerBarra($cliente_lista_alt[0]['cli_fax']),
               'cli_frete' => strtoupper($cliente_alt->removerAcentos($cliente_alt->removerBarra($cliente_lista_alt[0]['cli_frete']),"")),
               'cli_obs' => strtoupper($cliente_alt->removerAcentos($cliente_alt->removerBarra($cliente_lista_alt[0]['cli_obs']),"")));

            $input1[] = $var;
                                                            
            //endforeach;
            
            //envia cada registro para o final do array
            array_push($input1, $var);
            //retira um elemento no final do array
            array_pop($input1);
            
            $dados['cliente_lista_alt'] = $input1;            
            
            
            //$json = json_encode($input);
            
            //funcao que chama a view
            
            if($view != NULL):
            
                return $dados;
            
            else:
            
                $this->View("alterarCliente", $dados);
                
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
            
            $model = new App_Model_clienteModel();
            $model_update = $model->clienteAlteracao($dados);
                       
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
            
            //print_r($ident);

            $item = new App_Model_itemModel();
            $model_item = $item->itemDeletar2($ident);
            
            $ped = new App_Model_pedidoModel();
            $model_ped = $ped->pedidoDeletar($ident);
                       
            echo $model_ped;
            
        }       
        
        /***************     PAGINA     *********************/
        
        public function cadastro(){
            
            //funcao que chama a view
            return $this->View("cadastroCliente"); 
           
        }

        public function recebidos(){
         
            global $start;

            $parm = $start->_params;

            if (!empty($parm['p'])):

                $pagination = $parm['p'];

            else:

                $pagination = 1;

            endif;


            $pag = new App_Controller_paginacaoController();


            if ($parm['cat'] == "all"):

                $paginacao = $pag->paginacao("pedidos", NULL,"ped_data DESC", "/backend/pedido/recebidos/cat/all/p", $pagination);

            else:

                $paginacao = $pag->paginacao("pedidos", "ped_status = " . $parm['cat'], "ped_data DESC", "/backend/pedido/recebidos/cat/" . $parm['cat'] . "/p", $pagination);

            endif;

            $PagSeguro = new App_pagseguro();

            $cliente = new App_Model_clienteModel();

            $curso = new App_Model_cursoModel();
           
            if (!empty($paginacao)):

                $item = new App_Model_itemModel();

                foreach ($paginacao['dados'] as $p):

                    $pedido_code = $PagSeguro->getStatusByReference($p['ped_numero']);

                    $ped_status = $PagSeguro->getStatusText($pedido_code); 

                    $ped_item = $item->itemSelecionar2($p['ped_id']);

                    $ped_itemqtd = $item->itemSelecionarQtd($p['ped_id']);

                    $ped_total = $item->itemSelecionarTotal($p['ped_id']);
                    
                    $sel_cliente = $cliente->clienteSelecionar($p['cli_id']);

                    $sel_curso = $curso->cursoSelecionar($ped_item[0]['prod_id']);

                                                                                   
                    //cria o array para a view
                    $var = array('ped_id' => $p['ped_id'],
                                  'ped_numero' => $p['ped_numero'],
                                  'ped_item' => $ped_itemqtd,
                                  'ped_total' => $ped_total[0]['item_total'],
                                  'ped_data' => $p['ped_data'],
                                  'ped_status' => $ped_status,
                                  'cli_nome' => $sel_cliente[0]['cli_nome'],
                                  'cur_titulo' => $sel_curso[0]['D016_Titulo']);
                                  
                   $input[] = $var;

                endforeach;

                $dados['pedido_lista'] = $paginacao;


                if(isset($input)):


                //envia cada registro para o final do array
                array_push($input, $var);
                //retira um elemento no final do array
                array_pop($input);
                
                $dados['pedido_dados'] = $input;

                else:

                $dados['pedido_dados'] = array();

                endif;

        
                $this->View("cadastradoPedido", $dados);

            else:

                //funcao que chama a view

                $this->View("cadastradoPedido");

            endif;
            
        }  

        public function listar(){
         
            $ped = new App_Model_pedidoModel();
            $ped_lista = $ped->pedidoSelecionar3($_SESSION['id_usuario']);

            $PagSeguro = new App_pagseguro();




            if(isset($ped_lista)):   

                $item = new App_Model_itemModel();

                foreach ($ped_lista as $p):  

                    $ped_status = $PagSeguro->getStatusText($p['ped_numero']);

                    $ped_item = $item->itemSelecionarQtd($p['ped_id']);

                    $ped_total = $item->itemSelecionarTotal($p['ped_id']);
                                                                    
                    //cria o array para a view
                    $var = array('ped_id' => $p['ped_id'],
                                  'ped_numero' => $p['ped_numero'],
                                  'ped_item' => $ped_item,
                                  'ped_total' => $ped_total[0]['item_total'],
                                  'ped_data' => $p['ped_data'],
                                  'ped_status' => $pedido_status);
                                  
                   $input[] = $var;
                                                                
                endforeach;
                if(isset($var)):
            
                    //envia cada registro para o final do array
                    array_push($input, $var);
                    //retira um elemento no final do array
                    array_pop($input);
                    
                    $dados['pedido_lista'] = $input;

                    //funcao que chama a view
                    $this->View("viewPedido", $dados);

                else:
                    //funcao que chama a view
                    $this->View("viewPedido");
                
                endif;
            else:

                $dados['pedido_lista'] = array();

                //funcao que chama a view
                $this->View("viewPedido", $dados);

            endif;
            

            
            
            //$this->View("cadastradoCliente", $json);
            
        }  
        
      public function viewped(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");
            
            $ped = new App_Model_pedidoModel();
            $model_ped = $ped->pedidoSelecionar($ident);
              
            $cli = new App_Model_clienteModel();
            $model_cli = $cli->clienteSelecionar($model_ped[0]['ped_cliente']);

            $item = new App_Model_itemModel();
            $model_item = $item->itemSelecionar2($model_ped[0]['ped_id']);

            //print_r($model_item);

            foreach ($model_item as $item):

                $prod = new App_Model_produtoModel();
                $model_prod = $prod->produtoSelecionar($item['item_produto']);

                $tam = new App_Model_tamanhoModel();
                $model_tam = $tam->tamanhoSelecionar3($item['item_tamanho']);

                $loja = new App_Model_lojaModel();
                $model_loja = $loja->lojaSelecionar($item['item_loja']);

                $arr = array(
                              "item_codigo" => $model_prod[0]['prod_cod']."-".$item['item_tamanho'],
                              "item_produto" => $model_prod[0]['prod_nome'],
                              "item_tamanho" => $model_tam[0]['tam_tamanho'],
                              "item_loja" => $model_loja[0]['loj_nome'],
                              "item_qtd" => $item['item_qtd'],
                              "item_valor" => $item['item_valor'],
                              "item_total" => $item['item_total']
                );

                $dt[] = $arr;

            endforeach;

            //envia cada registro para o final do array
            array_push($dt, $arr);
            //retira um elemento no final do array
            array_pop($dt);
            
            $dados['cliente'] = $model_cli;
            $dados['pedido'] = $model_ped;
            $dados['item'] = $dt;

            //funcao que chama a view
            $this->View("viewPed", $dados);

        }

        public function pedview(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");
                    
            $ped = new App_Model_pedidoModel();
            $model_ped = $ped->pedidoSelecionar($ident);
              
            $cli = new App_Model_clienteModel();
            $model_cli = $cli->clienteSelecionar($model_ped[0]['ped_cliente']);

            $item = new App_Model_itemModel();
            $model_item = $item->itemSelecionar2($model_ped[0]['ped_id']);

            //print_r($model_item);

            foreach ($model_item as $item):

                $prod = new App_Model_produtoModel();
                $model_prod = $prod->produtoSelecionar($item['item_produto']);

                $tam = new App_Model_tamanhoModel();
                $model_tam = $tam->tamanhoSelecionar3($item['item_tamanho']);

                $loja = new App_Model_lojaModel();
                $model_loja = $loja->lojaSelecionar($item['item_loja']);

                $arr = array(
                              "item_codigo" => $model_prod[0]['prod_cod']."-".$item['item_tamanho'],
                              "item_produto" => $model_prod[0]['prod_nome'],
                              "item_tamanho" => $model_tam[0]['tam_tamanho'],
                              "item_loja" => $model_loja[0]['loj_nome'],
                              "item_qtd" => $item['item_qtd'],
                              "item_valor" => $item['item_valor'],
                              "item_total" => $item['item_total']
                );

                $dt[] = $arr;

            endforeach;

            //envia cada registro para o final do array
            array_push($dt, $arr);
            //retira um elemento no final do array
            array_pop($dt);
            
            $dados['cliente'] = $model_cli;
            $dados['pedido'] = $model_ped;
            $dados['item'] = $dt;

            //funcao que chama a view
            $this->View("viewPed", $dados);

        }

          public function acao(){
            
            // seta o id do ação pedido
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            //print_r($dados);
            
            $ped = new App_Model_pedidoModel();
            $model_ped = $ped->pedidoAcao($dados);
                       
            echo $model_ped;
            
        }

         public function transpenviar(){
            
            // seta o id do ação pedido
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            //print_r($dados);
            
            $ped = new App_Model_pedidoModel();
            $model_ped = $ped->pedidoSelecionar($dados['id']);
            
            $data['pedido'] = $model_ped;

            $transp = new App_Model_transpModel();
            $model_transp = $transp->listaTransp();

            $data['transportadora'] = $model_transp;

            $this->view('enviarTransp',$data);

            //echo $model_ped;
            
        }
        
    }
