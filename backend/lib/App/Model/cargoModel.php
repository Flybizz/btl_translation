<?php
class App_Model_cargoModel extends App_Model{
  
  public $_tabela = "btl_cargo";

  public function listaCargo(){
      $parm = " ORDER BY cargo_nome ASC";
      return $this->readorder($parm);
  }

  public function listaCargoStatus(){
      $parm = "WHERE cargo_status = 'sim' ORDER BY cargo_nome ASC";
      return $this->readorder($parm);
  }

  public function cargoRegistar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($dados['nome']);
      $arr = array( 
         'cargo_nome' => trim($dados['nome']),  
         'cargo_chave' => strtolower($chave),       
         'cargo_status' => $dados['status']    
      );          
      $return = $this->insert($arr);
      // $return = $arr;
    endif;
    return $return;
  }

  public function cargoSelecionar($id){
     if($id <> null ):
     $parm = "cargo_id = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }

  public function cargoChave($chave){
     $parm = "cargo_id = '".$chave."'";     
     return $this->read($parm);
  }

  public function cargoDeletar($id){
     $dados_id = "cargo_id =".$id;
     return $this->delete($dados_id,$id);
  }

  public function cargoAlteracao(array $dados){
    $id = "cargo_id=".$dados['id']; 
    $chave = parent::removerAcentos($dados['nome']);
    $arr = array( 
       'cargo_nome' => trim($dados['nome']),  
       'cargo_chave' => strtolower($chave),       
       'cargo_status' => $dados['status']    
    );    

    //return $arr;
        
    return $this->update($arr,$id);
  }

}