<?php
class App_Model_followupModel extends App_Model{
  
  public $_tabela = "followup";

  public function listaFollowup(){

      $parm = " ORDER BY foll_data DESC";
      return $this->readorder($parm);
  }

  public function listaFollowup2(){

      if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
        $parm = " ORDER BY foll_nome DESC";
      else:
        $parm = " WHERE vend_id = ".$_SESSION['vendedor_acesso']." ORDER BY foll_nome DESC";
      endif;
      return $this->readorder($parm);
  }

   public function listaFollowupLimit(){

      if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
        $parm = "ORDER BY foll_data, foll_nome DESC LIMIT 10";
      else:
        $parm = " WHERE vend_id = ".$_SESSION['vendedor_acesso']." ORDER BY foll_data, foll_nome DESC LIMIT 10";
      endif;   
      return $this->readorder($parm);
  }

  public function selecionaColunas(){
    return $this->showcolunns();
  }

  public function parmFollowup($campo, $where,$order){ 
      return $this->readesp($campo,$where,$order);    
  }

  public function followupRegistar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $referencia = rand(10000, 9999999);    
      $arr = array( 
         'foll_referencia' => $referencia,
         'cli_id' => $dados['id'],  
         'foll_tipo' => $dados['tipo'],           
         'foll_titulo' => $dados['titulo'], 
         'foll_texto' => trim($dados['texto'])
      );          
      $return = $this->insert($arr);
    endif;
    return $return;
  }

  public function followupUltimoRg(){
     $parm = "ORDER BY foll_id DESC LIMIT 1";     
     return $this->readorder($parm);
  }


  public function followupCsv($nome, $morada, $cp, $distrito, $localidade, $telefone, $area, $email, $vendedor){
    if(empty($nome)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($nome);
      $arr = array( 
         'foll_referencia' => 0,
         'foll_nome' => str_replace("/", "|", trim($nome)),  
         'foll_chave' => strtolower($chave), 
         'foll_tipo' => 0,
         'foll_area_negocio' => trim($area),
         'foll_rua' => str_replace("/", "|", trim($morada)),
         'foll_numero' => trim($dados['numero']),
         'foll_andar' => trim($dados['andar']),
         'foll_cp' => trim($cp),
         'foll_localidade' => str_replace("/", "|", trim($localidade)),
         'foll_distrito' => trim($distrito),
         'foll_telefone' => str_replace("/", "|", trim($telefone)),
         'foll_email' => str_replace("/", "|", trim($email)),
         'foll_url' => "",        
         'foll_descricao' => "", 
         'foll_status' => 'sim', 
         'vend_id' => str_replace("/", "|", $vendedor)
      );
      $return = $this->insert($arr);
    endif;
    return $return;
  }

  public function followupSelecionar($id){
     
     $parm = "foll_id = ".$id;      
     return $this->read($parm);
     
  }

  public function followupSelecionarRef($ref){
     
     $parm = "foll_referencia = ".$ref;      
     return $this->read($parm);
     
  }

  public function followupSelecionarCliente($cliente){
     
     $parm = " WHERE cli_id = ".$cliente." ORDER BY foll_data DESC";      
     return $this->readorder($parm);
     
  }

  public function followupChave($chave){
     $parm = "foll_id = '".$chave."'";     
     return $this->read($parm);
  }

  public function followupDeletar($id){
     $dados_id = "foll_id = ".$id;

     return $this->delete($dados_id,$id);
  }

  public function followupComplete($ref){

      $id = "foll_referencia=". $ref; 

      $arr = array( 
         'foll_finalizado' => 1
      );
   
    return $this->update($arr,$id);
  }

  public function followupAlteracao(array $dados){

    $id = "foll_id=".$dados['id']; 
    $chave = parent::removerAcentos($dados['nome']);

      $arr = array( 
         'foll_saudacao' => $dados['saudacao'],  
         'foll_nome' =>  str_replace("/", "|", trim($dados['nome'])),  
         'foll_chave' => strtolower($chave),          
         'foll_telefone' => trim($dados['telefone']),
         'foll_email' => trim($dados['email']),
         'foll_nascimento' => implode("-", array_reverse( explode("-", $dados['nascimento']))),
         'foll_nif' => $dados['nif'],
         'foll_cc_numero' => $dados['cc_numero'],
         'foll_cc_validade' => implode("-", array_reverse( explode("-", $dados['cc_validade']))),        
         'foll_tipo' => $dados['tipo'],
         'foll_angariacao' => $dados['angariacao'],
         'foll_fonte' => $dados['fonte'],
         'foll_morada' => trim($dados['morada']),
         'foll_numero' => trim($dados['numero']),
         'foll_andar' => trim($dados['andar']),
         'foll_cp' => trim($dados['cp']),
         'foll_localidade' => trim($dados['localidade']),
         'foll_distrito' => trim($dados['distrito']),
         'foll_descricao' => trim($dados['descricao']), 
         'foll_status' => $dados['status']
      );     

    //return $arr;        
    return $this->update($arr,$id);
  }


  /* PESQUISAR */
  public function followupBuscar($dados){

    $query = "";

    if(!empty( $dados )):

      foreach($dados as $item):

        if( !empty( $item["dtstart"] ) && !empty( $item["dtend"] )):

          $query .= " date(foll_data) BETWEEN '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND '".implode("-", array_reverse( explode("-", $item["dtend"])))."' AND";
        endif;

        if( !empty( $item["dtstart"] ) && empty( $item["dtend"] )):

          $query .= " date(foll_data) = '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND";
        endif;
      
        if( !empty( $item["vend_id"] ) ):

          $query .= " vend_id = ".$item["vend_id"]." AND";
        endif;

        if( !empty( $item["foll_area"] ) ):
          //$area = explode(",", $item["foll_area"]);
          $query .= " foll_area_negocio IN(".$item["foll_area"].") AND";
        endif;

        if( !empty( $item["foll_tipo"] ) ):

          $query .= " foll_tipo IN(".$item["foll_tipo"].") AND";
        endif;

        if( !empty( $item["foll_distrito"] ) ):

          $query .= " foll_distrito IN(".$item["foll_distrito"].") AND";
        endif;

        if( !empty( $item["campo"] ) ):
                  
          // Aqui você pode juntar vários campos no concat.
          $campo = ' CONCAT( foll_nome, foll_localidade )';
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
        $q = $this->readesp("*",$parm," ORDER BY foll_nome ASC");
      else:
        $q = $this->readesp("*",$parm," ORDER BY foll_nome ASC");

        //$q = $this->readesp("*",$parm." AND vend_id = ".$_SESSION['vendedor_acesso'].""," ORDER BY foll_nome ASC");
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


    public function followupBuscarXls($dados,$colunas){

      $query = "";

      if(!empty( $dados )):

        foreach($dados as $item):

          if( !empty( $item["dtstart"] ) && !empty( $item["dtend"] )):

            $query .= " date(foll_data) BETWEEN '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND '".implode("-", array_reverse( explode("-", $item["dtend"])))."' AND";
          endif;

          if( !empty( $item["dtstart"] ) && empty( $item["dtend"] )):

            $query .= " date(foll_data) = '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND";
          endif;
        
          if( !empty( $item["vend_id"] ) ):

            $query .= " vend_id = ".$item["vend_id"]." AND";
          endif;

          if( !empty( $item["foll_area"] ) ):
            //$area = explode(",", $item["foll_area"]);
            $query .= " foll_area_negocio IN(".$item["foll_area"].") AND";
          endif;

          if( !empty( $item["foll_tipo"] ) ):

            $query .= " foll_tipo IN(".$item["foll_tipo"].") AND";
          endif;

          if( !empty( $item["foll_distrito"] ) ):

            $query .= " foll_distrito IN(".$item["foll_distrito"].") AND";
          endif;

          if( !empty( $item["campo"] ) ):
                    
            // Aqui você pode juntar vários campos no concat.
            $campo = ' CONCAT( foll_nome, foll_localidade )';
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
          $col = "foll_id".$str_colunas;
        else:
          $col = "*";
        endif; 

       $q = $this->readesp($col,$parm," ORDER BY foll_nome ASC");
       // else:
       // $q = $this->readesp($col,$parm," ORDER BY foll_nome ASC");
       // $q = $this->readesp($col,$parm." AND vend_id = ".$_SESSION['vendedor_acesso'].""," ORDER BY foll_nome ASC");
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