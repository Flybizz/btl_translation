<?php
class App_Model_clienteModel extends App_Model{

  public $_tabela = "btl_cliente";

  public function listaCliente(){

      if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
        $parm = " ORDER BY cli_nome ASC";
      else:
        $parm = " WHERE usu_id = ".$_SESSION['id_usuario']." ORDER BY cli_nome ASC";
      endif;
      return $this->readorder($parm);
  }

  public function listaCliente2(){
      if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
        $parm = " ORDER BY cli_nome DESC";
      else:
        $parm = " WHERE usu_id = ".$_SESSION['id_usuario']." ORDER BY cli_nome DESC";
      endif;
      return $this->readorder($parm);
  }

  public function listaClienteLimit(){
      if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
        $parm = " ORDER BY cli_data DESC LIMIT 100";
      else:
        $parm = "WHERE usu_id = ".$_SESSION['id_usuario']." ORDER BY cli_data DESC LIMIT 100";
      endif;
      return $this->readorder($parm);
  }

  public function clienteSelecionarRef($ref){
      if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
        $parm = " WHERE cli_referencia = ".$ref." ORDER BY cli_nome ASC";
      else:
        $parm = "WHERE usu_id = ".$_SESSION['id_usuario']." AND cli_referencia = ".$ref." ORDER BY cli_nome ASC";
      endif;

      return $this->readorder($parm);
  }

  public function clienteTimeline($id){
      $parm = " q.cliente = ".$id." ORDER BY q.data DESC";
      return $this->readordertimeline($parm);
  }

  public function selecionaColunas(){
    return $this->showcolunns();
  }

  public function parmCliente($campo, $where,$order){
      return $this->readesp($campo,$where,$order);
  }


  public function clienteRegistar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      
      $image = (!empty($dados['img']))? $dados['img'] : "";
      
