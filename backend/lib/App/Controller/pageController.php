<?php

class page extends App_Controller
{

    public $_sistema;



    public function index_action()
    {
        $dados['dados'] = array();
        //CHAMA INDEX BACKEND
        parent::Site("index", $dados);
    }

    public function nav()
    {
        $menu = new App_Model_menuModel();
        $config = unserialize(CONFIG_DB);
        /********************************************/
        //CONFIGURAÇÃO DO SITE
        $dados['config'] = $config;
        //NAV
        $menu_lista_cad = $menu->listaMenu3();
        $arr = array();
        $arr2 = array();
        foreach ($menu_lista_cad as $value):
            $count = $menu->countMenu($value['D008_Uid']);
            if (count($count) != 0):
                $arr += array($value['D008_Uid'] => count($count));
            endif;
            foreach ($count as $value2):
                $countsub1 = $menu->countSub1($value['D008_Uid'], $value2['D008_Uid']);
                if (count($countsub1) != 0):
                    $arr2 += array($value2['D008_Uid'] => count($countsub1));
                endif;
            endforeach;
        endforeach;
        $sub_master = $menu->listaMaster();
        $sub_1 = $menu->listaSub1();
        $sub_2 = $menu->listaSub2();
        $sub_3 = $menu->listaSub3();
        $sub_4 = $menu->listaSub4();
        $sub_5 = $menu->listaSub5();
        //print_r($sub_master);
        if (!empty($menu_lista_cad)):
            $dados['menu_count'] = $arr;
            $dados['sub1_count'] = $arr2;
            $dados['menu_lista'] = $menu_lista_cad;
            $dados['sub_master'] = $sub_master;
            $dados['sub_1'] = $sub_1;
            $dados['sub_2'] = $sub_2;
            $dados['sub_3'] = $sub_3;
            $dados['sub_4'] = $sub_4;
            $dados['sub_5'] = $sub_5;
            //Conteudo UNIDADE
            $unidade = new App_Model_unidadeModel();
            $sel_unidade = $unidade->listaUnidade();
            if (!empty($sel_unidade)):
                $dados['unidade'] = $sel_unidade;
            else:
                $dados['unidade'] = array();
            endif;

            //funcao que chama a view
            parent::Site("nav", $dados);
        endif;
    }
    public function footer()
    {
        $config = unserialize(CONFIG_DB);
        /********************************************/
        //CONFIGURAÇÃO DO SITE
        $dados['config'] = $config;
        //NAV
        $menu = new App_Model_menuModel();
        $menu_lista_cad = $menu->listaMenu3();
        $arr = array();
        $arr2 = array();
        foreach ($menu_lista_cad as $value):
            $count = $menu->countMenu($value['D008_Uid']);
            if (count($count) != 0):
                $arr += array($value['D008_Uid'] => count($count));
            endif;
            foreach ($count as $value2):
                $countsub1 = $menu->countSub1($value['D008_Uid'], $value2['D008_Uid']);
                if (count($countsub1) != 0):
                    $arr2 += array($value2['D008_Uid'] => count($countsub1));
                endif;
            endforeach;
        endforeach;
        $sub_master = $menu->listaMaster();
        $sub_1 = $menu->listaSub1();
        $sub_2 = $menu->listaSub2();
        $sub_3 = $menu->listaSub3();
        $sub_4 = $menu->listaSub4();
        $sub_5 = $menu->listaSub5();
        //print_r($sub_master);
        if (!empty($menu_lista_cad)):
            $dados['menu_count'] = $arr;
            $dados['sub1_count'] = $arr2;
            $dados['menu_lista'] = $menu_lista_cad;
            $dados['sub_master'] = $sub_master;
            $dados['sub_1'] = $sub_1;
            $dados['sub_2'] = $sub_2;
            $dados['sub_3'] = $sub_3;
            $dados['sub_4'] = $sub_4;
            $dados['sub_5'] = $sub_5;
            //funcao que chama a view
        endif;
        //Conteudo UNIDADE
        $unidade = new App_Model_unidadeModel();
        $sel_unidade = $unidade->listaUnidade();
        if (!empty($sel_unidade)):
            $dados['unidade'] = $sel_unidade;
        else:
            $dados['unidade'] = array();
        endif;
        //Conteudo curso
        $curso = new App_Model_cursoModel();
        $sel_curso = $curso->listaEnd();
        if (!empty($sel_unidade)):
            $dados['curso_destaque'] = $sel_curso;
        else:
            $dados['curso_destaque'] = array();
        endif;
        parent::Site("footer", $dados);
    }

