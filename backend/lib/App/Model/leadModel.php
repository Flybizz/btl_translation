<?php
class App_Model_leadModel extends App_Model{
  
  public $_tabela = "btl_lead";

  public function listaLead(){
      $parm = " ORDER BY lead_id ASC";
      return $this->readorder($parm);
  }

  public function listaLeadStatus(){
      $parm = "WHERE lead_status = 'sim' ORDER BY lead_nome ASC";
      return $this->readorder($parm);
  }

  public function leadRegistar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($dados['nome']);
      $arr = array( 
         'lead_nome' => trim($dados['nome']),
         'lead_chave' => strtolower($chave),
         'lead_status' => $dados['status']
      );
      $return = $this->insert($arr);
      // $return = $arr;
    endif;
    return $return;
  }

  public function leadSelecionar($id){
     if($id <> null ):
     $parm = "lead_id = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }

  public function leadChave($chave){
     $parm = "lead_id = '".$chave."'";     
     return $this->read($parm);
  }

  public function leadDeletar($id){
     $dados_id = "lead_id =".$id;
     return $this->delete($dados_id,$id);
  }

  public function leadAlteracao(array $dados){
    $id = "lead_id=".$dados['id']; 
    $chave = parent::removerAcentos($dados['nome']);
    $arr = array( 
       'lead_nome' => trim($dados['nome']),  
       'lead_chave' => strtolower($chave),       
       'lead_status' => $dados['status']    
    );    

    //return $arr;
        
    return $this->update($arr,$id);
  }

}