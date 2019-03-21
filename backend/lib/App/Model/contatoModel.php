<?php
class App_Model_contatoModel extends App_Model{
  
  public $_tabela = "btl_contato";

  public function get_all_into_array(){
    $result = $this->listaContato();
    if(!empty($result)){
      foreach($result as $contato){
        $output[$contato["cli_id"]] = $contato;
      }
      return $output;
    }
    return false;
  }

  public function listaContato(){
      $parm = " ORDER BY cont_nome ASC";
      return $this->readorder($parm);
  }

  public function contatoWithCliente($action){   

    if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
      
      return $this->sqlquery("SELECT cont_id, cont_nome, cont_email, cont_telemovel, cont_lead_status,(SELECT cli_nome FROM btl_cliente WHERE cli_id = bc.cli_id) AS cli_id FROM btl_contato bc WHERE cont_lead_status = {$action} ORDER BY cont_nome");

    else:

      return $this->sqlquery("SELECT cont_id, cont_nome, cont_email, cont_telemovel, cont_lead_status,(SELECT cli_nome FROM btl_cliente WHERE cli_id = bc.cli_id) AS cli_id,(SELECT usu_id FROM btl_cliente WHERE cli_id = bc.cli_id) AS usu_id  FROM btl_contato bc WHERE cont_lead_status = {$action} HAVING usu_id = {$_SESSION['id_usuario']} ORDER BY cont_nome");
    
    endif;
  }

  public function contatoRegistar(array $dados){

    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($dados['nome']);
      $arr = array( 
         'cont_nome' => trim($dados['nome']),  
         'cont_chave' => strtolower($chave), 
         'cont_cargo' => trim($dados['cargo']),
         'cont_especialidade' => trim($dados['especialidade']),
         'cont_email' => trim($dados['email']),
         'cont_telemovel' => trim($dados['telemovel']),
         'cli_id' => trim($dados['cliente']),
         'cont_contato' => trim($dados['contato']),
         'cont_lead_status' => $dados["lead_status"]
      );     
      
      $return = $this->insert($arr);
    endif;
    return $return;
  }

  public function contatoCsv($contato,$especialidade,$cliente){
    if(empty($contato)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($contato);
      $arr = array( 
         'cont_nome' => str_replace("/", "|", trim($contato)),  
         'cont_chave' => strtolower($chave), 
         'cont_cargo' => 0,
         'cont_especialidade' => $especialidade,
         'cont_email' => "",
         'cont_telemovel' => "",
         'cli_id' => trim($cliente),
         'cont_contato' => 1,
         'cont_lead_status' => 1         
      );          
      $return = $this->insert($arr);
    endif;
    return $return;
  }

  public function contatoSelecionarCliente5($cliente){
      $parm = "WHERE cli_id = ".$cliente." ORDER BY cont_contato ASC";
      return $this->readorder($parm);

  }

  public function contatoSelecionarCliente($cliente){     
     $parm = " cli_id IN (".$cliente.")";
     $rs = $this->readesp("*",$parm," ORDER BY cont_contato ASC"); 
     return $rs;
  }

  public function contatoSelecionar($id){
     if($id <> null ):
     $parm = "cont_id = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }

  public function contatoChave($chave){
     $parm = "cont_id = '".$chave."'";
     return $this->read($parm);
  }

  public function contatoDeletar($id){
     $dados_id = "cont_id =".$id;
     return $this->delete($dados_id,$id);
  }

  public function contatoDeletarCliente($id){
     $dados_id = "cli_id =".$id;
     return $this->delete($dados_id,$id);
  }
 
  public function contatoAlteracao(array $dados){


    $id = "cont_id=".$dados['id']; 
    $chave = parent::removerAcentos($dados['nome']);
    $arr = array(
       'cont_nome' => trim($dados['nome']),  
       'cont_chave' => strtolower($chave), 
       'cont_cargo' => trim($dados['cargo']),
       'cont_especialidade' => trim($dados['especialidade']),
       'cont_email' => trim($dados['email']),
       'cont_telemovel' => trim($dados['telemovel']),
       'cli_id' => trim($dados['cliente']),
       'cont_contato' => trim($dados['contato']),
       'cont_lead_status' => $dados["lead_status"]
    );

    //return $arr;
        
    return $this->update($arr,$id);
  }

  public function contatoSelecionarClienteLead($cliente){
    $parm = "WHERE cli_id = ".$cliente." AND cont_lead_status <> 0 ORDER BY cont_contato ASC";
    return $this->readorder($parm);
  }

  

  public function contatoAction($data, $action){

    $id = "cont_id=".$data;     
    $arr = array( 'cont_lead_status' => $action );
        
    return $this->updatelead($arr,$id);
  }  

}