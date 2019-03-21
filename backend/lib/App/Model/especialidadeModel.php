<?php
class App_Model_especialidadeModel extends App_Model{
  
  public $_tabela = "btl_especialidade";

  public function listaEspecialidade(){
      $parm = " ORDER BY esp_nome ASC";
      return $this->readorder($parm);
  }

  public function listaEspecialidadeStatus(){
      $parm = "WHERE esp_status = 'sim' ORDER BY esp_nome ASC";
      return $this->readorder($parm);
  }

  public function especialidadeRegistar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($dados['nome']);
      $arr = array( 
         'esp_nome' => trim($dados['nome']),  
         'esp_chave' => strtolower($chave),       
         'esp_status' => $dados['status']    
      );          
      $return = $this->insert($arr);
      // $return = $arr;
    endif;
    return $return;
  }

  public function especialidadeSelecionar($id){
     if($id <> null ):
     $parm = "esp_id = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }

  public function especialidadeChave($chave){
     $parm = "esp_id = '".$chave."'";     
     return $this->read($parm);
  }

  public function especialidadeDeletar($id){
     $dados_id = "esp_id =".$id;
     return $this->delete($dados_id,$id);
  }

  public function especialidadeAlteracao(array $dados){
    $id = "esp_id=".$dados['id']; 
    $chave = parent::removerAcentos($dados['nome']);
    $arr = array( 
       'esp_nome' => trim($dados['nome']),  
       'esp_chave' => strtolower($chave),       
       'esp_status' => $dados['status']    
    );    

    //return $arr;
        
    return $this->update($arr,$id);
  }

}