      $chave = parent::removerAcentos($dados['nome']);
      $arr = array(
        'cli_saudacao' => trim($dados['saudacao']),
        'cli_referencia' => trim($dados['referencia']),
        'cli_nome' => trim($dados['nome']),
        'cli_chave' => strtolower($chave),
        'cli_tipo' => trim($dados['tipo']),
        'cli_area_negocio' => trim($dados['area']),
        'cli_produto' => trim($dados['produto']),
        'cli_rua' => trim($dados['rua']),
        'cli_numero' => trim($dados['numero']),
        'cli_andar' => trim($dados['andar']),
        'cli_cp' => trim($dados['cp']),
        'cli_localidade' => trim($dados['localidade']),
        'cli_distrito' => trim($dados['distrito']),
        'cli_telefone' => trim($dados['telefone']),
        'cli_email' => trim($dados['email']),
        'cli_url' => trim($dados['url']),        
        'cli_descricao' => trim($dados['descricao']),
        'cli_status' => $dados['status'],
        'usu_id' => $dados['vendedor'],
        'cli_associado' => trim($dados['associado']),
        'cli_rgpd' => trim($dados['rgpd']),
        'cli_nif' => trim($dados['nif']),      
        'cli_imagem' => $image
      );
      $return = $this->insert($arr);
    endif;
    return $return;
  }

  public function pesquisar_ajax($term){

    if(empty($term)) return false;
    

    if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ){
      $parm = "cli_nome LIKE '%".$term."%'";
    }
    else{
      $parm = "cli_nome LIKE '%".$term."%' AND usu_id = '{$_SESSION['id_usuario']}'";
    }

    return $this->read($parm);
  }

  public function clienteUltimoRg(){
     $parm = "ORDER BY cli_id DESC LIMIT 1";
     return $this->readorder($parm);
  }

  public function clienteCsv($vendedor, $cpf, $nome, $tipo, $area, $endereco, $cidade, $estado, $telefone, $email, $produto, $mapa, $website, $locacao, $obs, $classificacao){
    if(empty($nome)):
      $return = "Erro: Dados vazios.";
    else:
      $ref = rand(100, 999999);
      $chave = parent::removerAcentos($nome);
      $arr = array(
          'cli_saudacao' => 0,
          'cli_referencia' => $ref,
          'cli_nome' => str_replace("/", "|", trim($nome)),
          'cli_chave' => strtolower($chave),
          'cli_tipo' => $tipo,
          'cli_area_negocio' => $area,
          'cli_produto' => trim($produto),
          'cli_rua' => str_replace("/", "|", trim($endereco)),
          'cli_numero' => '',
          'cli_andar' => '',
          'cli_cp' => '',
          'cli_localidade' => str_replace("/", "|", trim($cidade)),
          'cli_distrito' => trim($estado),
          'cli_telefone' => str_replace("/", "|", trim($telefone)),
          'cli_email' => str_replace("/", "|", trim($email)),
          'cli_url' => $website,
          'cli_descricao' => $obs,
          'cli_status' => 'sim',
          'usu_id' => $vendedor,
          'cli_associado' => '',
          'cli_rgpd' => '',
          'cli_nif' => $cpf,
          'cli_google_map' => $mapa,
          'cli_imagem' => "",
          'cli_aluguel' => $locacao,
          'cli_classificacao' => $classificacao
      );
      //print_r($arr);
      $return = $this->insert($arr);
    endif;
    return $return;
  }

  public function clienteSelecionar($id){

     $parm = "cli_id = ".$id;
     return $this->read($parm);

  }

  public function clienteChave($chave){
     $parm = "cli_id = '".$chave."'";
     return $this->read($parm);
  }

  public function clienteDeletarIMG($dados){
    $id = "cli_id = ".$dados;
    $arr = array(
        'cli_imagem' => ""
    );
    return $this->update($arr,$id);
  }

  public function clienteDeletar($id){
     $dados_id = "cli_id = ".$id;

     return $this->delete($dados_id,$id);
  }

  public function clienteAlteracao(array $dados){

    $id = "cli_id=".$dados['id'];

    if(!empty($dados['img'])):
      $image = $dados['img'];
    else:
      $image = "";
    endif;

    $chave = parent::removerAcentos($dados['nome']);
    
    //if($_SESSION["nivel_acesso"] <= 2  && $_SESSION["nivel_acesso"] >= 1):
    $arr = array(
      'cli_saudacao' => trim($dados['saudacao']),
      'cli_referencia' => trim($dados['referencia']),
      'cli_nome' => trim($dados['nome']),
      'cli_chave' => strtolower($chave),
      'cli_tipo' => trim($dados['tipo']),
      'cli_area_negocio' => trim($dados['area']),
      'cli_produto' => trim($dados['produto']),
      'cli_rua' => trim($dados['rua']),
      'cli_numero' => trim($dados['numero']),
      'cli_andar' => trim($dados['andar']),
      'cli_cp' => trim($dados['cp']),
      'cli_localidade' => trim($dados['localidade']),
      'cli_distrito' => trim($dados['distrito']),
      'cli_telefone' => trim($dados['telefone']),
      'cli_email' => trim($dados['email']),
      'cli_url' => trim($dados['url']),      
      'cli_descricao' => trim($dados['descricao']),
      'cli_status' => $dados['status'],
      'usu_id' => $dados['vendedor'],
      'cli_associado' => trim($dados['associado']),
      'cli_rgpd' => trim($dados['rgpd']),
      'cli_nif' => trim($dados['nif']),      
      'cli_imagem' => $image,
      'cli_google_map' => trim($dados['google_map'])
    );  

    //return $arr;
    return $this->update($arr,$id);
  }

  public function clienteSClassification(){   
    if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):

      return $this->sqlquery("SELECT cli_id as cont_id, cli_referencia as ref, cli_nome as cont_nome, cli_email as cont_email, cli_telefone as cont_telemovel, (SELECT COUNT(*) FROM btl_contato WHERE cli_id = bc.cli_id AND cont_lead_status = 0) AS total_sem_lead, (SELECT COUNT(*) FROM btl_contato WHERE cli_id = bc.cli_id) AS total_contactos FROM btl_cliente bc HAVING (total_sem_lead > 0 OR (total_contactos = 0 AND total_sem_lead = 0)) AND total_sem_lead >= total_contactos ORDER BY bc.cli_id");     

    else:

      return $this->sqlquery("SELECT cli_id as cont_id, cli_referencia as ref, cli_nome as cont_nome, cli_email as cont_email, cli_telefone as cont_telemovel, (SELECT COUNT(*) FROM btl_contato WHERE cli_id = bc.cli_id AND cont_lead_status = 0) AS total_sem_lead, (SELECT COUNT(*) FROM btl_contato WHERE cli_id = bc.cli_id) AS total_contactos FROM btl_cliente bc WHERE usu_id = {$_SESSION['id_usuario']} HAVING (total_sem_lead > 0 OR (total_contactos = 0 AND total_sem_lead = 0)) AND total_sem_lead >= total_contactos ORDER BY bc.cli_id");     

    endif;
  }

  /* PESQUISAR */
  public function clienteBuscar($dados){

    $query = "";

    if(!empty( $dados )):

      foreach($dados as $item):

        if( !empty( $item["dtstart"] ) && !empty( $item["dtend"] )):

          $query .= " date(cli_data) BETWEEN '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND '".implode("-", array_reverse( explode("-", $item["dtend"])))."' AND";
        endif;

        if( !empty( $item["dtstart"] ) && empty( $item["dtend"] )):

          $query .= " date(cli_data) = '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND";
        endif;

        if( !empty( $item["usu_id"] ) ):

          $query .= " usu_id = ".$item["usu_id"]." AND";
        endif;

        $pattern = ',';

        if( !empty( $item["cli_area"] ) ):

          if (strstr($item["cli_area"], $pattern)) {
            $r_area = explode(",", $item["cli_area"]);
            $in = "";
            foreach ($r_area as $ar) {
              $in .= " FIND_IN_SET('".$ar."', cli_area_negocio) AND";
            }
            $r_in = substr($in,0,-3);
            $query .= " ".$r_in." AND";
          } else {
            $query .= " FIND_IN_SET(".$item["cli_area"].", cli_area_negocio) AND";
          }

        endif;

        if( !empty( $item["cli_produto"] ) ):

          if (strstr($item["cli_produto"], $pattern)) {
            $r_prod = explode(",", $item["cli_produto"]);
            $in = "";
            foreach ($r_prod as $pd) {
              $in .= " FIND_IN_SET('".$pd."', cli_produto) AND";
            }
            $r_in = substr($in,0,-3);
            $query .= " ".$r_in." AND";
          } else {
            $query .= " FIND_IN_SET(".$item["cli_produto"].", cli_produto) AND";
          }

        endif;

        if( !empty( $item["cli_tipo"] ) ):

          if (strstr($item["cli_tipo"], $pattern)) {
            $r_tipo = explode(",", $item["cli_tipo"]);
            $in = "";
            foreach ($r_tipo as $t) {
              $in .= " FIND_IN_SET('".$t."', cli_tipo) AND";
            }
            $r_in = substr($in,0,-3);
            $query .= " ".$r_in." AND";
          } else {
            $query .= " FIND_IN_SET(".$item["cli_tipo"].", cli_tipo) AND";
          }

        endif;

        if( !empty( $item["cli_distrito"] ) ):

          if (strstr($item["cli_distrito"], $pattern)) {
            $r_distrito = explode(",", $item["cli_distrito"]);
            $in = "";
            foreach ($r_distrito as $dis) {
              $in .= " FIND_IN_SET('".$dis."', cli_distrito) AND";
            }
            $r_in = substr($in,0,-3);
            $query .= " ".$r_in." AND";
          } else {
            $query .= " FIND_IN_SET(".$item["cli_distrito"].", cli_distrito) AND";
          }

        endif;

        if( !empty( $item["campo"] ) ):

          // Aqui você pode juntar vários campos no concat.
          $campo = ' CONCAT( cli_nome, cli_localidade )';
          // Ou usar um só, mas nesse caso talvez compense um LIKE tradicional
          // $campo = 'title';

          $palavras = explode( ' ', $item["campo"] ); // dividindo as palavras pelo espaço
          $palavras = array_filter($palavras); // eliminando ítens vazios

          $where = '';
          $cola = '';

          foreach ($palavras as $palavra) {
            $palavra = trim($palavra); //Removendo espaços em branco
            // $palavra = mysql_real_escape_string($palavra); //Precisa da conexao com o banco!
            $suaPesquisa = self::trataPesquisa($palavra);
            $where .= $cola.$campo.' REGEXP "'.$suaPesquisa.'"';
            $cola = 'AND ';
          }

          $query .= $where." AND";

        endif;
      endforeach;

      $parm = substr($query,0,-3);
      //echo $parm;

      if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
        $q = $this->readesp("*",$parm," ORDER BY cli_nome ASC");
      else:
        $q = $this->readesp("*",$parm," ORDER BY cli_nome ASC");

        //$q = $this->readesp("*",$parm." AND vend_id = ".$_SESSION['vendedor_acesso'].""," ORDER BY cli_nome ASC");
      endif;

      return $q;

    else:

      echo "OPS! Não foi possível concluir sua pesquisa.";
    endif;

    // if(isset($base)):
    //   array_push($base, $query);
    //   array_pop($base);
    // endif;

  }


    public function clienteBuscarXls($dados,$colunas){

      $query = "";

      if(!empty( $dados )):

        foreach($dados as $item):

          if( !empty( $item["dtstart"] ) && !empty( $item["dtend"] )):

            $query .= " date(cli_data) BETWEEN '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND '".implode("-", array_reverse( explode("-", $item["dtend"])))."' AND";
          endif;

          if( !empty( $item["dtstart"] ) && empty( $item["dtend"] )):

            $query .= " date(cli_data) = '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND";
          endif;

          if( !empty( $item["usu_id"] ) ):

            $query .= " usu_id = ".$item["usu_id"]." AND";
          endif;

          $pattern = ',';

          if( !empty( $item["cli_area"] ) ):

            if (strstr($item["cli_area"], $pattern)) {
              $r_area = explode(",", $item["cli_area"]);
              $in = "";
              foreach ($r_area as $ar) {
                $in .= " FIND_IN_SET('".$ar."', cli_area_negocio) AND";
              }
              $r_in = substr($in,0,-3);
              $query .= " ".$r_in." AND";
            } else {
              $query .= " FIND_IN_SET(".$item["cli_area"].", cli_area_negocio) AND";
            }

          endif;

          if( !empty( $item["cli_produto"] ) ):

            if (strstr($item["cli_produto"], $pattern)) {
              $r_prod = explode(",", $item["cli_produto"]);
              $in = "";
              foreach ($r_prod as $pd) {
                $in .= " FIND_IN_SET('".$pd."', cli_produto) AND";
              }
              $r_in = substr($in,0,-3);
              $query .= " ".$r_in." AND";
            } else {
              $query .= " FIND_IN_SET(".$item["cli_produto"].", cli_produto) AND";
            }

          endif;

          if( !empty( $item["cli_tipo"] ) ):

            if (strstr($item["cli_tipo"], $pattern)) {
              $r_tipo = explode(",", $item["cli_tipo"]);
              $in = "";
              foreach ($r_tipo as $t) {
                $in .= " FIND_IN_SET('".$t."', cli_tipo) AND";
              }
              $r_in = substr($in,0,-3);
              $query .= " ".$r_in." AND";
            } else {
              $query .= " FIND_IN_SET(".$item["cli_tipo"].", cli_tipo) AND";
            }

          endif;

          if( !empty( $item["cli_distrito"] ) ):

            if (strstr($item["cli_distrito"], $pattern)) {
              $r_distrito = explode(",", $item["cli_distrito"]);
              $in = "";
              foreach ($r_distrito as $dis) {
                $in .= " FIND_IN_SET('".$dis."', cli_distrito) AND";
              }
              $r_in = substr($in,0,-3);
              $query .= " ".$r_in." AND";
            } else {
              $query .= " FIND_IN_SET(".$item["cli_distrito"].", cli_distrito) AND";
            }

          endif;

          if( !empty( $item["campo"] ) ):

            // Aqui você pode juntar vários campos no concat.
            $campo = ' CONCAT( cli_nome, cli_localidade )';
            // Ou usar um só, mas nesse caso talvez compense um LIKE tradicional
            // $campo = 'title';

            $palavras = explode( ' ', $item["campo"] ); // dividindo as palavras pelo espaço
            $palavras = array_filter($palavras); // eliminando ítens vazios

            $where = '';
            $cola = '';

            foreach ($palavras as $palavra) {
              $palavra = trim($palavra); //Removendo espaços em branco
              // $palavra = mysql_real_escape_string($palavra); //Precisa da conexao com o banco!
              $suaPesquisa = self::trataPesquisa($palavra);
              $where .= $cola.$campo.' REGEXP "'.$suaPesquisa.'"';
              $cola = 'AND ';
            }

            $query .= $where." AND";

          endif;
        endforeach;

        $parm = substr($query,0,-3);

        //if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):

        $exp = explode(",", $colunas);

        if (in_array("contato", $exp)):

          $exp = array_diff($exp, array("contato"));
          $imp = implode(",", $exp);
          if(!empty($exp)):
            $str_colunas = ",".$imp;
          else:
            $str_colunas = $imp;
          endif;

        else:
          $str_colunas = ",".$colunas;
        endif;

        if(!empty($colunas)):
          $col = "cli_id".$str_colunas;
        else:
          $col = "*";
        endif;

       $q = $this->readesp($col,$parm," ORDER BY cli_nome ASC");
       // else:
       // $q = $this->readesp($col,$parm," ORDER BY cli_nome ASC");
       // $q = $this->readesp($col,$parm." AND vend_id = ".$_SESSION['vendedor_acesso'].""," ORDER BY cli_nome ASC");
       // endif;

       return $q;

      else:

        echo "OPS! Não foi possível concluir sua pesquisa.";
      endif;

      // if(isset($base)):
      //   array_push($base, $query);
      //   array_pop($base);
      // endif;

    }

    public function trataPesquisa($str) {

      //Array de acentos
      $map = array(
        'á' => 'a',
        'à' => 'a',
        'ã' => 'a',
        'â' => 'a',
        'é' => 'e',
        'ê' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ú' => 'u',
        'ü' => 'u',
        'ç' => 'c',
        'Á' => 'A',
        'À' => 'A',
        'Ã' => 'A',
        'Â' => 'A',
        'É' => 'E',
        'Ê' => 'E',
        'Í' => 'I',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ú' => 'U',
        'Ü' => 'U',
        'Ç' => 'C'
      );

      //Array para REGEXP
      $regpx = array(
        'a' => '(a|á|&aacute;|à|&agrave;|â|&acirc;|ã|&atilde;)',
        'A' => '(a|á|&aacute;|à|&agrave;|â|&acirc;|ã|&atilde;)',
        'e' => '(e|é|&eacute;|è|&egrave;|ê|&ecirc;)',
        'E' => '(e|é|&eacute;|è|&egrave;|ê|&ecirc;)',
        'i' => '(i|í|&iacute;|ì|&igrave;|î|&icirc;)',
        'I' => '(i|í|&iacute;|ì|&igrave;|î|&icirc;)',
        'o' => '(o|ó|&oacute;|ò|&ograve;|ô|&ocirc;|õ|&otilde;)',
        'O' => '(o|ó|&oacute;|ò|&ograve;|ô|&ocirc;|õ|&otilde;)',
        'u' => '(u|ú|&uacute;|ù|&ugrave;|û|&ucirc;)',
        'U' => '(u|ú|&uacute;|ù|&ugrave;|û|&ucirc;)',
        'ç' => '(c|ç|&#231;)',
        'c' => '(c|ç|&#231;)'
      );

      //Substituindo acentos
      $str = strtr($str, $map);

      //Substituindo vogais e ç para REGEXP
      $str = strtr($str, $regpx);

      //Substituindo espaços em branco
      $str = str_replace(' ', '|', $str);

      return ($str);
    }

}