    public function formatar($string, $tipo = ""){
    
        $string = preg_replace('/[^A-Za-z0-9_]/', "", $string);
    
        if (!$tipo){        
            switch (strlen($string)){        
                case 10:    $tipo = 'fone';     break;        
                case 8:     $tipo = 'cep';      break;        
                case 11:    $tipo = 'cpf';      break;        
                case 14:    $tipo = 'cnpj';     break;        
            }        
        }
    
        switch ($tipo){        
            case 'fone':        
                $string = '(' . substr($string, 0, 2) . ') ' . substr($string, 2, 4) . '-' . substr($string, 6);        
            break;        
            case 'cep':        
                $string = substr($string, 0, 5) . '-' . substr($string, 5, 3);        
            break;        
            case 'cpf':        
                $string = substr($string, 0, 3) . '.' . substr($string, 3, 3) . '.' . substr($string, 6, 3) . '-' . substr($string, 9, 2);        
            break;        
            case 'cnpj':        
                $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) . '.' . substr($string, 5, 3) . '/' . substr($string, 8, 4) . '-' . substr($string, 12, 2);        
            break;        
            case 'rg':        
                $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) . '.' . substr($string, 5, 3);        
            break;        
        }
    
        return $string;        
    }

    public function usuario(){
        if( strtolower($element_custom["pag_tipo_de_acao"]) == "recuperar" ):

            parent::Site("recuperarPassword",$dados);

        endif;

        if( strtolower($element_custom["pag_tipo_de_acao"]) == "redefinir" ):

          if(isset($_GET["token"])):
            if(!empty($_GET["token"])):

              $model = new App_Model_clienteModel();
              $cliente = $model->clienteSelecionarToken($_GET["token"]);
              $dados["cliente"] = $cliente;

              // Cria um objeto com a data atual UTC
              $dt = new DateTime(gmdate('Y/m/d H:i:s'), new DateTimeZone('UTC'));
              // Altera a timezone
              $dt->setTimezone(new DateTimeZone('Europe/Lisbon'));

              if(!empty($cliente)):

                $token = date('Y-m-d H:i:s', $cliente[0]["cli_time"]);

                $dt1 = new DateTime( $token );
                $dt2 = $dt;
                $intervalo = $dt1->diff( $dt2 );

                if($intervalo->d >= 1 && $intervalo->invert == 0):
                  $dados["session"] = false;
                  $dados["token"] = true;
                elseif($intervalo->d < 1 && $intervalo->invert == 0):
                  $dados["session"] = true;
                endif;
              else:
                $dados["session"] = false;
                $dados["token"] = false;
              endif;
            else:
              $dados["session"] = false;
              $dados["token"] = false;
            endif;
          else:
            $dados["session"] = false;
            $dados["token"] = false;
          endif;
            parent::Site("redefinirPassword",$dados);
        endif;
    }

    public function clienteDados(){
          self::nav();
          //VERIFICAR cLIENTE
          $model_cliente = new App_Model_clienteModel();
          $sel_cliente = $model_cliente->clienteSelecionar2($_SESSION['id_cliente']);
          foreach ($sel_cliente as $cliente): 
              $var = array(
                  'cli_id' => $cliente['cli_id'],
                  'cli_nome' => $cliente['cli_nome'],
                  'cli_cpf' => self::formatar($cliente['cli_cpf'],"cpf"),
                  'cli_email' => $cliente['cli_email'],
                  'cli_endereco' => $cliente['cli_endereco'],
                  'cli_numero' => $cliente['cli_numero'],
                  'cli_bairro' => $cliente['cli_bairro'],
                  'cli_cidade' => $cliente['cli_cidade'],
                  'cli_uf' => $cliente['cli_uf'],
                  'cli_cep' => $cliente['cli_cep'],
                  'cli_telefone' => self::formatar($cliente['cli_telefone'],"fone"),
                  'cli_telefone2' => self::formatar($cliente['cli_telefone2'],"fone"),
                  'cli_celular' => self::formatar($cliente['cli_celular'],"fone")
              );
                    
              $input[] = $var;
                                                          
          endforeach;
          
          //envia cada registro para o final do array
          array_push($input, $var);
          //retira um elemento no final do array
          array_pop($input);
          $dados["cliente_lista"] = $input;
          parent::Site("viewCliente",$dados);
          self::footer();
    }
   
    public function clienteCadastrar(){
      self::nav();
      global $start;
      $parm = $start->_params;
      parent::Site("cadastroCliente");
      self::footer();
    }
    public function alterarCliente(){
     
        //VERIFICAR cLIENTE
        $model_cliente = new App_Model_clienteModel();
        $sel_cliente = $model_cliente->clienteSelecionar2($_SESSION['id_cliente']);
        foreach ($sel_cliente as $cliente): 
            $var = array(
                'cli_id' => $cliente['cli_id'],
                'cli_nome' => $cliente['cli_nome'],
                'cli_cpf' => self::formatar($cliente['cli_cpf'],"cpf"),
                'cli_email' => $cliente['cli_email'],
                'cli_endereco' => $cliente['cli_endereco'],
                'cli_numero' => $cliente['cli_numero'],
                'cli_bairro' => $cliente['cli_bairro'],
                'cli_cidade' => $cliente['cli_cidade'],
                'cli_uf' => $cliente['cli_uf'],
                'cli_cep' => $cliente['cli_cep'],
                'cli_telefone' => $cliente['cli_telefone'],
                'cli_telefone2' => $cliente['cli_telefone2'],
                'cli_celular' => $cliente['cli_celular']                    
            );
                  
            $input[] = $var;
                                                        
        endforeach;
        
        //envia cada registro para o final do array
        array_push($input, $var);
        //retira um elemento no final do array
        array_pop($input);
                 
        $dados['cliente_lista'] = $input;
        
        //$json = json_encode($input);
        
        //funcao que chama a view
        parent::Site("viewClienteAlt", $dados);
        
        //$this->View("cadastradoCliente", $json);
        
    }  
    public function pedidoListar(){
      self::nav();
      $cliente = new App_Model_clienteModel();
      $sel_cliente = $cliente->clienteSelecionar2($_SESSION['id_cliente']);
      //print_r($sel_cliente);
      $ped = new App_Model_pedidoModel();
      $ped_lista = $ped->pedidoSelecionar3($sel_cliente[0]['cli_id']);
      $PagSeguro = new App_pagseguro();
      $item = new App_Model_itemModel();
      $curso = new App_Model_cursoModel();
      if(isset($ped_lista)):                      
          foreach ($ped_lista as $p):  
            $ped_item = $item->itemSelecionar2($p['ped_id']);
            //print_r($ped_item);
            $total_item = 0;
            foreach($ped_item as $item_list):
              $total_item += $item_list["item_valor"];
            endforeach;
            $sel_curso = $curso->cursoSelecionar($ped_item[0]['prod_id']);
            $pedido_code = $PagSeguro->getStatusByReference($p['ped_numero']);
            $pedido_status = $PagSeguro->getStatusText($pedido_code);               
                                                          
            //cria o array para a view
            $var = array('ped_id' => $p['ped_id'],
                          'ped_numero' => $p['ped_numero'],
                          'ped_item' => count($ped_item),
                          'ped_total' => $total_item,
                          'ped_data' => $p['ped_data'],
                          'ped_status' => $pedido_status,
                          'cli_nome' => $sel_cliente[0]['cli_nome']);
                            
             $input[] = $var;
                                                          
          endforeach;
          if(isset($var)):
      
              //envia cada registro para o final do array
              array_push($input, $var);
              //retira um elemento no final do array
              array_pop($input);
              
              $dados['pedido_lista'] = $input;
              //funcao que chama a view
              parent::Site("viewPedido", $dados);
          else:
              //funcao que chama a view
              parent::Site("viewPedido");
          
          endif;
      else:
          $dados['pedido_lista'] = array();
          //funcao que chama a view
          parent::Site("viewPedido", $dados);
      endif;
      self::footer();
    }
    public function meuscursos(){
      self::nav();
      $cliente = new App_Model_clienteModel();
      $sel_cliente = $cliente->clienteSelecionar2($_SESSION['id_cliente']);
      //print_r($sel_cliente);
      $ped = new App_Model_pedidoModel();
      $ped_lista = $ped->pedidoSelecionar3($sel_cliente[0]['cli_id']);
      $PagSeguro = new App_pagseguro();
      $item = new App_Model_itemModel();
      $disciplina = new App_Model_cursoModel();
      $curso = new App_Model_cursocatModel();
      if(isset($ped_lista)):                      
          foreach ($ped_lista as $p):  
            $ped_item = $item->itemSelecionar2($p['ped_id']);
            $pedido_code = $PagSeguro->getStatusByReference($p['ped_numero']);
            $pedido_status = $PagSeguro->getStatusText($pedido_code);   
            
            //relacionando os cursos pelos os itens do pedido
            $total_item = 0;
            foreach($ped_item as $item_list):
              $total_item += $item_list["item_valor"];
              //verifica se o curso foi pago
              if($pedido_status = "Pago"):
                //echo "curso liberado.";
                //seleciona a disciplina
                $sel_disciplina = $disciplina->cursoSelecionar($item_list['prod_id']);
                
                //verificar se é um pacote
                if( $sel_disciplina[0]['D016_Pacote'] == 1):
                  //selecionar curso
                  $sel_curso  = $curso->cursoSelecionar($sel_disciplina[0]['D016_Categoria']);
                  //selecionar disciplinas
                  $todas_disciplinas = $disciplina->cursoPacote($sel_disciplina[0]['D016_Categoria'],0);
                  foreach($todas_disciplinas as $list):
                  $arr_cursos = array( 
                      "titulo" => $list['D016_Titulo'],
                      "chave" => $list['D016_Chave'],
                      "pedido" => $p['ped_numero']
                  );
                  $arr_pacote[] = $arr_cursos;
                  endforeach;
                  array_push($arr_pacote, $arr_cursos);
                  //retira um elemento no final do array
                  array_pop($arr_pacote);
                  $arr_pacote_full = array( 
                    $sel_disciplina[0]['D016_Categoria'] => array(
                      "curso" => $sel_curso[0]["D016_Nome"],
                      "disciplina" => $arr_pacote                          
                      )
                  );
                  $dados['listar_curso'] = $arr_pacote_full;
                else: // selecionar todas disciplinas que foi page que seja pacote
                  //selecionar curso
                  $sel_curso  = $curso->cursoSelecionar($sel_disciplina[0]['D016_Categoria']);
                  //selecionar disciplinas
                  $todas_disciplinas = $disciplina->cursoSelecionar2($sel_disciplina[0]['D016_Uid'],0);
                  foreach($todas_disciplinas as $list):
                  $arr_cursos = array( 
                      "titulo" => $list['D016_Titulo'],
                      "chave" => $list['D016_Chave'],
                      "pedido" => $p['ped_numero']
                  );
                  $arr_pacote[] = $arr_cursos;
                  endforeach;
                  array_push($arr_pacote, $arr_cursos);
                  //retira um elemento no final do array
                  array_pop($arr_pacote);
                  $arr_pacote_full = array( 
                    $sel_disciplina[0]['D016_Categoria'] => array(
                      "curso" => $sel_curso[0]["D016_Nome"],
                      "disciplina" => $arr_pacote                          
                      )
                  );
                  $dados['listar_curso'] = $arr_pacote_full;
                endif; //end verificar pacotes
              else:
                echo "aguardando a confirmação de pagamento.";
              endif;
            endforeach;
                                                          
          endforeach;
       
              //funcao que chama a view
              parent::Site("viewMeusCursos", $dados);
        
      else:
          $dados['listar_curso'] = array();
          //funcao que chama a view
          parent::Site("viewMeusCursos", $dados);
      endif;
      self::footer();
    }
    public function minhasaulas(){
      self::nav();
      global $start;
      $parm = $start->_params;
      $cliente = new App_Model_clienteModel();
      $sel_cliente = $cliente->clienteSelecionar2($_SESSION['id_cliente']);
      //print_r($sel_cliente);
      $ped = new App_Model_pedidoModel();
      $ped_lista = $ped->pedidoSelecionar2($parm['token']);
      $PagSeguro = new App_pagseguro();
      $item = new App_Model_itemModel();
      $disciplina = new App_Model_cursoModel();
      $curso = new App_Model_cursocatModel();
      $aula = new App_Model_aulaModel();
      $model_fotos = new App_Model_aulafotosModel();
      if(isset($ped_lista)):            
            $pedido_code = $PagSeguro->getStatusByReference($parm['token']);
            $pedido_status = $PagSeguro->getStatusText($pedido_code);
            //relacionando os cursos pelos os itens do pedido
            //verifica se o curso foi pago
            if($pedido_status = "Pago"):               
              //seleciona a disciplina
              $sel_disciplina = $disciplina->cursoChave($parm['ref']);
              $dados["disciplina"] = $sel_disciplina;  
              //selecionar curso
              $sel_curso = $curso->cursoSelecionar($sel_disciplina[0]['D016_Categoria']);
              $dados["curso"] = $sel_curso;  
              $sel_aula = $aula->aulaSelecionarcat($sel_disciplina[0]['D016_Uid']);
              if( !empty( $sel_aula ) ):
              
                foreach($sel_aula as $aulas):
                  $dt_exp = explode(" ", $aulas['aula_data']);
                  $sel_arquivos = $model_fotos->aulaSelecionarFotos($aulas["aula_id"]);                
                  if(!empty( $sel_arquivos )):
                    $aula_file = $sel_arquivos[0]["arq_file"];
                  else:
                    $aula_file = "";
                  endif;
                  $arr_aulas = array( 
                      "titulo" => $aulas['aula_titulo'],
                      "chave" => $aulas['aula_chave'],
                      "chamada" => $aulas['aula_chamada'],
                      "descricao" => $aulas['aula_descricao'],
                      "video" => $aulas['aula_video'],
                      "tpvideo" => $aulas['aula_tipovideo'],
                      "status" => $aulas['aula_status'],
                      "tpstatus" => $aulas['aula_tipostatus'],
                      "ordem" => $aulas['aula_ordem'],
                      "disciplina" => $aulas['aula_disciplina'],
                      "data" => implode("/", array_reverse(explode("-", $dt_exp[0]))),
                      "arquivo" => $aula_file
                  );
                  $pac_aula[] = $arr_aulas;
                endforeach;
                array_push($pac_aula, $arr_aulas);
                array_pop($pac_aula);
                $dados["aulas"] = $pac_aula; 
              else:
                $dados["aulas"] = array(); 
              endif; 
              
            else:
              echo "aguardando a confirmação de pagamento.";
            endif;
          //funcao que chama a view
          parent::Site("viewMinhasAulas", $dados);
      else:
          $dados['disciplina'] = array();
          //funcao que chama a view
          parent::Site("viewMinhasAulas", $dados);
      endif;
      self::footer();
    }
    // public function carrinho(){
    //   self::nav();
    //   global $start;
    //   $parm = $start->_params;
    //   $carrinho = new App_Controller_shoppingController();
    //   echo $carrinho->carrinho();
    //   self::footer();
    // }
    public function images(){
      global $start;
      $parm = $start->_params;
    }
    public function carrinho(){

      
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
        
        echo "<input id='item_curso' type='hidden' value='".$soma_pedido."'/>";
        echo "<input id='item_curso1' type='hidden' value='".$s_pedido."'/>";
        if(isset($_SESSION["produto"])):
            $dados['total'] = 0;
             
            //VERIFICAR cLIENTE
            $model_cliente = new App_Model_clienteModel();
            $curso_alt = new App_Model_cursoModel();
            
            $sel_cliente = $model_cliente->clienteSelecionar2($_SESSION['id_cliente']);
            
            //VERIFICAR Cupom
            $model_cupom = new App_Model_cupomModel();
            
            foreach ($_SESSION["produto"] as $nome => $quantidade):
                
                //foreach ($_SESSION["produto"] as $nome => $quantidade):
                    if($quantidade > 0):
                        //if(substr($nome, 0, 9) == "produto_'"):
                            //$base_id = substr($nome, 9, strlen($nome)-9);
                            $base_exp = explode("_", $nome);
                            $base_id = $base_exp[1];
                            
                            //$base_exp = explode("-", $base);
                            //$base_id = $base_exp[0];
                            // $base_tam = $base_exp[1];
                            //$prod_tam = new App_Model_tamanhoModel();
                            //$prod_tam_lista = $prod_tam->tamanhoSelecionar($base_id);
                            //foreach ($prod_tam_lista as $tamanho):
                            if(!empty( $_SESSION['cupom-valido'] )):
                            $sel_cupom = $model_cupom->cupomVerificar($_SESSION['cupom-valido']);
                            else:
                            $sel_cupom = "";
                            endif;
                                
                                $curso_lista = $curso_alt->cursoSelecionar($base_id);  
                                foreach ($curso_lista as $curso):
                                    // if($base_tam == 1):
                                    //     $bs_tam = "P";
                                    // elseif($base_tam == 2):
                                    //     $bs_tam = "M";
                                    // elseif($base_tam == 3):
                                    //     $bs_tam = "G";
                                    // endif;
                                    if($curso['D016_Valor2'] != "0.00"):
                                      $curso_vl = $curso['D016_Valor2'];
                                    else:
                                      $curso_vl = $curso['D016_Valor'];
                                    endif;
                                    /** PAREI AQUI **/
                                    if(!empty( $sel_cupom )):
                                      if( $sel_cupom[0]['cup_curso'] == $base_id):
                                      $Porc = ( $sel_cupom[0]["cup_valor"] / 100 ) * $curso['D016_Valor'];
                                      $vl = $curso['D016_Valor'] - $Porc;
                                      else:
                                      $vl = $curso_vl;
                                      endif;
                                    else:
                                      $vl = $curso_vl;
                                    endif;
                                    $sub_sem_desconto = $quantidade * $curso['D016_Valor'];                                                                                
                                    $subtotal = $quantidade * $vl;
                                    $porc_desconto = ( $vl * 100 ) / $curso['D016_Valor'];
                                    $porc_desconto = 100 - $porc_desconto;
                                   
                                    $arr = array("
                                        <tr class='cart_table_item'>
                                            <td class='hidden-xs' >".$curso['D016_Codigo']."</td>
                                            <td>".$curso['D016_Titulo']."</td>
                                            <td align='center'>".$quantidade." </td>
                                            <td align='left'>R$ ".number_format($curso['D016_Valor'],2,',','.')."</td>
                                            <!--td align='center'>
                                                <input type='hidden' value='".$curso['D016_Uid']."'> <i class='btn_inserir fa fa-plus-circle fa-2x'></i>                            
                                            </td-->
                                            <td align='center'>
                                                ".$porc_desconto."%
                                            </td>
                                            <td align='left' class='hidden-xs' ><b>R$ ".number_format($subtotal,2,',','.')."</b></td>
                                            <td align='center'> 
                                                <input type='hidden' value='".$curso['D016_Uid']."'>
                                                <i class='btn_excluir fa fa-times-circle fa-2x'></i>
                                                <input type='hidden' value='".$quantidade."'>
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
                                    $dados['total_sd'] += $sub_sem_desconto;
                                    /*BANCO DE DADOS*/
                                                  
                                    $arr1 = array("cliente" => $sel_cliente[0]["cli_id"],
                                                  "usuario" => $_SESSION['id_cliente'],
                                                  "produto" => $curso['D016_Uid'],
                                                  "qtd" => $quantidade,
                                                  "valor" => $vl,
                                                  "total" => $subtotal);
                                    $db[] = $arr1;
                                    
                                    //envia cada registro para o final do array
                                    array_push($db, $arr1);
                                    //retira um elemento no final do array
                                    array_pop($db);
                                    
                                    $_SESSION['pedido'] = $db; 
                                      
                                endforeach;                                    
                            //endforeach;    
                        //endif;
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
        self::nav();
       
        if(isset($dados["item"])):
            //funcao que chama a view
            return parent::Site("carrinho", $dados);
        else:
            return parent::Site("carrinho");
        endif;
        self::footer();
       
    }
    
    public function logout(){
      $logar = new App_logar();
      return $logar->logout_cliente();
    }



    public function noticias()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $exp_url = explode("/", $_GET["url"]);

        $noticiacat = new App_Model_noticiacatModel();
        $dbnoticia = new App_Model_noticiaModel();
        $config = unserialize(CONFIG_DB);

        $action = "";
        $categoria = "";
        $slug = "";

        if(isset($exp_url[4]))://identifica o slug da noticia PAGE SINGLE
          $slug = $exp_url[4];

          $rs = $dbnoticia->noticiaChave($slug);
          $dados['noticia'] = $rs;
          $sel_noticia = $dbnoticia->listaEnd();
          foreach ($sel_noticia as $nt):  
            $ident_categoria = $noticiacat->noticiaSelecionar($nt['D007_Categoria']);          
            //$destaque .= $foto["D015_Uid"]."-".$rs1.",";
            $arr = Array("D007_Uid" => $nt["D007_Uid"],
                "D007_Titulo" => $nt["D007_Titulo"],
                "D007_Chave" => $nt["D007_Chave"],
                "D007_Chamada" => $nt["D007_Chamada"],
                "D007_Categoria" => $ident_categoria[0]["D007_Chave"],
                "D007_Foto" => $nt["D007_Foto"],
                "D007_Data" => $nt["D007_Data"]
            );
            $new[] = $arr;
          endforeach;
          array_push($new, $arr);
          array_pop($new);
          $dados['last_noticias'] = $new;
          $cat_noticia = $noticiacat->listaNoticia();
          $dados['cat_noticias'] = $cat_noticia;          
          $dados['config'] = $config;
          //CHAMA INDEX BACKEND
          parent::Site("noticia", $dados);

        else:

          if(isset($exp_url[2]))://identifica qual page
            $action = $exp_url[2];
          endif;
          if(isset($exp_url[3]))://verifica se tem categoria
            $categoria = $exp_url[3];
            $sel_categoria = $noticiacat->noticiaSelecionarChave($categoria);
            $noticia = $dbnoticia->listNoticia($sel_categoria[0]["D007_Uid"]);
            $count_noticia = $dbnoticia->countNoticia($sel_categoria[0]["D007_Uid"]);
            foreach ($noticia as $nt):  
              $ident_categoria = $noticiacat->noticiaSelecionar($nt['D007_Categoria']);          
              //$destaque .= $foto["D015_Uid"]."-".$rs1.",";
              $arr = Array("D007_Uid" => $nt["D007_Uid"],
                  "D007_Titulo" => $nt["D007_Titulo"],
                  "D007_Chave" => $nt["D007_Chave"],
                  "D007_Chamada" => $nt["D007_Chamada"],
                  "D007_Categoria" => $ident_categoria[0]["D007_Chave"],
                  "D007_Foto" => $nt["D007_Foto"],
                  "D007_Data" => $nt["D007_Data"]
              );
              $new[] = $arr;
            endforeach;
            array_push($new, $arr);
            array_pop($new);
            $dados['noticias'] = $new;
            $dados['count_noticias'] = $count_noticia;
          else:
            $noticia = $dbnoticia->listNoticia(null);
            $count_noticia = $dbnoticia->countNoticia(null);
            foreach ($noticia as $nt):  
              $ident_categoria = $noticiacat->noticiaSelecionar($nt['D007_Categoria']);          
              //$destaque .= $foto["D015_Uid"]."-".$rs1.",";
              $arr = Array("D007_Uid" => $nt["D007_Uid"],
                  "D007_Titulo" => $nt["D007_Titulo"],
                  "D007_Chave" => $nt["D007_Chave"],
                  "D007_Chamada" => $nt["D007_Chamada"],
                  "D007_Categoria" => $ident_categoria[0]["D007_Chave"],
                  "D007_Foto" => $nt["D007_Foto"],
                  "D007_Data" => $nt["D007_Data"]
              );
              $new[] = $arr;
            endforeach;
            array_push($new, $arr);
            array_pop($new);
            $dados['noticias'] = $new;
            $dados['count_noticias'] = $count_noticia;
          endif;
                     
          $sel_noticia = $dbnoticia->listaEnd();
          foreach ($sel_noticia as $nt2):  
            $ident_categoria2 = $noticiacat->noticiaSelecionar($nt2['D007_Categoria']);          
            //$destaque .= $foto["D015_Uid"]."-".$rs1.",";
            $arr2 = Array("D007_Uid" => $nt2["D007_Uid"],
                "D007_Titulo" => $nt2["D007_Titulo"],
                "D007_Chave" => $nt2["D007_Chave"],
                "D007_Chamada" => $nt2["D007_Chamada"],
                "D007_Categoria" => $ident_categoria2[0]["D007_Chave"],
                "D007_Foto" => $nt2["D007_Foto"],
                "D007_Data" => $nt2["D007_Data"]
            );
            $new2[] = $arr2;
          endforeach;
          array_push($new2, $arr2);
          array_pop($new2);
          $dados['last_noticias'] = $new2;
          $cat_noticia = $noticiacat->listaNoticia();
          $dados['cat_noticias'] = $cat_noticia;
          $dados['config'] = $config;
          //CHAMA INDEX BACKEND
          parent::Site("noticias", $dados);

        endif;
        self::footer();
    }

    public function noticia_load(){

      $id = new App_System();
      $id->_urlAjax = $_POST['url'];
      $id->setExplodeAjax();
      $id->setControllerAjax();
      $id->setActionAjax();
      $id->setParamsAjax();
      $dados = $id->getParamsAjax();

      $exp_url = explode("|", $dados["list"]);

      $noticiacat = new App_Model_noticiacatModel();
      $dbnoticia = new App_Model_noticiaModel();

      $action = "";
      $categoria = "";
      $slug = "";

      if(isset($exp_url[2]))://identifica qual page
        $action = $exp_url[2];
      endif;
      if(isset($exp_url[3]))://verifica se tem categoria
        
        $categoria = $exp_url[3];        
        $sel_categoria = $noticiacat->noticiaSelecionarChave($categoria);
        $noticia = $dbnoticia->listNoticia($sel_categoria[0]["D007_Uid"]);
        $count_noticia = $dbnoticia->countNoticia($sel_categoria[0]["D007_Uid"]);
        foreach ($noticia as $nt):  
          $ident_categoria = $noticiacat->noticiaSelecionar($nt['D007_Categoria']);          
          //$destaque .= $foto["D015_Uid"]."-".$rs1.",";
          $arr = Array("D007_Uid" => $nt["D007_Uid"],
              "D007_Titulo" => $nt["D007_Titulo"],
              "D007_Chave" => $nt["D007_Chave"],
              "D007_Chamada" => $nt["D007_Chamada"],
              "D007_Categoria" => $ident_categoria[0]["D007_Chave"],
              "D007_Foto" => $nt["D007_Foto"],
              "D007_Data" => $nt["D007_Data"]
          );
          $new[] = $arr;
        endforeach;
        array_push($new, $arr);
        array_pop($new);
        $dados['noticias'] = $new;
        $dados['count_noticias'] = $count_noticia;
        $cat = $sel_categoria[0]["D007_Uid"];
      else:
        $noticia = $dbnoticia->listNoticia(null);
        $count_noticia = $dbnoticia->countNoticia(null);
        foreach ($noticia as $nt):  
          $ident_categoria = $noticiacat->noticiaSelecionar($nt['D007_Categoria']);          
          //$destaque .= $foto["D015_Uid"]."-".$rs1.",";
          $arr = Array("D007_Uid" => $nt["D007_Uid"],
              "D007_Titulo" => $nt["D007_Titulo"],
              "D007_Chave" => $nt["D007_Chave"],
              "D007_Chamada" => $nt["D007_Chamada"],
              "D007_Categoria" => $ident_categoria[0]["D007_Chave"],
              "D007_Foto" => $nt["D007_Foto"],
              "D007_Data" => $nt["D007_Data"]
          );
          $new[] = $arr;
        endforeach;
        array_push($new, $arr);
        array_pop($new);
        $dados['noticias'] = $new;
        $dados['count_noticias'] = $count_noticia;
        $cat = null;
      endif;
      if(isset($exp_url[4]))://identifica o slug da noticia
        $slug = $exp_url[4];
      endif;

      /*How many records you want to show in a single page.*/
      $limit = 2;
      if(isset($dados['page']) && $dados['page'] != "") {
        $page = $dados['page'];
        $offset = $limit * ($page);
      } else {
        $page = 1;
        $offset = 0;
      }

      $total_pages = ceil($count_noticia[0]["COUNT"]/$limit);
      $res = $dbnoticia->loadNoticia($offset,$limit,$cat);

      if(!empty($res)) {
        $results = "";
        $results .= '<input type="hidden" name="total_pages" id="total_pages" value="' . $total_pages . '">';
        $results .= '<input type="hidden" name="page" id="page" value="' . $page . '">';

          foreach ($res as $noticia): 

            $ident_cat = $noticiacat->noticiaSelecionar($noticia['D007_Categoria']);

            $dt = explode("-", $noticia['D007_Data']);
            switch ($dt[1]):
              case 1: $mes = "Jan"; break;
              case 2: $mes = "Fev"; break;
              case 3: $mes = "Mar"; break;
              case 4: $mes = "Abr"; break;
              case 5: $mes = "Mai"; break;
              case 6: $mes = "Jun"; break;
              case 7: $mes = "Jul"; break;
              case 8: $mes = "Ago"; break;
              case 9: $mes = "Set"; break;
              case 10: $mes = "Out"; break;
              case 11: $mes = "Nov"; break;
              case 12: $mes = "Dez"; break;
            endswitch;  

            $results .= '<article class="post post-large">';
            $results .= '<div class="post-image">';
            $results .= '<div>
                          <div class="img-thumbnail d-block">
                            <img class="img-fluid" src="/images/noticia/'.$noticia['D007_Foto'].'" alt="'.$noticia['D007_Titulo'].'">
                          </div>
                        </div>
                    </div>';
            $results .= '<div class="post-date">
              <span class="day">'.$dt[2].'</span>
              <span class="month">'.$mes.'</span>
            </div>
            <div class="post-content">
              <h2><a href="/noticias/'.$ident_cat[0]['D007_Chave'].'/'.$noticia['D007_Chave'].'">'.$noticia['D007_Titulo'].'</a></h2>
              '.str_replace("|", "/", $noticia['D007_Chamada']).'
              <div class="post-meta">
                <span class="d-block d-sm-inline-block float-sm-right mt-3 mt-sm-4"><a href="/noticias/'.$ident_cat[0]['D007_Chave'].'/'.$noticia['D007_Chave'].'" class="btn btn-xs btn-primary">Saiba mais</a></span>
              </div>

            </div>
            </article>';

          endforeach;

        echo $results;
      }

    }  

    /* SERVIÇOS */
    public function servicos()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $servico = new App_Model_servicoModel();
        $servicofotos = new App_Model_servicofotosModel();
        $rs = $servico->listaservico();
        //$paginacao = self::paginacao("d007servico",NULL,"D007_Data","/page/servicos/p",$parm['p']);
        //print_r($paginacao);
        foreach ($rs as $foto):
  
          $serv_fotos = $servicofotos->servicoSelecionarDestaque($foto['D011_Imageminicio']);
          if (!empty($serv_fotos)):
                    $rs1 = $serv_fotos[0]['D011_Imagem'];
                else:
                    $rs1 = "";
          endif;
          //$destaque .= $foto["D015_Uid"]."-".$rs1.",";
          $arr = Array("D011_Uid" => $foto["D011_Uid"],
              "D011_Titulo" => $foto["D011_Titulo"],
              "D011_Chave" => $foto["D011_Chave"],
              "D011_Descricao" => $foto["D011_Descricao"],
              "D011_Imagem" => $rs1,
              "D011_Tags" => $foto["D011_Tags"]
          );
          $gal[] = $arr;
        endforeach;
        array_push($gal, $arr);
        array_pop($gal);
        $dados['servicos'] = $gal;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //CHAMA INDEX BACKEND
        parent::Site("servicos", $dados);
        self::footer();
    }
    public function servico()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $servico = new App_Model_servicoModel();
        $rs = $servico->servicoSelecionarChave($parm["link"]);
        $dados['servico'] = $rs;
        if (!empty($rs)):
          $servicoFotos = new App_Model_servicofotosModel();
          $rs2 = $servicoFotos->servicoSelecionarFotos($rs[0]['D011_Uid']);
          $dados['foto'] = $rs2;
        else:
          $dados['foto'] = array();
        endif;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //CHAMA INDEX BACKEND
        parent::Site("servico", $dados);
        self::footer();
    }


    /***************************************
     *********   VIDEOS
     *****************************************/
    public function videos()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $paginacao = self::paginacao("d014video", NULL, "D014_Data", "/page/videos/p", $parm['p']);
        $dados['videos'] = $paginacao;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //CHAMA INDEX BACKEND
        parent::Site("videos", $dados);
        self::footer();
    }
    public function video()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $video = new App_Model_videoModel();
        $rs = $video->videoChave($parm["link"]);
        $dados['video'] = $rs;
        //CHAMA INDEX BACKEND
        parent::Site("video", $dados);
        self::footer();
    }


    /***************************************
     *********   GALERIAS
     *****************************************/
    public function galerias()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $galeria = new App_Model_galeriaModel();
        $galeriafotos = new App_Model_galeriafotosModel();
        $rs = $galeria->listagaleria();
        //$paginacao = self::paginacao("d007galeria",NULL,"D007_Data","/page/galerias/p",$parm['p']);
        //print_r($paginacao);
        foreach ($rs as $foto):
  
          $serv_fotos = $galeriafotos->galeriaSelecionarDestaque($foto['D011_Imageminicio']);
          if (!empty($serv_fotos)):
                    $rs1 = $serv_fotos[0]['D011_Imagem'];
                else:
                    $rs1 = "";
          endif;
          //$destaque .= $foto["D015_Uid"]."-".$rs1.",";
          $arr = Array("D011_Uid" => $foto["D011_Uid"],
              "D011_Titulo" => $foto["D011_Titulo"],
              "D011_Chave" => $foto["D011_Chave"],
              "D011_Descricao" => $foto["D011_Descricao"],
              "D011_Imagem" => $rs1,
              "D011_Chamada" => $foto["D011_Chamada"]
          );
          $gal[] = $arr;
        endforeach;
        array_push($gal, $arr);
        array_pop($gal);
        $dados['galerias'] = $gal;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //CHAMA INDEX BACKEND
        parent::Site("galerias", $dados);
        self::footer();
    }

    public function galeria()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $galeria = new App_Model_galeriaModel();
        $rs = $galeria->galeriaSelecionarChave($parm["link"]);
        $dados['galeria'] = $rs;
        if (!empty($rs)):
          $galeriaFotos = new App_Model_galeriafotosModel();
          $rs2 = $galeriaFotos->galeriaSelecionarFotos($rs[0]['D011_Uid']);
          $dados['foto'] = $rs2;
        else:
          $dados['foto'] = array();
        endif;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //CHAMA INDEX BACKEND
        parent::Site("galeria", $dados);
        self::footer();
    }


    /*EVENTOS */
    public function eventos()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $eventocat = new App_Model_eventocatModel();
        $evento = new App_Model_eventoModel();
        if ($parm['cat'] == "all"):
            $paginacao = self::paginacao("d016evento", NULL, "D016_Data,D016_Titulo", "/page/eventos/cat/all/p", $parm['p']);
            $dados['evento'] = $paginacao;
        else:
            $sel_categoria = $eventocat->eventoSelecionarChave($parm['cat']);
            $paginacao = self::paginacao("d016evento", "D016_Categoria =" . $sel_categoria[0]['D016_Uid'], "D016_Data,D016_Titulo", "/page/eventos/cat/" . $parm['cat'] . "/p", $parm['p']);
            $dados['evento'] = $paginacao;
        endif;
        // $sel_equipe = $equipe->listaEnd();
        // $dados['last_equipes'] = $sel_equipe;
        $cat_evento = $eventocat->listaEvento();
        $dados['cat_eventos'] = $cat_evento;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //CHAMA INDEX BACKEND
        parent::Site("eventos", $dados);
        self::footer();
    }
    public function evento()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $evento = new App_Model_eventoModel();
        $evento_lista = $evento->eventoChave($parm['link']);
        $equipe = new App_Model_equipeModel();
        $m_estado = new App_Model_estadoModel();
        $m_cidade = new App_Model_cidadeModel();
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        if (!empty($evento_lista)):
            foreach ($evento_lista as $agenda):
                if (strstr($agenda['D016_Atracao'], ",")) {
                    //echo "tem!";
                    $dados_atracao = explode(",", $agenda['D016_Atracao']);
                } else {
                    //echo "Não Tem!";
                    $dados_atracao = array(0 => $agenda['D016_Atracao']);
                }
                //print_r($dados_atracao);
                foreach ($dados_atracao as $atr):
                    $s_atracao = $equipe->equipeSelecionar($atr);
                    $arr_atr = array(
                        "D016_Titulo" => $s_atracao[0]['D016_Titulo'],
                        "D016_Chave" => $s_atracao[0]['D016_Chave'],
                        "D016_Funcao" => $s_atracao[0]['D016_Funcao'],
                        "D016_Img" => $s_atracao[0]['D016_Img']
                    );
                    if (in_array($s_atracao[0]['D016_Uid'], $dados_atracao)):
                        $arr3_agenda[] = $arr_atr;
                    endif;
                endforeach;
                // if (!empty($arr3_agenda)):
                //   // array_push($arr3_agenda, $arr_atr);
                //   // array_pop($arr3_agenda);   
                // endif;
                $dados_data = explode(" ", $agenda['D016_Data']);
                if ($agenda['D016_Dataend'] != "0000-00-00 00:00:00"):
                    $dados_data2 = explode(" ", $agenda['D016_Dataend']);
                else:
                    $dados_data2 = array(0 => "", 1 => "");
                endif;
                $s_cidade = $m_cidade->cidadeSelecionar($agenda["D016_Cidade"]);
                $s_estado = $m_estado->estadoSelecionar($agenda["D016_Estado"]);
                if (!empty($s_cidade)):
                    $cidade = utf8_encode($s_cidade[0]['DSC_CIDADE']);
                else:
                    $cidade = "";
                endif;
                if (!empty($s_estado)):
                    $estado = utf8_encode($s_estado[0]['DSC_ESTADO']);
                else:
                    $estado = "";
                endif;
                $arr_eve = array(
                    "D016_Titulo" => $agenda['D016_Titulo'],
                    "D016_Descricao" => $agenda['D016_Descricao'],
                    "D016_Data" => $dados_data[0],
                    "D016_Hora" => $dados_data[1],
                    "D016_Dataend" => $dados_data2[0],
                    "D016_Horaend" => $dados_data2[1],
                    "D016_Local" => $agenda['D016_Local'],
                    "D016_Endereco" => $agenda['D016_Endereco'],
                    "D016_Bairro" => $agenda['D016_Bairro'],
                    "D016_Cidade" => $cidade,
                    "D016_Estado" => $estado,
                    "D016_Atracao" => $arr3_agenda,
                    "D016_Foto" => $agenda['D016_Foto'],
                    "D016_Tipo" => $agenda['D016_Tipo'],
                    "D016_Video" => $agenda['D016_Video']
                );
                $arr2_agenda[] = $arr_eve;
                $arr3_agenda = array();
            endforeach;
            if (!empty($arr2_agenda)):
                array_push($arr2_agenda, $arr_eve);
                //retira um elemento no final do array
                array_pop($arr2_agenda);
                $dados['evento'] = $arr2_agenda;
            else:
                $dados['evento'] = array();
            endif;
        else:
            $dados['evento'] = array();
        endif;
        //print_r( $dados['evento']);
        /************************************************/
        parent::Site("evento", $dados);
        self::footer();
    }

    /*CURSOS */
    public function cursos()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $cursocat = new App_Model_cursocatModel();
        $curso = new App_Model_cursoModel();
        if ($parm['ref'] == "all"):
            $paginacao = self::paginacao("d016curso", NULL, "D016_Titulo ASC", "/cursos/ref/all/p", $parm['p']);
            $dados['curso'] = $paginacao;
        else:
            $sel_categoria = $cursocat->cursoSelecionarChave($parm['ref']);
            $paginacao = self::paginacao("d016curso", "D016_Categoria =" . $sel_categoria[0]['D016_Uid'], "D016_Data,D016_Titulo", "/cursos/ref/" . $parm['ref'] . "/p", $parm['p']);
            $dados['curso'] = $paginacao;
            $dados['curso_detalhe'] = $sel_categoria;
        endif;
        // $sel_equipe = $equipe->listaEnd();
        // $dados['last_equipes'] = $sel_equipe;
        $cat_curso = $cursocat->listaCurso();
        $dados['cat_cursos'] = $cat_curso;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //CHAMA INDEX BACKEND
        parent::Site("cursos", $dados);
        self::footer();
    }

    public function curso()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $curso = new App_Model_cursoModel();
        $curso_lista = $curso->cursoChave($parm['link']);
        $aula = new App_Model_aulaModel();
        $aula_lista = $aula->listaAulaFree($curso_lista[0]["D016_Uid"]);
        $aula_arq = new App_Model_aulafotosModel();
        
        foreach ($aula_lista as $item_aula):
          $rs_arquivo = $aula_arq->listaArqFree($item_aula["aula_id"]);
          if(!empty( $rs_arquivo )):
            if ($item_aula["aula_tipostatus"] == "public"):
              $file = $rs_arquivo[0]["arq_file"];
            else:
              $file = "";
            endif;            
          else: 
            $file = "";
          endif;
          $arr_aula = Array(
              "aula_id" => $item_aula["aula_id"],           
              "aula_titulo" => $item_aula["aula_titulo"],
              "aula_status" => $item_aula["aula_status"],
              "aula_tipostatus" => $item_aula["aula_tipostatus"],
              "aula_ordem" => $item_aula["aula_ordem"],
              "aula_data" => $item_aula["aula_data"],
              "aula_disciplina" => $item_aula["aula_disciplina"],
              "arq_file" => $file
          );
          $input_aula[] = $arr_aula;
        endforeach;
        array_push($input_aula, $arr_aula);
        array_pop($input_aula);
        $dados['arquivos'] = $input_aula;
  
        $cursocat = new App_Model_cursocatModel();
        $cat_curso = $cursocat->listaCurso();
        $dados['cat_cursos'] = $cat_curso;
        $equipe = new App_Model_equipeModel();
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        if (!empty($curso_lista)):
            $dados['curso'] = $curso_lista;
        else:
            $dados['curso'] = array();
        endif;
        //print_r( $dados['evento']);
        /************************************************/
        parent::Site("curso", $dados);
        self::footer();
    }

    public function testemunho()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        $paginacao = self::paginacao("d004testemunho", NULL, "D004_Uid DESC", "/page/testemunho/p", $parm['p']);
        $dados['testemunho'] = $paginacao;
        parent::Site("testemunho", $dados);
        self::footer();
    }

    //$qs = "about";
    //if (in_array($qs,["about","institucional","quem-somos","sobre-nos","sobre"])):
    
    public function institucional()
    {
        self::nav();
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        $institucional = new App_Model_institucionalModel();
        $pagina = new App_Model_paginaModel();
        
        $menu_link = unserialize (MENU_LINK);
        $header = $pagina->paginaSelecionarLang($menu_link["controller"],$menu_link["sigla"]);
        $dados['header'] = $header;

        $conteudo = $institucional->institucionalSelecionarLang($menu_link["controller"],$menu_link["sigla"]);
        $dados['conteudo'] = $conteudo;

        /* INSTITUCIONAL */

        parent::Site("institucional", $dados);
        self::footer();
    }

    //endif;



    /***************************************
     *********   PRODUTOS
     *****************************************/
    public function modelos()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $paginacao = self::paginacao("d011produto_cat", "D011_PertenceCodigoMaster = 0 ", "D011_Ordem", "/page/modelos/p", $parm['p']);
        //print_r($paginacao);
        $dados['modelos'] = $paginacao;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //CHAMA INDEX BACKEND
        parent::Site("modelos", $dados);
        self::footer();
    }
    public function produtosdestaque()
    {
        self::nav();
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        $produto = new App_Model_produtoModel();
        $rs = $produto->listaProdutoDestaque();
        $dados['produtosdestaque'] = $rs;
        parent::Site("produtosdestaque", $dados);
        self::footer();
    }
    public function produtos()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        //if (isset($parm['tipo']))://VERIFICAR SE O TIPO FOI SELECIONADO
            $model_cat = new App_Model_produtocatModel();
            $catfull = $model_cat->produtoSelecionarModelo();

            $db_produto = new App_Model_produtoModel();
            $sel_produto = $db_produto->listaproduto();

            if (isset($parm['tipo'])):
              if ($parm['tipo'] == "all"):
                  $mod = $model_cat->produtoSelecionarModelo();
                  $parm['tipo'] == '';
                  $filtrocat = '';
              else:
                  $mod = $model_cat->produtoSelecionarChave($parm['tipo']);
                  $filtrocat = " AND D011_Categoria = '".$mod[0]['D011_Uid']."'";
              endif;
            else:
              $parm['tipo'] == '';
              $filtrocat = '';
            endif;

            //$mod_categ = $model_cat->selecionarMaster($mod[0]['D011_Uid']);
            if (isset($parm['modelo'])):
              if (!empty($parm['modelo'])):
                  $modsub = $model_cat->produtoSelecionarChave($parm['modelo']);
                  $parametro_modelo = " AND D011_CategoriaSub1 = " . $modsub[0]['D011_Uid'];
                  $modelourl = "/modelo/" . $parm['modelo'];
              else:
                  $parametro_modelo = "";
                  $modelourl = "";
              endif;
            else:
              $parametro_modelo = "";
              $modelourl = "";
            endif;

            //$paginacao = self::paginacaoProdutos("d011produto", " D011_Categoria = " . $mod[0]['D011_Uid'] . $parametro_modelo, "D011_Titulo", "/page/produtos/tipo/" . $parm['tipo'] . $modelourl . "/p", $parm['p']);
            //$paginacao = self::paginacaoProdutos("d011produto", " D011_Status = 'sim' " . $filtrocat . $parametro_modelo , "D011_Ordem", "/produtos/tipo/" . $parm['tipo'] . $modelourl . "/p", $parm['p']);

            $config = unserialize(CONFIG_DB);
            $dados['config'] = $config;

            //print_r($paginacao);
            if (!empty($sel_produto)):
                //$dados['galerias'] = $paginacao;
                /*DADOS GALERIA FOTOS*/
                //$arr = "";
                $destaque = "";
                $categoria = "";
                foreach ($sel_produto as $foto):
                    $rs_cat = $model_cat->produtoSelecionar($foto["D011_Categoria"]);
                    $rs_subcat = $model_cat->produtoSelecionar($foto["D011_CategoriaSub1"]);
                    if (!empty($rs_cat)):
                        $rs2 = $rs_cat[0]['D011_Chave'];
                        $titulocat = $rs_cat[0]['D011_DescriCategoria'];
                    else:
                        $rs2 = "null";
                        $titulocat = "";
                    endif;
                    if (!empty($rs_subcat)):
                        $rs22 = $rs_subcat[0]['D011_Chave'];
                        if (!empty($parm['modelo'])):
                            $titulosubcat = $rs_subcat[0]['D011_DescriCategoria'];
                        else:
                            $titulosubcat = "null";
                        endif;
                    else:
                        $rs22 = "null";
                        $titulosubcat = "";
                    endif;
                    $categoria .= $foto["D011_Uid"] . "-" . $rs2 . ",";
                    $model_fotos = new App_Model_produtofotosModel();
                    $rs_fotos = $model_fotos->produtoSelecionarFotos($foto["D011_Uid"]);
                    if (!empty($rs_fotos)):
                        $rs = count($rs_fotos);
                    else:
                        $rs = 0;
                    endif;
                    //$arr .= $foto["D015_Uid"]."-".$rs.",";
                    $rs_destaque = $model_fotos->produtoSelecionar($foto["D011_Imageminicio"]);
                    if (!empty($rs_destaque)):
                        $rs1 = $rs_destaque[0]['D011_Imagem'];
                    else:
                        $rs1 = "";
                    endif;
                    //$destaque .= $foto["D015_Uid"]."-".$rs1.",";
                    $arr = Array(
                        "D011_Uid" => $foto["D011_Uid"],
                        "D011_Codigo" => $foto["D011_Codigo"],
                        "D011_Titulo" => $foto["D011_Titulo"],
                        "D011_Chave" => $foto["D011_Chave"],
                        "D011_Chamada" => $foto["D011_Chamada"],
                        "D011_Descricao" => $foto["D011_Descricao"],
                        "D011_Valor" => $foto["D011_Valor"],
                        "D011_Valor2" => $foto["D011_Valor2"],
                        "D011_Imageminicio" => $rs1,
                        "D011_Categoria" => $rs2,
                        "D011_SubCategoria" => $rs22,
                        "D011_CategoriaTitulo" => $titulocat,
                        "D011_SubCategoriaTitulo" => $titulosubcat,
                        "D011_Data" => $foto["D011_DATA"],
                        "D011_Destaque" => $foto["D011_Destaque"],
                        "D011_Status" => $foto["D011_Status"],
                        "D011_Ordem" => $foto["D011_Ordem"],
                        "D011_Tags" => $foto["D011_Tags"],
                        "count" => count($rs_fotos)
                    );
                    $gal[] = $arr;
                endforeach;
                if (!empty($gal)):
                    //envia cada registro para o final do array
                    array_push($gal, $arr);
                    //retira um elemento no final do array
                    array_pop($gal);
                    $dados['dados2'] = $gal;
                    array_push($sel_produto, $dados['dados2']);
                    $dados['produtos'] = $sel_produto;
                endif;
                $arr = array();
                $arr2 = array();
                $menu = new App_Model_produtocatModel();
                $menu_lista_cad = $menu->listaprodutoIndex();
                foreach ($menu_lista_cad as $value):
                    $count = $menu->countproduto($value['D011_Uid']);
                    if (count($count) != 0):
                        $arr += array($value['D011_Uid'] => count($count));
                    endif;
                    foreach ($count as $value2):
                        $countsub1 = $menu->countSub1($value['D011_Uid'], $value2['D011_Uid']);
                        if (count($countsub1) != 0):
                            $arr2 += array($value2['D011_Uid'] => count($countsub1));
                        endif;
                    endforeach;
                    $arr_cat = Array(
                        "D011_Uid" => $value["D011_Uid"],
                        "D011_DescriCategoria" => $value["D011_DescriCategoria"],
                        "D011_Chave" => $value["D011_Chave"]
                    );
                    $cat[] = $arr_cat;
                endforeach;
                if (!empty($cat)):
                    //envia cada registro para o final do array
                    array_push($cat, $arr_cat);
                    //retira um elemento no final do array
                    array_pop($cat);
                    $dados['menu_lista'] = $cat;
                else:
                    $dados['menu_lista'] = array();
                endif;
                $sub_master = $menu->listaMaster();
                $sub_1 = $menu->listaSub1();
                $sub_2 = $menu->listaSub2();
                $sub_3 = $menu->listaSub3();
                $sub_4 = $menu->listaSub4();
                $sub_5 = $menu->listaSub5();
                $dados['menu_count'] = $arr;
                $dados['sub1_count'] = $arr2;
                //$dados['menu_lista'] = $menu_lista_cad;
                $dados['sub_master'] = $sub_master;
                $dados['sub_1'] = $sub_1;
                $dados['sub_2'] = $sub_2;
                $dados['sub_3'] = $sub_3;
                $dados['sub_4'] = $sub_4;
                $dados['sub_5'] = $sub_5;

                //CHAMA INDEX BACKEND
                parent::Site("produtos", $dados);
            else:
                $dados['produtos'] = array();
                //CHAMA INDEX BACKEND
                parent::Site("produtos", $dados);
            endif;
        //endif;
        self::footer();
    }
    public function produto()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $produto = new App_Model_produtoModel();
        if (!empty($parm["ref"])):
            $rs = $produto->produtoSelecionarChave($parm["ref"]);
        endif;
        $dados['produto'] = $rs;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        if (!empty($rs)):
            $produtoFotos = new App_Model_produtofotosModel();
            $rs2 = $produtoFotos->produtoSelecionarFotos($rs[0]['D011_Uid']);
            $dados['foto'] = $rs2;
        else:
          $dados['foto'] = array();
        endif;
        $model_cat = new App_Model_produtocatModel();
        $mod_categ = $model_cat->produtoSelecionar($rs[0]['D011_CategoriaSub1']);
        $mod_tipo = $model_cat->produtoSelecionar($rs[0]['D011_Categoria']);
        // $dados['modelo'] = $mod_categ[0]['D011_DescriCategoria'];
        if (!empty($mod_tipo)):
          $dados['tipo'] = $mod_tipo[0]['D011_DescriCategoria'];
        else:
          $dados['tipo'] = "";
        endif;
        
        $arr = array();
        $arr2 = array();
        $menu = new App_Model_produtocatModel();
        $menu_lista_cad = $menu->listaprodutoIndex();
        foreach ($menu_lista_cad as $value):
            $count = $menu->countproduto($value['D011_Uid']);
            if (count($count) != 0):
                $arr += array($value['D011_Uid'] => count($count));
            endif;
            foreach ($count as $value2):
                $countsub1 = $menu->countSub1($value['D011_Uid'], $value2['D011_Uid']);
                if (count($countsub1) != 0):
                    $arr2 += array($value2['D011_Uid'] => count($countsub1));
                endif;
            endforeach;
            $arr_cat = Array(
                "D011_Uid" => $value["D011_Uid"],
                "D011_DescriCategoria" => $value["D011_DescriCategoria"],
                "D011_Chave" => $value["D011_Chave"]
            );
            $cat[] = $arr_cat;
        endforeach;
        if (!empty($cat)):
            //envia cada registro para o final do array
            array_push($cat, $arr_cat);
            //retira um elemento no final do array
            array_pop($cat);
            $dados['menu_lista'] = $cat;
        else:
            $dados['menu_lista'] = array();
        endif;
        $sub_master = $menu->listaMaster();
        $sub_1 = $menu->listaSub1();
        $sub_2 = $menu->listaSub2();
        $sub_3 = $menu->listaSub3();
        $sub_4 = $menu->listaSub4();
        $sub_5 = $menu->listaSub5();
        $dados['menu_count'] = $arr;
        $dados['sub1_count'] = $arr2;
        //$dados['menu_lista'] = $menu_lista_cad;
        $dados['sub_master'] = $sub_master;
        $dados['sub_1'] = $sub_1;
        $dados['sub_2'] = $sub_2;
        $dados['sub_3'] = $sub_3;
        $dados['sub_4'] = $sub_4;
        $dados['sub_5'] = $sub_5;
        //CHAMA INDEX BACKEND
        parent::Site("produto", $dados);
        self::footer();
    }
    /* PACOTES */
    public function pacotes()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        //if (isset($parm['tipo']))://VERIFICAR SE O TIPO FOI SELECIONADO
            $model_cat = new App_Model_pacotecatModel();
            $catfull = $model_cat->pacoteSelecionarModelo();
            if (isset($parm['tipo'])):
              if ($parm['tipo'] == "all"):
                  $mod = $model_cat->pacoteSelecionarModelo();
                  $parm['tipo'] = '';
                  $filtrocat = '';
              else:
                  $mod = $model_cat->pacoteSelecionarChave($parm['tipo']);
                  $filtrocat = " AND D011_Categoria = '".$mod[0]['D011_Uid']."'";
              endif;
            else:
              $parm['tipo'] == '';
              $filtrocat = '';
            endif;
            //$mod_categ = $model_cat->selecionarMaster($mod[0]['D011_Uid']);
            if (isset($parm['modelo'])):
              if (!empty($parm['modelo'])):
                  $modsub = $model_cat->pacoteSelecionarChave($parm['modelo']);
                  $parametro_modelo = " AND D011_CategoriaSub1 = " . $modsub[0]['D011_Uid'];
                  $modelourl = "/modelo/" . $parm['modelo'];
              else:
                  $parametro_modelo = "";
                  $modelourl = "";
              endif;
            else:
              $parametro_modelo = "";
              $modelourl = "";
            endif;
            //$paginacao = self::paginacaoProdutos("d011produto", " D011_Categoria = " . $mod[0]['D011_Uid'] . $parametro_modelo, "D011_Titulo", "/page/produtos/tipo/" . $parm['tipo'] . $modelourl . "/p", $parm['p']);
            $paginacao = self::paginacaoProdutos("d011pacote", " D011_Status = 'sim' " . $filtrocat . $parametro_modelo , "D011_Ordem", "/pacotes/tipo/" . $parm['tipo'] . $modelourl . "/p", $parm['p']);
            
            //print_r($paginacao);
            if (!empty($paginacao)):
                //$dados['galerias'] = $paginacao;
                /*DADOS GALERIA FOTOS*/
                //$arr = "";
                $destaque = "";
                $categoria = "";
                foreach ($paginacao["dados"] as $foto):
                    $rs_cat = $model_cat->pacoteSelecionar($foto["D011_Categoria"]);
                    $rs_subcat = $model_cat->pacoteSelecionar($foto["D011_CategoriaSub1"]);
                    if (!empty($rs_cat)):
                        $rs2 = $rs_cat[0]['D011_Chave'];
                        $titulocat = $rs_cat[0]['D011_DescriCategoria'];
                    else:
                        $rs2 = "null";
                        $titulocat = "";
                    endif;
                    if (!empty($rs_subcat)):
                        $rs22 = $rs_subcat[0]['D011_Chave'];
                        if (!empty($parm['modelo'])):
                            $titulosubcat = $rs_subcat[0]['D011_DescriCategoria'];
                        else:
                            $titulosubcat = "null";
                        endif;
                    else:
                        $rs22 = "null";
                        $titulosubcat = "";
                    endif;
                    $categoria .= $foto["D011_Uid"] . "-" . $rs2 . ",";
                    $model_fotos = new App_Model_pacotefotosModel();
                    $rs_fotos = $model_fotos->pacoteSelecionarFotos($foto["D011_Uid"]);
                    if (!empty($rs_fotos)):
                        $rs = count($rs_fotos);
                    else:
                        $rs = 0;
                    endif;
                    //$arr .= $foto["D015_Uid"]."-".$rs.",";
                    $rs_destaque = $model_fotos->pacoteSelecionar($foto["D011_Imageminicio"]);
                    if (!empty($rs_destaque)):
                        $rs1 = $rs_destaque[0]['D011_Imagem'];
                    else:
                        $rs1 = "";
                    endif;
                    //$destaque .= $foto["D015_Uid"]."-".$rs1.",";
                    $arr = Array(
                        "D011_Uid" => $foto["D011_Uid"],
                        "D011_Codigo" => $foto["D011_Codigo"],
                        "D011_Titulo" => $foto["D011_Titulo"],
                        "D011_Chave" => $foto["D011_Chave"],
                        "D011_Chamada" => $foto["D011_Chamada"],
                        "D011_Descricao" => $foto["D011_Descricao"],
                        "D011_Valor" => $foto["D011_Valor"],
                        "D011_Valor2" => $foto["D011_Valor2"],
                        "D011_Imageminicio" => $rs1,
                        "D011_Categoria" => $rs2,
                        "D011_SubCategoria" => $rs22,
                        "D011_CategoriaTitulo" => $titulocat,
                        "D011_SubCategoriaTitulo" => $titulosubcat,
                        "D011_Data" => $foto["D011_DATA"],
                        "D011_Destaque" => $foto["D011_Destaque"],
                        "D011_Status" => $foto["D011_Status"],
                        "D011_Ordem" => $foto["D011_Ordem"],
                        "D011_Tags" => $foto["D011_Tags"],
                        "count" => count($rs_fotos)
                    );
                    $gal[] = $arr;
                endforeach;
                if (!empty($gal)):
                    //envia cada registro para o final do array
                    array_push($gal, $arr);
                    //retira um elemento no final do array
                    array_pop($gal);
                    $dados['dados2'] = $gal;
                    array_push($paginacao, $dados['dados2']);
                    $dados['pacotes'] = $paginacao;
                endif;
                $arr = array();
                $arr2 = array();
                $menu = new App_Model_pacotecatModel();
                $menu_lista_cad = $menu->listapacoteIndex();
                foreach ($menu_lista_cad as $value):
                    $count = $menu->countpacote($value['D011_Uid']);
                    if (count($count) != 0):
                        $arr += array($value['D011_Uid'] => count($count));
                    endif;
                    foreach ($count as $value2):
                        $countsub1 = $menu->countSub1($value['D011_Uid'], $value2['D011_Uid']);
                        if (count($countsub1) != 0):
                            $arr2 += array($value2['D011_Uid'] => count($countsub1));
                        endif;
                    endforeach;
                    $arr_cat = Array(
                        "D011_Uid" => $value["D011_Uid"],
                        "D011_DescriCategoria" => $value["D011_DescriCategoria"],
                        "D011_Chave" => $value["D011_Chave"]
                    );
                    $cat[] = $arr_cat;
                endforeach;
                if (!empty($cat)):
                    //envia cada registro para o final do array
                    array_push($cat, $arr_cat);
                    //retira um elemento no final do array
                    array_pop($cat);
                    $dados['menu_lista'] = $cat;
                else:
                    $dados['menu_lista'] = array();
                endif;
                
                $config = unserialize(CONFIG_DB);
                $dados['config'] = $config;

                $sub_master = $menu->listaMaster();
                $sub_1 = $menu->listaSub1();
                $sub_2 = $menu->listaSub2();
                $sub_3 = $menu->listaSub3();
                $sub_4 = $menu->listaSub4();
                $sub_5 = $menu->listaSub5();
                $dados['menu_count'] = $arr;
                $dados['sub1_count'] = $arr2;
                //$dados['menu_lista'] = $menu_lista_cad;
                $dados['sub_master'] = $sub_master;
                $dados['sub_1'] = $sub_1;
                $dados['sub_2'] = $sub_2;
                $dados['sub_3'] = $sub_3;
                $dados['sub_4'] = $sub_4;
                $dados['sub_5'] = $sub_5;
                //CHAMA INDEX BACKEND
                parent::Site("pacotes", $dados);
            else:
                $dados['pacotes'] = array();
                //CHAMA INDEX BACKEND
                parent::Site("pacotes", $dados);
            endif;
        //endif;
        self::footer();
    }
    public function pacote()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $pacote = new App_Model_pacoteModel();
        if (!empty($parm["ref"])):
            $rs = $pacote->pacoteSelecionarChave($parm["ref"]);
        endif;
        $dados['pacote'] = $rs;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        if (!empty($rs)):
            $pacoteFotos = new App_Model_pacotefotosModel();
            $rs2 = $pacoteFotos->pacoteSelecionarFotos($rs[0]['D011_Uid']);
            $dados['foto'] = $rs2;
        else:
          $dados['foto'] = array();
        endif;
        $model_cat = new App_Model_pacotecatModel();
        $mod_categ = $model_cat->pacoteSelecionar($rs[0]['D011_CategoriaSub1']);
        $mod_tipo = $model_cat->pacoteSelecionar($rs[0]['D011_Categoria']);
        // $dados['modelo'] = $mod_categ[0]['D011_DescriCategoria'];
        if (!empty($mod_tipo)):
          $dados['tipo'] = $mod_tipo[0]['D011_DescriCategoria'];
        else:
          $dados['tipo'] = "";
        endif;
        
        $arr = array();
        $arr2 = array();
        $menu = new App_Model_pacotecatModel();
        $menu_lista_cad = $menu->listapacoteIndex();
        foreach ($menu_lista_cad as $value):
            $count = $menu->countpacote($value['D011_Uid']);
            if (count($count) != 0):
                $arr += array($value['D011_Uid'] => count($count));
            endif;
            foreach ($count as $value2):
                $countsub1 = $menu->countSub1($value['D011_Uid'], $value2['D011_Uid']);
                if (count($countsub1) != 0):
                    $arr2 += array($value2['D011_Uid'] => count($countsub1));
                endif;
            endforeach;
            $arr_cat = Array(
                "D011_Uid" => $value["D011_Uid"],
                "D011_DescriCategoria" => $value["D011_DescriCategoria"],
                "D011_Chave" => $value["D011_Chave"]
            );
            $cat[] = $arr_cat;
        endforeach;
        if (!empty($cat)):
            //envia cada registro para o final do array
            array_push($cat, $arr_cat);
            //retira um elemento no final do array
            array_pop($cat);
            $dados['menu_lista'] = $cat;
        else:
            $dados['menu_lista'] = array();
        endif;
        $sub_master = $menu->listaMaster();
        $sub_1 = $menu->listaSub1();
        $sub_2 = $menu->listaSub2();
        $sub_3 = $menu->listaSub3();
        $sub_4 = $menu->listaSub4();
        $sub_5 = $menu->listaSub5();
        $dados['menu_count'] = $arr;
        $dados['sub1_count'] = $arr2;
        //$dados['menu_lista'] = $menu_lista_cad;
        $dados['sub_master'] = $sub_master;
        $dados['sub_1'] = $sub_1;
        $dados['sub_2'] = $sub_2;
        $dados['sub_3'] = $sub_3;
        $dados['sub_4'] = $sub_4;
        $dados['sub_5'] = $sub_5;
        //CHAMA INDEX BACKEND
        parent::Site("pacote", $dados);
        self::footer();
    }
    /* AGENCIA */
    public function agencias()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $config = unserialize(CONFIG_DB);
        //CONFIGURAÇÃO DO SITE
        $dados['config'] = $config;
        $agencia = new App_Model_agenciaModel();
        $model_cat = new App_Model_agencialocalModel();
        if ($parm['tipo'] == "all"):
            $ag = array();
            $dados['title'] = "";
        else:
            $sel_estado = $model_cat->agenciaSelecionarChave($parm['tipo']);
            $ag = $agencia->agenciaSelecionarCat($sel_estado[0]['D018_Uid']);
            $dados['title'] = $sel_estado[0]['D018_Titulo'];
        endif;
        $dados['chave'] = $parm["tipo"];
        $estado = $model_cat->listaAgencia();
        $dados['estado'] = $estado;
        $dados['agencia'] = $ag;
        //CHAMA INDEX BACKEND
        parent::Site("agencias", $dados);
        self::footer();
    }
    /***************************************
     *********   CIFRAS
     *****************************************/
    public function genero()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $paginacao = self::paginacao("d011cifra_cat", "D011_PertenceCodigoMaster = 0 ", "D011_Ordem", "/page/genero/p", $parm['p']);
        //print_r($paginacao);
        $dados['generos'] = $paginacao;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        $model_cifra = new App_Model_cifraModel();
        $sel_cifra = $model_cifra->listacifraLimit(10);
        $model_cifracat = new App_Model_cifracatModel();
        $model_cifrafoto = new App_Model_cifrafotosModel();
        foreach ($sel_cifra as $cif):
            $sel_genero = $model_cifracat->cifraSelecionar($cif['D011_Categoria']);
            $sel_artista = $model_cifracat->cifraSelecionar($cif['D011_CategoriaSub1']);
            $sel_album = $model_cifracat->cifraSelecionar($cif['D011_CategoriaSub2']);
            $arr_cifra = array(
                "D011_Uid" => $cif['D011_Uid'],
                "D011_Titulo" => $cif['D011_Titulo'],
                "D011_Codigo" => $cif['D011_Codigo'],
                "D011_Imageminicio" => $sel_artista[0]['D011_Imagem'],
                "D011_Chave" => $cif['D011_Chave'],
                "D011_Genero" => $sel_genero[0]['D011_Chave'],
                "D011_Artista" => $sel_artista[0]['D011_Chave'],
                "D011_Album" => $sel_album[0]['D011_Chave'],
                "D011_Data" => $cif['D011_Data'],
                "D011_Tags" => $cif['D011_Tags']
            );
            $cifra_arr[] = $arr_cifra;
        endforeach;
        if (!empty($cifra_arr)):
            //envia cada registro para o final do array
            array_push($cifra_arr, $arr_cifra);
            //retira um elemento no final do array
            array_pop($cifra_arr);
            $dados['limit_cifras'] = $cifra_arr;
        else:
            $dados['limit_cifras'] = array();
        endif;
        $arr = array();
        $arr2 = array();
        $menu = new App_Model_cifracatModel();
        $menu_lista_cad = $menu->listacifra3();
        foreach ($menu_lista_cad as $value):
            $count = $menu->countcifra($value['D011_Uid']);
            if (count($count) != 0):
                $arr += array($value['D011_Uid'] => count($count));
            endif;
            foreach ($count as $value2):
                $countsub1 = $menu->countSub1($value['D011_Uid'], $value2['D011_Uid']);
                if (count($countsub1) != 0):
                    $arr2 += array($value2['D011_Uid'] => count($countsub1));
                endif;
            endforeach;
        endforeach;
        $sub_master = $menu->listaMaster();
        $sub_1 = $menu->listaSub1();
        $sub_2 = $menu->listaSub2();
        $sub_3 = $menu->listaSub3();
        $sub_4 = $menu->listaSub4();
        $sub_5 = $menu->listaSub5();
        //print_r($sub_master);
        $dados['menu_count'] = $arr;
        $dados['sub1_count'] = $arr2;
        $dados['menu_lista'] = $menu_lista_cad;
        $dados['sub_master'] = $sub_master;
        $dados['sub_1'] = $sub_1;
        $dados['sub_2'] = $sub_2;
        $dados['sub_3'] = $sub_3;
        $dados['sub_4'] = $sub_4;
        $dados['sub_5'] = $sub_5;
        //CHAMA INDEX BACKEND
        parent::Site("generos", $dados);
        self::footer();
    }
    public function artistas()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        if (!empty($parm['artista']))://VERIFICAR SE O ARTISTA FOI SELECIONADO
            $model_cat = new App_Model_cifracatModel();
            $mod_genero = $model_cat->cifraSelecionarChave($parm['genero'], 0);
            $mod_artista = $model_cat->cifraSelecionarChave($parm['artista'], $mod_genero[0]['D011_Uid']);
            $mod_album = $model_cat->listaScifra($mod_genero[0]['D011_Uid'], $mod_artista[0]['D011_Uid'], 0, 0, 0, 0);
            $config = unserialize(CONFIG_DB);
            $dados['config'] = $config;
            $dados['artista'] = $mod_artista;
            if (!empty($mod_album)):
                $destaque = "";
                $categoria = "";
                foreach ($mod_album as $foto):
                    $rs_genero = $model_cat->cifraSelecionar($foto["D011_PertenceCodigoMaster"]);
                    $rs_artista = $model_cat->cifraSelecionar($foto["D011_PertenceCodigoSub1"]);
                    $model_cifra = new App_Model_cifraModel();
                    $sel_cifra = $model_cifra->cifraSelecionarAlbum($foto["D011_Uid"]);
                    $arr = Array(
                        "D011_Uid" => $foto["D011_Uid"],
                        "D011_Titulo" => $foto["D011_DescriCategoria"],
                        "D011_Chave" => $foto["D011_Chave"],
                        "D011_Imagem" => $foto["D011_Imagem"],
                        "D011_PertenceCodigoMaster" => $rs_artista[0]['D011_PertenceCodigoMaster'],
                        "D011_PertenceCodigoSub1" => $rs_artista[0]['D011_PertenceCodigoSub1'],
                        "D011_PertenceCodigoSub2" => $foto['D011_PertenceCodigoSub2'],
                        "D011_Ordem" => $foto["D011_Ordem"],
                        "D011_Link" => $rs_artista[0]['D011_Link'],
                        "D011_Artistanome" => $rs_artista[0]['D011_DescriCategoria'],
                        "D011_Artista" => $rs_artista[0]['D011_Chave'],
                        "D011_Generonome" => $rs_genero[0]['D011_DescriCategoria'],
                        "D011_Genero" => $rs_genero[0]['D011_Chave'],
                        "D011_Album" => $rs_genero[0]['D011_Chave'],
                        "D011_Cifra" => $sel_cifra
                    );
                    $gal[] = $arr;
                endforeach;
                if (!empty($gal)):
                    //envia cada registro para o final do array
                    array_push($gal, $arr);
                    //retira um elemento no final do array
                    array_pop($gal);
                    $dados['dados'] = $gal;
                else:
                    $dados['dados'] = array();
                endif;
            else:
                $dados['dados'] = array();
            endif;
            $dados['album'] = $mod_album;
            $arr = array();
            $arr2 = array();
            $menu = new App_Model_cifracatModel();
            $menu_lista_cad = $menu->listacifra3();
            foreach ($menu_lista_cad as $value):
                $count = $menu->countcifra($value['D011_Uid']);
                if (count($count) != 0):
                    $arr += array($value['D011_Uid'] => count($count));
                endif;
                foreach ($count as $value2):
                    $countsub1 = $menu->countSub1($value['D011_Uid'], $value2['D011_Uid']);
                    if (count($countsub1) != 0):
                        $arr2 += array($value2['D011_Uid'] => count($countsub1));
                    endif;
                endforeach;
            endforeach;
            $sub_master = $menu->listaMaster();
            $sub_1 = $menu->listaSub1();
            $sub_2 = $menu->listaSub2();
            $sub_3 = $menu->listaSub3();
            $sub_4 = $menu->listaSub4();
            $sub_5 = $menu->listaSub5();
            $dados['menu_count'] = $arr;
            $dados['sub1_count'] = $arr2;
            $dados['menu_lista'] = $menu_lista_cad;
            $dados['sub_master'] = $sub_master;
            $dados['sub_1'] = $sub_1;
            $dados['sub_2'] = $sub_2;
            $dados['sub_3'] = $sub_3;
            $dados['sub_4'] = $sub_4;
            $dados['sub_5'] = $sub_5;
            parent::Site("artistas", $dados);
        endif;
        self::footer();
    }
    public function cifras()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $cifra = new App_Model_cifraModel();
        $rs = $cifra->cifraSelecionarChave($parm["cifra"]);
        $dados['cifra'] = $rs;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        $model_cat = new App_Model_cifracatModel();
        $mod_artista = $model_cat->cifraSelecionarChave($parm["artista"], $rs[0]["D011_Categoria"]);
        $mod_genero = $model_cat->cifraSelecionarChave($parm["genero"], $rs[0]["D011_Categoria"]);
        $mod_album = $model_cat->cifraSelecionar($rs[0]["D011_CategoriaSub2"]);
        $dados['album'] = $mod_album;
        $dados['artista'] = $mod_artista;
        $dados['genero'] = $mod_genero;
        $arr = array();
        $arr2 = array();
        $menu = new App_Model_cifracatModel();
        $menu_lista_cad = $menu->listacifra3();
        foreach ($menu_lista_cad as $value):
            $count = $menu->countcifra($value['D011_Uid']);
            if (count($count) != 0):
                $arr += array($value['D011_Uid'] => count($count));
            endif;
            foreach ($count as $value2):
                $countsub1 = $menu->countSub1($value['D011_Uid'], $value2['D011_Uid']);
                if (count($countsub1) != 0):
                    $arr2 += array($value2['D011_Uid'] => count($countsub1));
                endif;
            endforeach;
        endforeach;
        $sub_master = $menu->listaMaster();
        $sub_1 = $menu->listaSub1();
        $sub_2 = $menu->listaSub2();
        $sub_3 = $menu->listaSub3();
        $sub_4 = $menu->listaSub4();
        $sub_5 = $menu->listaSub5();
        $dados['menu_count'] = $arr;
        $dados['sub1_count'] = $arr2;
        $dados['menu_lista'] = $menu_lista_cad;
        $dados['sub_master'] = $sub_master;
        $dados['sub_1'] = $sub_1;
        $dados['sub_2'] = $sub_2;
        $dados['sub_3'] = $sub_3;
        $dados['sub_4'] = $sub_4;
        $dados['sub_5'] = $sub_5;
        //CHAMA INDEX BACKEND
        parent::Site("cifras", $dados);
        self::footer();
    }
    /***************************************
     *********   PORTFOLIO
     *****************************************/
    public function mod()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $paginacao = self::paginacao("d011portfolio_cat", "D011_PertenceCodigoMaster = 0 ", "D011_Ordem", "/page/mod/p", $parm['p']);
        //print_r($paginacao);
        $dados['modeloportfolio'] = $paginacao;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //CHAMA INDEX BACKEND
        parent::Site("modeloportfolio", $dados);
        self::footer();
    }
    public function portfolios()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        if (!empty($parm['tipo']))://VERIFICAR SE O TIPO FOI SELECIONADO
            $model_cat = new App_Model_portfoliocatModel();
            $catfull = $model_cat->portfolioSelecionarModelo();
            if ($parm['tipo'] == "all"):
                $mod = $model_cat->portfolioSelecionarModelo();
                $parm['tipo'] == $mod[0]['D011_Uid'];
            else:
                $mod = $model_cat->portfolioSelecionarChave($parm['tipo']);
                $parm['tipo'] == $mod[0]['D011_Uid'];
            endif;
            $mod_categ = $model_cat->selecionarMaster($mod[0]['D011_Uid']);
            if (!empty($parm['modelo'])):
                $modsub = $model_cat->portfolioSelecionarChave($parm['modelo']);
                $parametro_modelo = " AND D011_CategoriaSub1 = " . $modsub[0]['D011_Uid'];
                $modelourl = "/modelo/" . $parm['modelo'];
            else:
                $parametro_modelo = "";
                $modelourl = "";
            endif;
            $paginacao = self::paginacaoProdutos("d011portfolio", " D011_Status = 'Sim' ", "D011_Titulo", "/page/portfolios/tipo/" . $parm['tipo'] . $modelourl . "/p", $parm['p']);
            $config = unserialize(CONFIG_DB);
            $dados['config'] = $config;
            //print_r($paginacao);
            if (!empty($paginacao)):
                //$dados['galerias'] = $paginacao;
                /*DADOS GALERIA FOTOS*/
                //$arr = "";
                $destaque = "";
                $categoria = "";
                foreach ($paginacao["dados"] as $foto):
                    $rs_cat = $model_cat->portfolioSelecionar($foto["D011_Categoria"]);
                    $rs_subcat = $model_cat->portfolioSelecionar($foto["D011_CategoriaSub1"]);
                    if (!empty($rs_cat)):
                        $rs2 = $rs_cat[0]['D011_Chave'];
                        $titulocat = $rs_cat[0]['D011_DescriCategoria'];
                    else:
                        $rs2 = "";
                        $titulocat = "";
                    endif;
                    if (!empty($rs_subcat)):
                        $rs22 = $rs_subcat[0]['D011_Chave'];
                        if (!empty($parm['modelo'])):
                            $titulosubcat = $rs_subcat[0]['D011_DescriCategoria'];
                        else:
                            $titulosubcat = "null";
                        endif;
                    else:
                        $rs22 = "null";
                        $titulosubcat = "";
                    endif;
                    $categoria .= $foto["D011_Uid"] . "-" . $rs2 . ",";
                    $model_fotos = new App_Model_portfoliofotosModel();
                    $rs_fotos = $model_fotos->portfolioSelecionarFotos($foto["D011_Uid"]);
                    if (!empty($rs_fotos)):
                        $rs = count($rs_fotos);
                    else:
                        $rs = 0;
                    endif;
                    //$arr .= $foto["D015_Uid"]."-".$rs.",";
                    $rs_destaque = $model_fotos->portfolioSelecionar($foto["D011_Imageminicio"]);
                    if (!empty($rs_destaque)):
                        $rs1 = $rs_destaque[0]['D011_Imagem'];
                    else:
                        $rs1 = "";
                    endif;
                    //$destaque .= $foto["D015_Uid"]."-".$rs1.",";
                    $arr = Array(
                        "D011_Uid" => $foto["D011_Uid"],
                        "D011_Subtitulo" => $foto["D011_Subtitulo"],
                        "D011_Titulo" => $foto["D011_Titulo"],
                        "D011_Chave" => $foto["D011_Chave"],
                        "D011_Descricao" => $foto["D011_Descricao"],
                        "D011_Cliente" => $foto["D011_Cliente"],
                        "D011_Tecnologia" => $foto["D011_Tecnologia"],
                        "D011_Site" => $foto["D011_Site"],
                        "D011_Valor" => $foto["D011_Valor"],
                        "D011_Valor2" => $foto["D011_Valor2"],
                        "D011_Imageminicio" => $rs1,
                        "D011_Categoria" => $rs2,
                        "D011_SubCategoria" => $rs22,
                        "D011_CategoriaTitulo" => $titulocat,
                        "D011_SubCategoriaTitulo" => $titulosubcat,
                        "D011_Data" => $foto["D011_Data"],
                        "D011_Destaque" => $foto["D011_Destaque"],
                        "D011_Status" => $foto["D011_Status"],
                        "D011_Ordem" => $foto["D011_Ordem"],
                        "D011_Tags" => $foto["D011_Tags"],
                        "count" => count($rs_fotos)
                    );
                    $gal[] = $arr;
                endforeach;
                if (!empty($gal)):
                    //envia cada registro para o final do array
                    array_push($gal, $arr);
                    //retira um elemento no final do array
                    array_pop($gal);
                    $dados['dados2'] = $gal;
                    array_push($paginacao, $dados['dados2']);
                    $dados['portfolios'] = $paginacao;
                endif;
                $arr = array();
                $arr2 = array();
                $menu = new App_Model_portfoliocatModel();
                $menu_lista_cad = $menu->listaportfolioIndex();
                foreach ($menu_lista_cad as $value):
                    $count = $menu->countportfolio($value['D011_Uid']);
                    if (count($count) != 0):
                        $arr += array($value['D011_Uid'] => count($count));
                    endif;
                    foreach ($count as $value2):
                        $countsub1 = $menu->countSub1($value['D011_Uid'], $value2['D011_Uid']);
                        if (count($countsub1) != 0):
                            $arr2 += array($value2['D011_Uid'] => count($countsub1));
                        endif;
                    endforeach;
                    $arr_cat = Array(
                        "D011_Uid" => $value["D011_Uid"],
                        "D011_DescriCategoria" => $value["D011_DescriCategoria"],
                        "D011_Chave" => $value["D011_Chave"]
                    );
                    $cat[] = $arr_cat;
                endforeach;
                if (!empty($cat)):
                    //envia cada registro para o final do array
                    array_push($cat, $arr_cat);
                    //retira um elemento no final do array
                    array_pop($cat);
                    $dados['menu_lista'] = $cat;
                else:
                    $dados['menu_lista'] = array();
                endif;
                $sub_master = $menu->listaMaster();
                $sub_1 = $menu->listaSub1();
                $sub_2 = $menu->listaSub2();
                $sub_3 = $menu->listaSub3();
                $sub_4 = $menu->listaSub4();
                $sub_5 = $menu->listaSub5();
                $dados['menu_count'] = $arr;
                $dados['sub1_count'] = $arr2;
                //$dados['menu_lista'] = $menu_lista_cad;
                $dados['sub_master'] = $sub_master;
                $dados['sub_1'] = $sub_1;
                $dados['sub_2'] = $sub_2;
                $dados['sub_3'] = $sub_3;
                $dados['sub_4'] = $sub_4;
                $dados['sub_5'] = $sub_5;
                //CHAMA INDEX BACKEND
                parent::Site("portfolios", $dados);
            else:
                $dados['portfolios'] = array();
                //CHAMA INDEX BACKEND
                parent::Site("portfolios", $dados);
            endif;
        endif;
        self::footer();
    }
    public function portfolio()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $portfolio = new App_Model_portfolioModel();
        if (!empty($parm["ref"])):
            $rs = $portfolio->portfolioSelecionarChave($parm["ref"]);
        endif;
        $dados['portfolio'] = $rs;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        if (!empty($rs)):
            $portfolioFotos = new App_Model_portfoliofotosModel();
            $rs2 = $portfolioFotos->portfolioSelecionarFotos($rs[0]['D011_Uid']);
            $dados['foto'] = $rs2;
            $testemunho = new App_Model_testemunhoModel();
            $sel_testemunho = $testemunho->testemunhoSelecionar($rs[0]['D011_Testemunho']);
            $dados['testemunho'] = $sel_testemunho;
        endif;
        $model_cat = new App_Model_portfoliocatModel();
        $mod_categ = $model_cat->portfolioSelecionar($rs[0]['D011_CategoriaSub1']);
        $mod_tipo = $model_cat->portfolioSelecionar($rs[0]['D011_Categoria']);
        if (!empty($mod_categ)):
            $dados['modelo'] = $mod_categ[0]['D011_DescriCategoria'];
        else:
            $dados['modelo'] = "null";
        endif;
        $dados['tipo'] = $mod_tipo[0]['D011_DescriCategoria'];
        $arr = array();
        $arr2 = array();
        $menu = new App_Model_portfoliocatModel();
        $menu_lista_cad = $menu->listaportfolioIndex();
        foreach ($menu_lista_cad as $value):
            $count = $menu->countportfolio($value['D011_Uid']);
            if (count($count) != 0):
                $arr += array($value['D011_Uid'] => count($count));
            endif;
            foreach ($count as $value2):
                $countsub1 = $menu->countSub1($value['D011_Uid'], $value2['D011_Uid']);
                if (count($countsub1) != 0):
                    $arr2 += array($value2['D011_Uid'] => count($countsub1));
                endif;
            endforeach;
            $arr_cat = Array(
                "D011_Uid" => $value["D011_Uid"],
                "D011_DescriCategoria" => $value["D011_DescriCategoria"],
                "D011_Chave" => $value["D011_Chave"]
            );
            $cat[] = $arr_cat;
        endforeach;
        if (!empty($cat)):
            //envia cada registro para o final do array
            array_push($cat, $arr_cat);
            //retira um elemento no final do array
            array_pop($cat);
            $dados['menu_lista'] = $cat;
        else:
            $dados['menu_lista'] = array();
        endif;
        $sub_master = $menu->listaMaster();
        $sub_1 = $menu->listaSub1();
        $sub_2 = $menu->listaSub2();
        $sub_3 = $menu->listaSub3();
        $sub_4 = $menu->listaSub4();
        $sub_5 = $menu->listaSub5();
        $dados['menu_count'] = $arr;
        $dados['sub1_count'] = $arr2;
        //$dados['menu_lista'] = $menu_lista_cad;
        $dados['sub_master'] = $sub_master;
        $dados['sub_1'] = $sub_1;
        $dados['sub_2'] = $sub_2;
        $dados['sub_3'] = $sub_3;
        $dados['sub_4'] = $sub_4;
        $dados['sub_5'] = $sub_5;
        /* PORTFOLIO */
        $portfoliosend = $portfolio->listaEndportfolio3();
        if (!empty($portfoliosend)):
            foreach ($portfoliosend as $value):
                $img = $portfolioFotos->portfolioSelecionar($value['D011_Imageminicio']);
                $catportfoliom = $model_cat->portfolioSelecionar($value['D011_Categoria']);
                if (!empty($img)):
                    $imag = $img[0]['D011_Imagem'];
                else:
                    $imag = "";
                endif;
                $arr_portfoliosend = array(
                    "D011_Uid" => $value['D011_Uid'],
                    "D011_Titulo" => $value['D011_Titulo'],
                    "D011_Chave" => $value['D011_Chave'],
                    "D011_Subtitulo" => $value['D011_Subtitulo'],
                    "D011_Categoria" => $catportfoliom[0]['D011_Chave'],
                    "D011_Cliente" => $value['D011_Cliente'],
                    "D011_Tecnologia" => $value['D011_Tecnologia'],
                    "D011_Site" => $value['D011_Site'],
                    "D011_Imageminicio" => $imag
                );
                $port[] = $arr_portfoliosend;
                //endif;
            endforeach;
            if (!empty($arr_portfoliosend)):
                //envia cada registro para o final do array
                array_push($port, $arr_portfoliosend);
                //retira um elemento no final do array
                array_pop($port);
                $dados['portfolioEnd'] = $port;
            else:
                $dados['portfolioEnd'] = array();
            endif;
        endif;
        //CHAMA INDEX BACKEND
        parent::Site("portfolio", $dados);
        self::footer();
    }
    //*************************************//
    //PAGINA TRABALHE CONOSCO
    public function Trabalheconosco()
    {
        self::nav();
        $cargo = new App_Model_ramoModel();
        $sub_cargo = $cargo->listaMasterNome();
        $dados['sub_cargo'] = $sub_cargo;
        $menu_cargo = $cargo->listaramo2();
        $dados['cargo'] = $menu_cargo;
        parent::Site("trabalheconosco", $dados);
        self::footer();
    }
    public function cargo()
    {
        // seta o pag do cliente
        $pag = new App_System();
        $pag->_urlAjax = $_POST['url'];
        $pag->setExplodeAjax();
        $pag->setControllerAjax();
        $pag->setActionAjax();
        $pag->setParamsAjax();
        $dados = $pag->getParamsAjax();
        //echo $dados['pag'];
        $cargo = new App_Model_ramoModel();
        $sub_cargo = $cargo->subSelecionar($dados['pag']);
        $select = "<option value='0'>Selecione</option>";
        foreach ($sub_cargo as $c):
            $select .= "<option value='" . $c['D008_Upag'] . "'>" . utf8_decode($c['D008_DescriCategoria']) . "</option>";
        endforeach;
        echo $select;
    }
    public function verificaTabalheConosco()
    {
        // seta o pag do cliente
        $pag = new App_System();
        $pag->_urlAjax = $_POST['url'];
        $pag->setExplodeAjax();
        $pag->setControllerAjax();
        $pag->setActionAjax();
        $pag->setParamsAjax();
        $dados = $pag->getParamsAjax();
        $verifica = new App_Model_trabconoscoModel();
        $rs = $verifica->verificaTrabconosco($dados['cpf']);
        if (empty($rs)):
            echo 0;
        else:
            echo 1;
        endif;
    }
    public function cadastrarTabalheConosco()
    {
        // seta o pag do cliente
        $pag = new App_System();
        $pag->_urlAjax = $_POST['url'];
        $pag->setExplodeAjax();
        $pag->setControllerAjax();
        $pag->setActionAjax();
        $pag->setParamsAjax();
        $dados = $pag->getParamsAjax();
        if (!empty($dados)):
            $cadastrar = new App_Model_trabconoscoModel();
            $rs = $cadastrar->trabconoscoCadastrar($dados);
            echo $rs;
        endif;
    }
    /*********************************************
     **************** ORÇAMENTO *******************
     **********************************************/
    public function orcamento()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $sel_produto = new App_Model_produtocatModel();
        $produto = $sel_produto->listaproduto();
        $dados['produto'] = $produto;
        $sel_unidade = new App_Model_unidadeModel();
        $unidade = $sel_unidade->listaunidade();
        $dados['unidade'] = $unidade;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        parent::Site("orcamento", $dados);
        self::footer();

    }
    public function orcamentocad()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $sel_servico = new App_Model_servicoModel();
        $servico = $sel_servico->listaservico();
        $dados['servico'] = $servico;
        parent::Site("orcamento_cad", $dados);
        self::footer();
    }
    public function orcamentoinsert()
    {
        // seta o pag do cliente
        $pag = new App_System();
        $pag->_urlAjax = $_POST['url'];
        $pag->setExplodeAjax();
        $pag->setControllerAjax();
        $pag->setActionAjax();
        $pag->setParamsAjax();
        $dados = $pag->getParamsAjax();
        if (!empty($dados)):
            $cadastrar = new App_Model_orcamentoModel();
            $rs = $cadastrar->orcamentoCadastrar($dados);
            echo $rs;
        endif;
    }
    /**************************************
     *********   TEXTO FIXO
     ***************************************/
    public function ver()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $conteudo = new App_Model_conteudoModel();
        $rs = $conteudo->conteudoSelecionarChave($parm["link"]);
        $dados['conteudo'] = $rs;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //CHAMA INDEX BACKEND
        parent::Site("conteudo", $dados);
        self::footer();
    }
    /**************************************
     *********   TEXTO FIXO
     ***************************************/
    public function destaque()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $artigo = new App_Model_artigoModel();
        //Conteudo ARTIGO
        $destaque = $artigo->artigoSelecionarChave($parm["link"]);
        if (isset($destaque)):
            $dados['destaque'] = $destaque;
        endif;
        //CHAMA INDEX BACKEND
        parent::Site("destaque", $dados);
        self::footer();
    }
    public function roteiro()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        $artigo = new App_Model_artigoModel();
        //Conteudo ARTIGO
        $destaque = $artigo->listaArtigo();
        if (!empty($destaque)):
            $dados['roteiro'] = $destaque;
        else:
            $dados['roteiro'] = array();
        endif;
        //CHAMA INDEX BACKEND
        parent::Site("roteiro", $dados);
        self::footer();
    }
    
    /**************************************
     *********   ERROR
     ***************************************/
    public function error()
    {
        self::nav();
        $conteudo = new App_Model_conteudoModel();
        $rs = $conteudo->conteudoSelecionarChave("error-404");
        $dados['error'] = $rs;
        //CHAMA INDEX BACKEND
        parent::Site("error", $dados);
        self::footer();
    }
    /**************************************
     *********   CONTATO
     ***************************************/
    public function contato()
    {
        self::nav();
        $conteudo = new App_Model_conteudoModel();
        $rs = $conteudo->conteudoSelecionarChave("contato");
        $dados['contato'] = $rs;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //Conteudo UNIDADE
        $unidade = new App_Model_unidadeModel();
        $sel_unidade = $unidade->listaUnidade();
        if (!empty($sel_unidade)):
            $dados['unidade'] = $sel_unidade;
        else:
            $dados['unidade'] = array();
        endif;
        //CHAMA INDEX BACKEND
        parent::Site("contato", $dados);
        self::footer();
    }
    public function contatoinsert()
    {
        // seta o pag do cliente
        $pag = new App_System();
        $pag->_urlAjax = $_POST['url'];
        $pag->setExplodeAjax();
        $pag->setControllerAjax();
        $pag->setActionAjax();
        $pag->setParamsAjax();
        $dados = $pag->getParamsAjax();
        if (!empty($dados)):
            //print_r($dados);
            $cadastrar = new App_Model_contatoModel();
            $rs = $cadastrar->contatoCadastrar($dados);
            echo $rs;
        endif;
    }
    /********************************************************************
     *                        LANDPAGE     *
     *********************************************************************/
    public function landpage_form($chave)
    {
        $site_page = "landpage_" . str_replace("-", "_", $chave);
        parent::Site($site_page);
    }
    public function landpage()
    {
        global $start;
        $parm = $start->_params;
        $land = new App_Model_landpageModel();
        $rs = $land->landpageSelecionarChave($parm["view"]);
        $dados['dados'] = $rs;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        //$dados['form'] = self::landpage_form($parm["view"]);
        if (!empty($rs)):
            if ($rs[0]["D019_Template"] == 1):
                parent::Site("landpage_template", $dados);
            endif;
            if ($rs[0]["D019_Template"] == 2):
                parent::Site("landpage_template2", $dados);
            endif;
            if ($rs[0]["D019_Template"] == 3):
                parent::Site("landpage_template3", $dados);
            endif;
        endif;
    }

    /***************************************
     *********   IMOVEL
     *****************************************/
    
    public function imoveis()
    {
        self::nav();

        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        
        global $start;
        $parm = $start->_params;
        
        $model_tipo = new App_Model_imovelTipoModel();
        $tipo = $model_tipo->listaimovel();

        $model_tipocliente = new App_Model_imovelTipoClienteModel();
        $tipocliente = $model_tipocliente->listaimovel();

        $model_piso = new App_Model_imovelPisoModel();

        $base = [];
      
        /* SE EXISTIR O PARAMENTRO REF */
        if (isset($parm['ref'])):
            $base["ref"] = $parm['ref'];
        endif;

        /* SE EXISTIR O PARAMENTRO TIPO */
        if (isset($parm['tipo'])):
            $tipochave = $model_tipo->imovelSelecionarChave($parm['tipo']);
            $base["tipo"] = $tipochave[0]["D011_Uid"];
        endif;

        /* SE EXISTIR O PARAMENTRO LOCALIDADE */
        if (isset($parm['localidade'])):
            $base["localidade"] = $parm['localidade'];     
        endif;

        /* SE EXISTIR O PARAMENTRO CATEGORIA */
        if (isset($parm['cat'])):
            $tipoclientechave = $model_tipocliente->imovelSelecionarChave($parm['cat']);
            $base["cat"] = $tipoclientechave[0]['D011_Uid'];
            $dados['categoria'] = $tipoclientechave[0]['D011_Titulo'];
        endif;

        /* SE EXISTIR O PARAMENTRO PRECO MIN */
        if (isset($parm['preco-min'])):
            $base["preco-min"] = str_replace(".","",$parm['preco-min']); 
            $base["preco-min"] = str_replace(",","",$parm['preco-min']);   
        endif;

        /* SE EXISTIR O PARAMENTRO PRECO MAX */
        if (isset($parm['preco-max'])):
            $base["preco-max"] = str_replace(".","",$parm['preco-max']); 
            $base["preco-max"] = str_replace(",","",$parm['preco-max']); 
        endif;
        
        $db_imovel = new App_Model_imovelModel();

        if (!empty($base)):
            $sel_imovel = $db_imovel->listaimovelBusca($base);
            //print_r($sel_imovel);
            if(empty($sel_imovel)):
                $dados['error'] = "<h2 class='text-center mt-2 mb-4 pb-4' style='margin-bottom:100px!important;'>OPS! Não foi possível encontrar sua pesquisa.</h2>";
                $sel_imovel = $db_imovel->listaimovel();
            endif;
            
        else:
            $sel_imovel = $db_imovel->listaimovelBusca($base);
        endif;

        $sel_localidade = $db_imovel->imovelSelecionarLocalidade();

        $dados['tipo'] = $tipo;
        $dados['tipocliente'] = $tipocliente;
        $dados['localidade'] = $sel_localidade;
        $dados['piso'] = $sel_piso;
      
        $model_foto = new App_Model_imovelfotosModel();
        $model_tipocliente = new App_Model_imovelTipoClienteModel();
        
        foreach ($sel_imovel as $imovel):            

            $sel_foto = $model_foto->imovelSelecionar($imovel["D011_Imagem"]);
            $sel_tipo = $model_tipo->imovelSelecionar($imovel["D011_Tipo"]);
            $sel_tipocliente = $model_tipocliente->imovelSelecionar($imovel["D011_Tipocliente"]);
            $sel_piso = $model_piso->imovelSelecionar($imovel["D011_Piso"]);

            if (!empty($sel_piso)):
                $piso = $sel_piso[0]["D011_Titulo"];
            else:
                $piso = "";
            endif;

            $arr_imovel = Array(
                "D011_Uid" => $imovel["D011_Uid"],
                "D011_Titulo" => $imovel["D011_Titulo"],
                "D011_Chave" => $imovel["D011_Chave"],
                "D011_Codigo" => $imovel["D011_Codigo"],
                "D011_Imagem" => $sel_foto[0]["D011_Imagem"],
                "D011_Localidade" => $imovel["D011_Localidade"],
                "D011_Tipo" => $sel_tipo[0]["D011_Titulo"],
                "D011_Tipocliente" => $sel_tipocliente[0]["D011_Titulo"],
                "D011_Valor" => $imovel["D011_Valor"],
                "D011_Valor_promocional" => $imovel["D011_Valor_promoocional"],
                "D011_Quarto" => $imovel["D011_Quarto"],
                "D011_Casabanho" => $imovel["D011_Casabanho"],
                "D011_Area_terreno" => $imovel["D011_Area_terreno"],
                "D011_Energetica" => $imovel["D011_Energetica"],
                "D011_Piso" => $piso,
                "SEO_Slug" => $imovel["SEO_Slug"]
            );
            $imo[] = $arr_imovel;
        endforeach;
        if (!empty($imo)):
            //envia cada registro para o final do array
            array_push($imo, $arr_imovel);
            //retira um elemento no final do array
            array_pop($imo);
            $dados['imoveis'] = $imo;
        else:
            $dados['imoveis'] = array();
        endif; 

        //print_r($dados['imoveis']);
    
        parent::Site("imoveis", $dados);
        self::footer();
    }
    public function imovel()
    {
        self::nav();

        $exp_url = explode("/", $_GET["url"]);
 
        $model_tipo = new App_Model_imovelTipoModel();
        $model_piso = new App_Model_imovelPisoModel();
        $model_tipologia = new App_Model_imovelTipologiaModel();
        $model_condicao = new App_Model_imovelCondicaoModel();
      
        $db_imovel = new App_Model_imovelModel();
        $imovel = $db_imovel->imovelSelecionarSlug($exp_url[3]);

        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;

        $model_foto = new App_Model_imovelfotosModel();
        $model_tipocliente = new App_Model_imovelTipoClienteModel();
         
        $sel_foto = $model_foto->imovelSelecionarFotos($imovel[0]["D011_Uid"]);        
        $sel_tipo = $model_tipo->imovelSelecionar($imovel[0]["D011_Tipo"]);
        $sel_tipocliente = $model_tipocliente->imovelSelecionar($imovel[0]["D011_Tipocliente"]);
        $sel_piso = $model_piso->imovelSelecionar($imovel[0]["D011_Piso"]);
        $sel_tipologia = $model_tipologia->imovelSelecionar($imovel[0]["D011_Tipologia"]);
        $sel_condicao = $model_condicao->imovelSelecionar($imovel[0]["D011_Condicao"]);

        $dados['imovel'] = $imovel;
        $dados['imagem'] = $sel_foto;
        $dados['tipo'] = $sel_tipo;
        $dados['tipocliente'] = $sel_tipocliente;
        $dados['piso'] = $sel_piso;
        $dados['tipologia'] = $sel_tipologia;
        $dados['condicao'] = $sel_condicao;
    
        //CHAMA INDEX BACKEND
        parent::Site("imovel", $dados);
        self::footer();
    }

    /********************************************************************
     *                        BUSCADO     *
     *********************************************************************/
    public function busca()
    {
        self::nav();
        global $start;
        $parm = $start->_params;
        if (isset($parm['ps'])):
            $paginacao = self::paginacao_busca($parm["ps"], "/page/busca/ps/" . $parm['ps'] . "/p", $parm['p']);
            $model = new App_Model_cifracatModel();
            $model_cifra = new App_Model_cifraModel();
            foreach ($paginacao['dados'] as $artista):
                if ($artista['tipo'] == 'cifra'):
                    $sel_cifra = $model_cifra->cifraSelecionar($artista['id']);
                    $sel_artista = $model->cifraSelecionar($sel_cifra[0]['D011_CategoriaSub1']);
                    $sel_genero = $model->cifraSelecionar($sel_cifra[0]['D011_Categoria']);
                    $link = '/page/cifras/genero/' . $sel_genero[0]['D011_Chave'] . '/artista/' . $sel_artista[0]['D011_Chave'] . '/cifra/' . $artista['chave'];
                endif;
                if (empty($link)):
                    $lin = "";
                else:
                    $lin = $link;
                endif;
                $arr = array(
                    'id' => $artista['id'],
                    'chave' => $artista['chave'],
                    'titulo' => $artista['titulo'],
                    'texto' => $artista['texto'],
                    'tipo' => $artista['tipo'],
                    'link' => $lin
                );
                $dad[] = $arr;
            endforeach;
            if (isset($dad)):
                //envia cada registro para o final do array
                array_push($dad, $arr);
                //retira um elemento no final do array
                array_pop($dad);
                $dados['dados'] = $dad;
            else:
                $dados['dados'] = array();
            endif;
        else:
            $paginacao = array();
        endif;
        $config = unserialize(CONFIG_DB);
        $dados['config'] = $config;
        $dados['busca'] = $paginacao;
        //CHAMA INDEX BACKEND
        parent::Site("busca", $dados);
        self::footer();
    }
    /* PAGINACAO */
    public function paginacaoProdutos($tabela, $where = NULL, $order, $link, $pag)
    {
        define("PAGINACAO_TABELA", $tabela);
        // if($where == NULL):
        //   $paginacao = new App_Model_contatoModel();
        //   $rs = $cadastrar->contatoCadastrar($dados);
        //   $sql_noticia = "SELECT * FROM `[d007]noticia` ORDER BY D007_Data DESC";
        // else:
        // // $query = $mysqli->query("SELECT * FROM `[d007]pertence` WHERE D007_Chave = '".$pagper."'");
        // // $rowchave = $query->fetch_assoc();
        // // $sql_noticia = "SELECT * FROM `[d007]noticia` WHERE D007_Pertence = '".$rowchave['D007_Upag']."' ORDER BY D007_Data DESC";
        // endif;
        $nrows = 12;
        $nlinks = 5;
        $arquivo = $link;
        if (empty($pag)) {
            $param = 0;
            $pag = 0;
            $temp = 0;
        } else {
            $temp = $pag;
            $passo1 = $temp - 1;
            $passo2 = $passo1 * $nrows;
            $param = $passo2;
        }
        $paginacao = new App_Model_paginacaoModel();
        $rs = $paginacao->listarPaginacao($where, $order, $param, $nrows);
        //print_r($rs['info']['sqllimit']);
        $rs1 = $rs['info']['sqllimit2'];
        $rs2 = $rs['info']['sqllimit'];
        $totreg = count($rs1);
        $limitreg = count($rs2);
        $reg_final = $param + $limitreg;
        $result_div = $totreg / $nrows;
        $n_inteiro = (int)$result_div;
        if ($n_inteiro < $result_div) {
            $n_paginas = $n_inteiro + 1;
        } else {
            $n_paginas = $result_div;
        }
        $pg_atual = $param / $nrows + 1;
        $pg_anterior = $pg_atual - 1;
        $pg_proxima = $pg_atual + 1;
        $lnk_impressos = 0;
        $finalpag = $n_paginas;
        // $total = $rs2->num_rows;
        $total = $limitreg;
        $dados['dados'] = $rs2;
        $dados['reg_final'] = $reg_final;
        $dados['n_paginas'] = $n_paginas;
        $dados['pg_atual'] = $pg_atual;
        $dados['pg_anterior'] = $pg_anterior;
        $dados['pg_proxima'] = $pg_proxima;
        $dados['lnk_impressos'] = $lnk_impressos;
        $dados['finalpag'] = $finalpag;
        $dados['arquivo'] = $arquivo;
        $dados['pag'] = $pag;
        $dados['nlinks'] = $nlinks;
        $dados['temp'] = $temp;
        $dados['totreg'] = $totreg;
        $dados['total'] = $total;
        return $dados;
    }
    public function paginacao($tabela, $where = NULL, $order, $link, $pag)
    {
        define("PAGINACAO_TABELA", $tabela);
        // if($where == NULL):
        //   $paginacao = new App_Model_contatoModel();
        //   $rs = $cadastrar->contatoCadastrar($dados);
        //   $sql_noticia = "SELECT * FROM `[d007]noticia` ORDER BY D007_Data DESC";
        // else:
        // // $query = $mysqli->query("SELECT * FROM `[d007]pertence` WHERE D007_Chave = '".$pagper."'");
        // // $rowchave = $query->fetch_assoc();
        // // $sql_noticia = "SELECT * FROM `[d007]noticia` WHERE D007_Pertence = '".$rowchave['D007_Upag']."' ORDER BY D007_Data DESC";
        // endif;
        $nrows = 12;
        $nlinks = 5;
        $arquivo = $link;
        if (empty($pag)) {
            $param = 0;
            $pag = 0;
            $temp = 0;
        } else {
            $temp = $pag;
            $passo1 = $temp - 1;
            $passo2 = $passo1 * $nrows;
            $param = $passo2;
        }
        $paginacao = new App_Model_paginacaoModel();
        $rs = $paginacao->listarPaginacao($where, $order, $param, $nrows);
        //print_r($rs['info']['sqllimit']);
        $rs1 = $rs['info']['sqllimit2'];
        $rs2 = $rs['info']['sqllimit'];
        $totreg = count($rs1);
        $limitreg = count($rs2);
        $reg_final = $param + $limitreg;
        $result_div = $totreg / $nrows;
        $n_inteiro = (int)$result_div;
        if ($n_inteiro < $result_div) {
            $n_paginas = $n_inteiro + 1;
        } else {
            $n_paginas = $result_div;
        }
        $pg_atual = $param / $nrows + 1;
        $pg_anterior = $pg_atual - 1;
        $pg_proxima = $pg_atual + 1;
        $lnk_impressos = 0;
        $finalpag = $n_paginas;
        // $total = $rs2->num_rows;
        $total = $limitreg;
        $dados['dados'] = $rs2;
        $dados['reg_final'] = $reg_final;
        $dados['n_paginas'] = $n_paginas;
        $dados['pg_atual'] = $pg_atual;
        $dados['pg_anterior'] = $pg_anterior;
        $dados['pg_proxima'] = $pg_proxima;
        $dados['lnk_impressos'] = $lnk_impressos;
        $dados['finalpag'] = $finalpag;
        $dados['arquivo'] = $arquivo;
        $dados['pag'] = $pag;
        $dados['nlinks'] = $nlinks;
        $dados['temp'] = $temp;
        $dados['totreg'] = $totreg;
        $dados['total'] = $total;
        return $dados;
    }
    /* PAGINACAO */
    public function paginacao_busca($pesq, $link, $pag)
    {
        define("PAGINACAO_TABELA", "");
        $nrows = 10;
        $nlinks = 5;
        $arquivo = $link;
        if (empty($pag)) {
            $param = 0;
            $pag = 0;
            $temp = 0;
        } else {
            $temp = $pag;
            $passo1 = $temp - 1;
            $passo2 = $passo1 * $nrows;
            $param = $passo2;
        }
        $paginacao = new App_Model_paginacaoModel();
        $rs = $paginacao->listarPaginacaobusca($pesq, $param, $nrows);
        $rs1 = $rs['info']['sqllimit2'];
        $rs2 = $rs['info']['sqllimit'];
        $totreg = count($rs1);
        $limitreg = count($rs2);
        $reg_final = $param + $limitreg;
        $result_div = $totreg / $nrows;
        $n_inteiro = (int)$result_div;
        if ($n_inteiro < $result_div) {
            $n_paginas = $n_inteiro + 1;
        } else {
            $n_paginas = $result_div;
        }
        $pg_atual = $param / $nrows + 1;
        $pg_anterior = $pg_atual - 1;
        $pg_proxima = $pg_atual + 1;
        $lnk_impressos = 0;
        $finalpag = $n_paginas;
        // $total = $rs2->num_rows;
        $total = $limitreg;
        $dados['dados'] = $rs2;
        $dados['reg_final'] = $reg_final;
        $dados['n_paginas'] = $n_paginas;
        $dados['pg_atual'] = $pg_atual;
        $dados['pg_anterior'] = $pg_anterior;
        $dados['pg_proxima'] = $pg_proxima;
        $dados['lnk_impressos'] = $lnk_impressos;
        $dados['finalpag'] = $finalpag;
        $dados['arquivo'] = $arquivo;
        $dados['pag'] = $pag;
        $dados['nlinks'] = $nlinks;
        $dados['temp'] = $temp;
        $dados['totreg'] = $totreg;
        $dados['total'] = $total;
        return $dados;
    }


}