<?php
class App_Model_tarefaModel extends App_Model{
  
  public $_tabela = "tarefas";

  public function listaTarefa(){

      $parm = " ORDER BY tar_data DESC";
      return $this->readorder($parm);
  }

  public function tarefaComplete($ref){

    $id = "tar_referencia=". $ref; 

    $arr = array( 
       'tar_finalizado' => 1
    );
 
  return $this->update($arr,$id);
}

  public function listaTarefa2(){

      if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
        $parm = " ORDER BY tar_nome DESC";
      else:
        $parm = " WHERE vend_id = ".$_SESSION['vendedor_acesso']." ORDER BY tar_nome DESC";
      endif;
      return $this->readorder($parm);
  }

  public function lista_pendentes(){
 
    $permission_sql = ($_SESSION["nivel_acesso"] != "1") ? " AND c.usu_id = {$_SESSION["id_usuario"]}" : "";

    $sql = "SELECT tar_notificacao_enviada AS notificacao_enviada, tar_finalizado AS finalizado, u.usu_nome, u.usu_email, tar_referencia AS referencia, tarefas.cli_id, c.cli_nome, c.cli_referencia, tar_tipo AS tipo, tar_titulo AS titulo, tar_dtstart AS dtstart, tar_dtend AS dtend, tar_hrstart AS hrstart, tar_hrend AS hrend, tar_texto AS texto, tar_prioridade AS prioridade, CONCAT(tar_dtstart, ' ',tar_hrstart) AS `data`, google_event_id from tarefas 
    INNER JOIN btl_cliente c ON c.cli_id = tarefas.cli_id
    INNER JOIN usuarios u ON u.usu_id = c.usu_id
    WHERE  tar_finalizado <> 1 {$permission_sql}
        UNION ALL
    SELECT foll_notificacao_enviada AS notificacao_enviada, foll_finalizado AS finalizado, u.usu_nome, u.usu_email, foll_referencia AS referencia, followup.cli_id, c.cli_nome, c.cli_referencia, 'follow-up' AS tipo, foll_titulo AS titulo, '' AS dtstart, '' AS dtend, '' AS hrstart, '' AS hrend, foll_texto AS texto, '' AS prioridade, foll_data AS `data`, '' google_event_id from followup
    INNER JOIN btl_cliente c ON c.cli_id = followup.cli_id
    INNER JOIN usuarios u ON u.usu_id = c.usu_id
    WHERE  foll_finalizado <> 1 {$permission_sql}
        ORDER BY `data` ASC";

    

    $query = $this->query($sql);
    $result = $query->fetchAll(PDO::FETCH_OBJ);

    return !empty($result) ? $result : false;

  }

   public function listaTarefaLimit(){

      if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
        $parm = "ORDER BY tar_data, tar_nome DESC LIMIT 10";
      else:
        $parm = " WHERE vend_id = ".$_SESSION['vendedor_acesso']." ORDER BY tar_data, tar_nome DESC LIMIT 10";
      endif;   
      return $this->readorder($parm);
  }

  public function listaTarefasDashboardFiltradas(){

    $permission_sql = ($_SESSION["nivel_acesso"] != "1") ? " AND c.usu_id = {$_SESSION["id_usuario"]}" : "";

    $sql = "SELECT tar_referencia AS referencia, tarefas.cli_id, c.cli_nome, c.cli_referencia, tar_tipo AS tipo, tar_titulo AS titulo, tar_dtstart AS dtstart, tar_dtend AS dtend, tar_hrstart AS hrstart, tar_hrend AS hrend, tar_texto AS texto, tar_prioridade AS prioridade, CONCAT(tar_dtstart, ' ',tar_hrstart) AS `data`, google_event_id from tarefas
    INNER JOIN btl_cliente c ON c.cli_id = tarefas.cli_id 
    WHERE tarefas.tar_finalizado <> 1 {$permission_sql}
        UNION ALL
        SELECT foll_referencia AS referencia, followup.cli_id, c.cli_nome, c.cli_referencia, 'follow-up' AS tipo, foll_titulo AS titulo, '' AS dtstart, '' AS dtend, '' AS hrstart, '' AS hrend, foll_texto AS texto, '' AS prioridade, foll_data AS `data`, '' google_event_id from followup
    INNER JOIN btl_cliente c ON c.cli_id = followup.cli_id
    WHERE followup.foll_finalizado <> 1 {$permission_sql}
        ORDER BY `data` ASC";

    $result = $this->sqlquery($sql);

    //filtrar por past / future
    if(!empty($result)){

      foreach($result as $notification){

        $event_date = new DateTime($notification["data"]);

        if( time() >= strtotime($notification["data"]) )
          $past_notifications[] = $notification;        
        else
          $future_notifications[] =  $notification;
      }

      $output = array(
        "past" => @$past_notifications,
        "future" => @$future_notifications
      );

    }

    return !empty($output) ? $output : false;

  }

