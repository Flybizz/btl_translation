<?php
class App_Model_tipoModel extends App_Model{
  
  public $_tabela = "btl_tipo";

  public function get_all_into_array(){
   $result = $this->listaTipo();
   if(!empty($result)){
     foreach($result as $tipo){
       $output[$tipo["tipo_id"]] = $tipo;
     }
     return $output;
   }
   return false;
 }

  public function listaTipo(){
      $parm = " ORDER BY tipo_nome ASC";
      return $this->readorder($parm);
  }

  public function listaTipoStatus(){
      $parm = "WHERE tipo_status = 'sim' ORDER BY tipo_nome ASC";
      return $this->readorder($parm);
  }

  public function tipoRegistar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($dados['nome']);
      $arr = array( 
         'tipo_nome' => trim($dados['nome']),  
         'tipo_chave' => strtolower($chave),       
         'tipo_status' => $dados['status']    
      );          
      $return = $this->insert($arr);
      // $return = $arr;
    endif;
    return $return;
  }

  public function tipoSelecionar($id){
     $parm = "tipo_id = ".$id;
     return $this->read($parm);
  }

  public function tipoSelecionarIn($id){
     if($id <> null ):
     $parm = " tipo_id IN (".$id.")";
     elseif( $id == null ):
     $parm = "";
     endif;
     
     $rs = $this->readesp("tipo_nome",$parm," ORDER BY tipo_nome ASC"); 

     //print_r($rs);

     return $rs;
  }

  public function tipoChave($chave){
     $parm = "tipo_id = '".$chave."'";     
     return $this->read($parm);
  }

  public function tipoDeletar($id){
     $dados_id = "tipo_id =".$id;
     return $this->delete($dados_id,$id);
  }

  public function tipoAlteracao(array $dados){
    $id = "tipo_id=".$dados['id']; 
    $chave = parent::removerAcentos($dados['nome']);
    $arr = array( 
       'tipo_nome' => trim($dados['nome']),  
       'tipo_chave' => strtolower($chave),       
       'tipo_status' => $dados['status']    
    );    

    //return $arr;
        
    return $this->update($arr,$id);
  }

}