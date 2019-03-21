<?php
    class shopping extends App_Controller{
        
        public function index_action(){
            
            return $this->View("indexCliente");
            
        }
        
        /****************** CRUD ******************************/
        
        public function carrinho(){
		
			//session_start();

            if(isset($_SESSION["produto"])):
                $s_pedido = array_sum($_SESSION["produto"]);
                if($s_pedido > 1):
                    $soma_pedido = "Meu carrinho tem ".$s_pedido." itens.";                
                else:
                    $soma_pedido = "Meu carrinho tem ".$s_pedido." item.";
                endif;    
            else:
                $soma_pedido = "Meu carrinho está vazio.";
                $s_pedido = 0;
            endif;
            echo "<input id='item_produto' type='hidden' value='".$soma_pedido."'/>";
            echo "<input id='item_produto1' type='hidden' value='".$s_pedido."'/>";

            if(isset($_SESSION["produto"])):
                $dados['total'] = 0;
                 

                //VERIFICAR cLIENTE
                $model_cliente = new App_Model_clienteModel();
                $sel_cliente = $model_cliente->clienteSelecionar2($_SESSION['id_cliente']);

                foreach ($_SESSION["produto"] as $nome => $quantidade):
                    
                    //foreach ($_SESSION["produto"] as $nome => $quantidade):

                        if($quantidade > 0):

                            if(substr($nome, 0, 9) == "produto_'"):
                                $base = substr($nome, 9, strlen($nome)-9);
                                
                                $base_exp = explode("-", $base);

                                $base_id = $base_exp[0];

                                $base_tam = $base_exp[1];

                                //$prod_tam = new App_Model_tamanhoModel();
                                //$prod_tam_lista = $prod_tam->tamanhoSelecionar($base_id);

                                //foreach ($prod_tam_lista as $tamanho):


                                    $prod_alt = new App_Model_produtoModel();
                                    $prod_lista = $prod_alt->produtoSelecionar($base_id);

                                    foreach ($prod_lista as $produto):

                                        if($base_tam == 1):
                                            $bs_tam = "P";
                                        elseif($base_tam == 2):
                                            $bs_tam = "M";
                                        elseif($base_tam == 3):
                                            $bs_tam = "G";
                                        endif;
                                        
                                        $prod_vl = $produto['prod_valor'.$base_tam];
                                        
                                        $subtotal = $quantidade * $prod_vl;
                                       
                                        $arr = array("
                                            <tr class='cart_table_item'>
                                                <td>".$produto['prod_cod']."-".$bs_tam."</td>
                                                <td>".$produto['prod_nome']." <br><i>Tamanho: ".$bs_tam."</i></td>
                                                <td align='center'>".$quantidade."</td>
                                                <td align='left'>R$ ".number_format($prod_vl,2,',','.')."</td>
                                                <td align='center'>
                                                    <input type='hidden' value='".$produto['prod_id']."'>
                                                    <input type='hidden' value='".$base_tam."'>
                                                    <i class='btn_inserir fa fa-plus-circle fa-2x'></i>                                                                                                        
                                                </td>
                                                <td align='center'>
                                                    <input type='hidden' value='".$produto['prod_id']."'>
                                                    <input type='hidden' value='".$base_tam."'>
                                                    <i class='btn_retirar fa fa-minus-circle fa-2x'></i>
                                                </td>
                                                <td align='left'><b>R$ ".number_format($subtotal,2,',','.')."</b></td>
                                                <td align='center'>
                                                    <input type='hidden' value='".$quantidade."'>
                                                    <input type='hidden' value='".$produto['prod_id']."'>
                                                    <input type='hidden' value='".$base_tam."'>
                                                    <i class='btn_excluir fa fa-times-circle fa-2x'></i>
                                                </td>
                                            </tr>                                            
                                        ");

                                        $tr[] = $arr;
                                        
                                        //envia cada registro para o final do array
                                        array_push($tr, $arr);
                                        //retira um elemento no final do array
                                        array_pop($tr);
                                        
                                        $dados['item'] = $tr;  

                                        $dados['total'] += $subtotal;


                                        /*BANCO DE DADOS*/

                                                      
                                        $arr1 = array("cliente" => $sel_cliente[0]["cli_id"],
                                                      "usuario" => $_SESSION['id_usuario'],
                                                      "produto" => $produto['prod_id'],
                                                      "tamanho" => $base_tam,
                                                      "loja" => $produto['prod_loja'],
                                                      "qtd" => $quantidade,
                                                      "valor" => $prod_vl,
                                                      "total" => $subtotal);

                                        $db[] = $arr1;
                                        
                                        //envia cada registro para o final do array
                                        array_push($db, $arr1);
                                        //retira um elemento no final do array
                                        array_pop($db);
                                        
                                        $_SESSION['pedido'] = $db; 


                                          
                                    endforeach;                                    

                                //endforeach;    

                            endif;

                        elseif($quantidade == 0):  

                         $_SESSION['pedido'] = 0;                       

                        endif;    
                        
                    //endforeach;
                endforeach; 

                if($dados['total'] == '0'):

                    $dados['item'] = "
                        <tr class='cart_table_item'>
                            <td colspan='8'>Seu carrinho de compras esta vazio! </td>
                        </tr>
                    ";
                endif;

            else:

                $dados['item'] = "
                        <tr class='cart_table_item' align='center' colspan='7'>
                            <td colspan='8'>Seu carrinho de compras esta vazio! </td>
                        </tr>
                ";

            endif;

            //print_r($_SESSION['pedido']);
           
            if(isset($dados["item"])):
                //funcao que chama a view
                return $this->Site("carrinho", $dados);
            else:
                return $this->Site("carrinho");
            endif;

           
        }

        public function add(){
		
			//session_start();
			
			// seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ref = $id->getParamsAjax("ref");

            //echo $ref;

            //$ex = explode(",", $ref);


           

            if(isset($ref)):

                //foreach($ex as $key):
                //echo $key."<br>";

                    //$explode = explode("-", $ref);
                    if(!isset($_SESSION['produto']['produto_'.$ref])):
                        $_SESSION['produto']['produto_'.$ref] = 0;
                        $_SESSION['produto']['produto_'.$ref] += 1;
                    elseif(isset($_SESSION['produto']['produto_'.$ref])):
                        $_SESSION['produto']['produto_'.$ref] += 1;
                    endif; 

                //endforeach;
            endif; 
           
            if(isset($_SESSION["produto"])):
                $s_pedido = array_sum($_SESSION["produto"]);
                if($s_pedido > 1):
                    $soma_pedido = "Meu carrinho tem ".$s_pedido." itens.";                
                else:
                    $soma_pedido = "Meu carrinho tem ".$s_pedido." item.";
                endif;    
            else:
                $soma_pedido = "Meu carrinho está vazio.";
                $s_pedido = 0;
            endif;
            echo $soma_pedido."-".$s_pedido;


        }


        public function cupom(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $code = $id->getParamsAjax("code");


            //VERIFICAR cupom
            $model_cupom = new App_Model_cupomModel();
            $sel_cupom = $model_cupom->cupomVerificar($code);

            print_r($sel_cupom);

        }



        public function inserir(){
		
			//session_start();

            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ref = $id->getParamsAjax("ref");
            //$tam = $id->getParamsAjax("tamanho");

            //echo $_SESSION['produto']['produto_'.$ref];
            //echo $_SESSION['tamanho']['tamanho_'.$tam];

            //$_SESSION['produto']['produto_'.$ref."-".$tam[1]] += 1;
            //$explode = explode("-", $ref);
            if(!isset($_SESSION['produto']['produto_'.$ref])):
                $_SESSION['produto']['produto_'.$ref] = 0;
                $_SESSION['produto']['produto_'.$ref] += 1;
            elseif(isset($_SESSION['produto']['produto_'.$ref])):
                $_SESSION['produto']['produto_'.$ref] += 1;
            endif; 

            self::carrinho();

           /* if(isset($ref)):
                if(!isset($_SESSION['produto']['produto_'.$ref]) AND !isset($_SESSION['tamanho']['tamanho_'.$tam])):
                    $_SESSION['produto']['produto_'.$ref] = 0;
                    $_SESSION['produto']['produto_'.$ref] += 1;
                    $_SESSION['tamanho']['tamanho_'.$tam] = 0;
                    $_SESSION['tamanho']['tamanho_'.$tam] += 1;


                elseif(isset($_SESSION['produto']['produto_'.$ref]) AND !isset($_SESSION['tamanho']['tamanho_'.$tam])):
                    $_SESSION['produto']['produto_'.$ref] += 1;
                    $_SESSION['tamanho']['tamanho_'.$tam] = 0;
                    $_SESSION['tamanho']['tamanho_'.$tam] += 1;
                elseif(isset($_SESSION['produto']['produto_'.$ref]) AND isset($_SESSION['tamanho']['tamanho_'.$tam])):
                    $_SESSION['produto']['produto_'.$ref] += 1;
                    $_SESSION['tamanho']['tamanho_'.$tam] += 1;

                endif; 
            endif; */

            /*if(isset($_SESSION["tamanho"])):
                $soma_pedido = array_sum($_SESSION["produto"]);
                if($soma_pedido > 1):
                    echo $soma_pedido." itens";
                else:
                    echo $soma_pedido." item";
                endif;    
            else:
                echo "0 item";
            endif; */         
        }

        public function retirar(){
		 
			//session_start();

            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ref = $id->getParamsAjax("ref");
            
            $explode = explode("-", $ref);
            if(!isset($_SESSION['produto']['produto_'.$explode[0]."-".$explode[1]])):
                $_SESSION['produto']['produto_'.$explode[0]."-".$explode[1]]--;                
            elseif(isset($_SESSION['produto']['produto_'.$explode[0]."-".$explode[1]])):
                $_SESSION['produto']['produto_'.$explode[0]."-".$explode[1]]--;
            endif; 

            self::carrinho();

            /*if(isset($ref)):
                if(!isset($_SESSION['produto']['produto_'.$ref]) AND !isset($_SESSION['tamanho']['tamanho_'.$tam])):
                    $_SESSION['produto']['produto_'.$ref] = 0;
                    $_SESSION['produto']['produto_'.$ref] --;
                    $_SESSION['tamanho']['tamanho_'.$tam] = 0;
                    $_SESSION['tamanho']['tamanho_'.$tam] --;
                elseif(isset($_SESSION['produto']['produto_'.$ref]) AND !isset($_SESSION['tamanho']['tamanho_'.$tam])):
                    $_SESSION['produto']['produto_'.$ref] --;
                    $_SESSION['tamanho']['tamanho_'.$tam] = 0;
                    $_SESSION['tamanho']['tamanho_'.$tam] --;
                elseif(isset($_SESSION['produto']['produto_'.$ref]) AND isset($_SESSION['tamanho']['tamanho_'.$tam])):
                    $_SESSION['produto']['produto_'.$ref] --;
                    $_SESSION['tamanho']['tamanho_'.$tam] --;
                endif; 
            endif;  

            if(isset($_SESSION["produto"])):
                $soma_pedido = array_sum($_SESSION["produto"]);
                if($soma_pedido > 1):
                    echo $soma_pedido." itens";
                else:
                    echo $soma_pedido." item";
                endif;    
            else:
                echo "0 item";
            endif; */         
        }

        public function excluir(){
		
			//session_start();

            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ref = $id->getParamsAjax("ref");


            // $explode = explode("-", $ref);
            // if(!isset($_SESSION['produto']['produto_'.$explode[0]."-".$explode[1]])):
            //     $_SESSION['produto']['produto_'.$explode[0]."-".$explode[1]] = 0;                
            // elseif(isset($_SESSION['produto']['produto_'.$explode[0]."-".$explode[1]])):
            //     $_SESSION['produto']['produto_'.$explode[0]."-".$explode[1]] = 0;
            // endif; 

            if(!isset($_SESSION['produto']['produto_'.$ref])):
                $_SESSION['produto']['produto_'.$ref] = 0;
            elseif(isset($_SESSION['produto']['produto_'.$ref])):
                $_SESSION['produto']['produto_'.$ref] = 0;
            endif; 

            $key = array_search($_SESSION['produto']['produto_'.$ref], $_SESSION['produto']);
            if($key!==false):
                unset($_SESSION['produto'][$key]);
            endif;

            if(empty($_SESSION['produto'])):
                unset($_SESSION['produto']);
            endif;

            $key = array_search($_SESSION['pedido'][$ref], $_SESSION['pedido']);
            if($key!==false):
                unset($_SESSION['pedido'][$key]);
            endif;

            if(empty($_SESSION['pedido'])):
                unset($_SESSION['pedido']);
            endif;



            //print_r($_SESSION['produto']);

            //self::carrinho();

           

            /*if(isset($ref)):
                if(!isset($_SESSION['produto']['produto_'.$ref])):
                    $_SESSION['produto']['produto_'.$ref] = 0;
                    $carrinho::carrinho;
                else:
                    $_SESSION['produto']['produto_'.$ref] = 0;
                    $carrinho::carrinho;
                endif; 
            endif; 

            if(isset($_SESSION["tamanho"])):
                $soma_pedido = array_sum($_SESSION["tamanho"]);
                if($soma_pedido > 1):
                    echo $soma_pedido;
                else:
                    echo $soma_pedido;
                endif;    
            else:
                echo "0";
            endif;*/          
        }

        
        public function acesso(){
            
            //funcao que chama a view
            return $this->View("login"); 
           
        }
        
               
    }
