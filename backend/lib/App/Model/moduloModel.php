<?php
class App_Model_moduloModel extends App_Model{
  public $_tabela = "modulos";
  public function listaModulo(){
      $parm = " ORDER BY mod_modulo ASC";
      return $this->readorder($parm);
  }
  public function moduloCadastrar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $arr = array( 
         'mod_modulo' => trim($dados['modulo']),         
         'mod_titulo' => trim($dados['titulo']),
         'mod_status' => trim($dados['status'])    
      );
      
      $return = $this->insert($arr);
    endif;
    return $return;
  }
  public function moduloSelecionar($id){
     if($id <> null ):
     $parm = "mod_id = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }
  public function moduloMOD($chave){
     $parm = "mod_modulo = '".$chave."'";     
     return $this->read($parm);
  }
  public function moduloDeletar($id){
     $dados_id = "mod_id =".$id;
     return $this->delete($dados_id,$id);
  }
  public function moduloAlteracao(array $dados){
    $id = "mod_Uid=".$dados['id']; 
    $arr = array( 
       'mod_modulo' => trim($dados['modulo']),         
       'mod_titulo' => trim($dados['titulo']),
       'mod_status' => trim($dados['status'])    
    );
        
    return $this->update($arr,$id);
  }
  
    public function selecionarSlug($id){
      $parm = "SEO_Slug = '".$id."'";     
      return $this->read($parm);
    }
}