  //inclui também eventos
  public function listaTarefasDashboard(){

    $permission_sql = ($_SESSION["nivel_acesso"] != "1") ? " AND c.usu_id = {$_SESSION["id_usuario"]}" : "";

    $sql = "SELECT tar_referencia AS referencia, tarefas.cli_id, c.cli_nome, c.cli_referencia, tar_tipo AS tipo, tar_titulo AS titulo, tar_dtstart AS dtstart, tar_dtend AS dtend, tar_hrstart AS hrstart, tar_hrend AS hrend, tar_texto AS texto, tar_prioridade AS prioridade, CONCAT(tar_dtstart, ' ',tar_hrstart) AS `data`, google_event_id from tarefas
    INNER JOIN btl_cliente c ON c.cli_id = tarefas.cli_id 
    WHERE tarefas.tar_finalizado <> 1 {$permission_sql}
        UNION ALL
        SELECT foll_referencia AS referencia, followup.cli_id, c.cli_nome, c.cli_referencia, 'follow-up' AS tipo, foll_titulo AS titulo, '' AS dtstart, '' AS dtend, '' AS hrstart, '' AS hrend, foll_texto AS texto, '' AS prioridade, foll_data AS `data`, '' google_event_id from followup
    INNER JOIN btl_cliente c ON c.cli_id = followup.cli_id
    WHERE followup.foll_finalizado <> 1 {$permission_sql}
        ORDER BY `data` ASC";

    $result = $this->sqlquery($sql);
    return !empty($result) ? $result : false;
  }

  public function listaResumoDashboard(){

    $permission_sql = ($_SESSION["nivel_acesso"] != "1") ? " AND c.usu_id = {$_SESSION["id_usuario"]}" : "";

    $sql = "SELECT usu_nome, tar_referencia AS referencia, tarefas.cli_id, c.cli_nome, c.cli_referencia, tar_tipo AS tipo, tar_titulo AS titulo, tar_dtstart AS dtstart, tar_dtend AS dtend, tar_hrstart AS hrstart, tar_hrend AS hrend, tar_texto AS texto, tar_prioridade AS prioridade, CONCAT(tar_dtstart, ' ',tar_hrstart) AS `data`, google_event_id from tarefas
    INNER JOIN btl_cliente c ON c.cli_id = tarefas.cli_id 
    INNER JOIN usuarios u ON u.usu_id = c.usu_id
    WHERE tarefas.tar_finalizado = 1 AND tarefas.`tar_dtstart` >= curdate()
        UNION ALL
        SELECT usu_nome, foll_referencia AS referencia, followup.cli_id, c.cli_nome, c.cli_referencia, 'follow-up' AS tipo, foll_titulo AS titulo, '' AS dtstart, '' AS dtend, '' AS hrstart, '' AS hrend, foll_texto AS texto, '' AS prioridade, foll_data AS `data`, '' google_event_id from followup
    INNER JOIN btl_cliente c ON c.cli_id = followup.cli_id
    INNER JOIN usuarios u ON u.usu_id = c.usu_id
    WHERE followup.foll_finalizado = 1  AND followup.`foll_data` >= curdate()
        ORDER BY `data` ASC";

    $result = $this->sqlquery($sql);
    return !empty($result) ? $result : false;

  }
  
  public function listaEventos(){

    $permission_sql = ($_SESSION["nivel_acesso"] != "1") ? " AND c.usu_id = {$_SESSION["id_usuario"]}" : "";

    $sql = "SELECT usu_nome, tar_referencia AS referencia, tarefas.cli_id, c.cli_nome, c.cli_referencia, tar_tipo AS tipo, tar_titulo AS titulo, tar_dtstart AS dtstart, tar_dtend AS dtend, tar_hrstart AS hrstart, tar_hrend AS hrend, tar_texto AS texto, tar_prioridade AS prioridade, CONCAT(tar_dtstart, ' ',tar_hrstart) AS `data`, google_event_id from tarefas
    INNER JOIN btl_cliente c ON c.cli_id = tarefas.cli_id 
    INNER JOIN usuarios u ON u.usu_id = c.usu_id
    WHERE tarefas.tar_finalizado = 1
        UNION ALL
        SELECT usu_nome, foll_referencia AS referencia, followup.cli_id, c.cli_nome, c.cli_referencia, 'follow-up' AS tipo, foll_titulo AS titulo, '' AS dtstart, '' AS dtend, '' AS hrstart, '' AS hrend, foll_texto AS texto, '' AS prioridade, foll_data AS `data`, '' google_event_id from followup
    INNER JOIN btl_cliente c ON c.cli_id = followup.cli_id
    INNER JOIN usuarios u ON u.usu_id = c.usu_id
    WHERE followup.foll_finalizado = 1
        ORDER BY `data` ASC";

    $result = $this->sqlquery($sql);
    return !empty($result) ? $result : false;

  }

  public function selecionaColunas(){
    return $this->showcolunns();
  }

  public function parmTarefa($campo, $where,$order){ 
      return $this->readesp($campo,$where,$order);    
  }

  public function tarefaRegistar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $referencia = rand(10000, 9999999);    
      $arr = array( 
         'tar_referencia' => $referencia,
         'cli_id' => $dados['id'],  
         'tar_tipo' => $dados['tipo'],
         'tar_titulo' => $dados['titulo'],
         'tar_dtstart' => implode("-", array_reverse(explode("-", $dados['dtstart']))),
         'tar_hrstart' => $dados['hrstart'],
         'tar_dtend' => implode("-", array_reverse(explode("-", $dados['dtend']))),
         'tar_hrend' => $dados['hrend'],
         'tar_texto' => trim($dados['texto']),
         'tar_prioridade' => $dados['prioridade'],
         'google_event_id' => $dados['google_event_id']
      );
      $return = $this->insert($arr);
    endif;
    return $return;
  }

  public function tarefaUltimoRg(){
     $parm = "ORDER BY tar_id DESC LIMIT 1";     
     return $this->readorder($parm);
  }


  public function tarefaCsv($nome, $morada, $cp, $distrito, $localidade, $telefone, $area, $email, $vendedor){
    if(empty($nome)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($nome);
      $arr = array( 
         'tar_referencia' => 0,
         'tar_nome' => str_replace("/", "|", trim($nome)),  
         'tar_chave' => strtolower($chave), 
         'tar_tipo' => 0,
         'tar_area_negocio' => trim($area),
         'tar_rua' => str_replace("/", "|", trim($morada)),
         'tar_numero' => trim($dados['numero']),
         'tar_andar' => trim($dados['andar']),
         'tar_cp' => trim($cp),
         'tar_localidade' => str_replace("/", "|", trim($localidade)),
         'tar_distrito' => trim($distrito),
         'tar_telefone' => str_replace("/", "|", trim($telefone)),
         'tar_email' => str_replace("/", "|", trim($email)),
         'tar_url' => "",        
         'tar_descricao' => "", 
         'tar_status' => 'sim', 
         'vend_id' => str_replace("/", "|", $vendedor)
      );
      $return = $this->insert($arr);
    endif;
    return $return;
  }

  public function tarefaSelecionar($id){
     
     $parm = "tar_id = ".$id;      
     return $this->read($parm);
     
  }
  
  public function tarefaSelecionar_from_google_id($google_event_id){

    if(empty($google_event_id)) return false;
     
    $parm = "google_event_id = '{$google_event_id}'";      
    return $this->read($parm);
    
 }

  public function tarefaSelecionarRef($ref){
     
     $parm = "tar_referencia = ".$ref;      
     return $this->read($parm);
     
  }

  public function tarefaSelecionarCliente($cliente){
     
     $parm = " WHERE cli_id = ".$cliente." ORDER BY tar_data DESC";      
     return $this->readorder($parm);
     
  }

  public function tarefaChave($chave){
     $parm = "tar_id = '".$chave."'";     
     return $this->read($parm);
  }

  public function tarefaDeletar($id){
     $dados_id = "tar_id = ".$id;

     return $this->delete($dados_id, $id);
  }

  public function tarefaAlteracao(array $dados){

    //check if it's a google event id, sometimes it will be in the database already but no ID will be passed
    if(empty($dados['id'])){
      $task_info = $this->tarefaSelecionar_from_google_id($dados["google_event_id"]);
      
      if(empty($task_info)) return false;

      $dados["id"] = $task_info[0]["tar_id"];

    }
    else{
      die("Missing ID or Google Event ID");
      return false; //impossible to get info without ID or Google Event ID
    }

    $id = "tar_id=".$dados['id'];

    //switch between update most info and update only dates    
    if( empty($dados["texto"]) && empty($dados["titulo"])){
      
      //update dates only
      $arr = array(
        'tar_id' => $dados['id'],
        'tar_dtstart' => implode("-", array_reverse(explode("-", $dados['dtstart']))),
        'tar_hrstart' => $dados['hrstart'],
        'tar_dtend' => implode("-", array_reverse(explode("-", $dados['dtend']))),
        'tar_hrend' => $dados['hrend'],
        'google_event_id' => $dados['google_event_id']
      );
      

    }
    
    else{
      
      //full update
      $arr = array(
        'tar_id' => $dados['id'],
        'tar_tipo' => !empty($dados['tipo']) ? $dados['tipo'] : 1,
        'tar_titulo' => $dados['titulo'],
        'tar_dtstart' => implode("-", array_reverse(explode("-", $dados['dtstart']))),
        'tar_hrstart' => $dados['hrstart'],
        'tar_dtend' => implode("-", array_reverse(explode("-", $dados['dtend']))),
        'tar_hrend' => $dados['hrend'],
        'tar_texto' => trim($dados['texto']),
        'tar_prioridade' => $dados['prioridade'],
        'google_event_id' => $dados['google_event_id']
      );
    }

    //return $arr;        
    return $this->update($arr,$id);
  }


  /* PESQUISAR */
  public function tarefaBuscar($dados){

    $query = "";

    if(!empty( $dados )):

      foreach($dados as $item):

        if( !empty( $item["dtstart"] ) && !empty( $item["dtend"] )):

          $query .= " date(tar_data) BETWEEN '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND '".implode("-", array_reverse( explode("-", $item["dtend"])))."' AND";
        endif;

        if( !empty( $item["dtstart"] ) && empty( $item["dtend"] )):

          $query .= " date(tar_data) = '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND";
        endif;
      
        if( !empty( $item["vend_id"] ) ):

          $query .= " vend_id = ".$item["vend_id"]." AND";
        endif;

        if( !empty( $item["tar_area"] ) ):
          //$area = explode(",", $item["tar_area"]);
          $query .= " tar_area_negocio IN(".$item["tar_area"].") AND";
        endif;

        if( !empty( $item["tar_tipo"] ) ):

          $query .= " tar_tipo IN(".$item["tar_tipo"].") AND";
        endif;

        if( !empty( $item["tar_distrito"] ) ):

          $query .= " tar_distrito IN(".$item["tar_distrito"].") AND";
        endif;

        if( !empty( $item["campo"] ) ):
                  
          // Aqui você pode juntar vários campos no concat.
          $campo = ' CONCAT( tar_nome, tar_localidade )';
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
        $q = $this->readesp("*",$parm," ORDER BY tar_nome ASC");
      else:
        $q = $this->readesp("*",$parm," ORDER BY tar_nome ASC");

        //$q = $this->readesp("*",$parm." AND vend_id = ".$_SESSION['vendedor_acesso'].""," ORDER BY tar_nome ASC");
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


    public function tarefaBuscarXls($dados,$colunas){

      $query = "";

      if(!empty( $dados )):

        foreach($dados as $item):

          if( !empty( $item["dtstart"] ) && !empty( $item["dtend"] )):

            $query .= " date(tar_data) BETWEEN '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND '".implode("-", array_reverse( explode("-", $item["dtend"])))."' AND";
          endif;

          if( !empty( $item["dtstart"] ) && empty( $item["dtend"] )):

            $query .= " date(tar_data) = '".implode("-", array_reverse( explode("-", $item["dtstart"])))."' AND";
          endif;
        
          if( !empty( $item["vend_id"] ) ):

            $query .= " vend_id = ".$item["vend_id"]." AND";
          endif;

          if( !empty( $item["tar_area"] ) ):
            //$area = explode(",", $item["tar_area"]);
            $query .= " tar_area_negocio IN(".$item["tar_area"].") AND";
          endif;

          if( !empty( $item["tar_tipo"] ) ):

            $query .= " tar_tipo IN(".$item["tar_tipo"].") AND";
          endif;

          if( !empty( $item["tar_distrito"] ) ):

            $query .= " tar_distrito IN(".$item["tar_distrito"].") AND";
          endif;

          if( !empty( $item["campo"] ) ):
                    
            // Aqui você pode juntar vários campos no concat.
            $campo = ' CONCAT( tar_nome, tar_localidade )';
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
          $col = "tar_id".$str_colunas;
        else:
          $col = "*";
        endif; 

       $q = $this->readesp($col,$parm," ORDER BY tar_nome ASC");
       // else:
       // $q = $this->readesp($col,$parm," ORDER BY tar_nome ASC");
       // $q = $this->readesp($col,$parm." AND vend_id = ".$_SESSION['vendedor_acesso'].""," ORDER BY tar_nome ASC");
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