<?php
class App_Model_vendedorModel extends App_Model{
  
  public $_tabela = "btl_vendedor";

  public function listaVendedor(){
      $parm = " ORDER BY vend_nome ASC";
      return $this->readorder($parm);
  }

  public function vendedorBuscar($busca, $active){

    if($active == "true"):
      $parm = "vend_nome like '%".$busca."%'";
      return $this->readesp("vend_nome, vend_id",$parm,"");
    endif;
  }

  public function vendedorRegistar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($dados['nome']);
      $arr = array( 
         'vend_nome' => trim($dados['nome']),  
         'vend_chave' => strtolower($chave), 
         'vend_telefone' => trim($dados['telefone']),
         'vend_telemovel' => trim($dados['telemovel']),
         'vend_email' => trim($dados['email']),        
         'vend_status' => $dados['status']
      );          
      $return = $this->insert($arr);
    endif;
    return $return;
  }

  public function vendedorSelecionar($id){
     if($id <> null ):
     $parm = "vend_id = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }

   public function vendedorSelecionarIn($id){
     if(!empty($id)):
     $parm = " vend_id IN (".$id.")";
     $rs = $this->readesp("vend_nome",$parm," ORDER BY vend_nome ASC"); 
     else:
      $rs = "";
     endif;
    
     return $rs;
  }

  public function vendedorChave($chave){
     $parm = "vend_id = '".$chave."'";     
     return $this->read($parm);
  }

  public function vendedorDeletar($id){
     $dados_id = "vend_id =".$id;
     return $this->delete($dados_id,$id);
  }

  public function vendedorAlteracao(array $dados){
    $id = "vend_id=".$dados['id']; 
    $chave = parent::removerAcentos($dados['nome']);
    $arr = array( 
       'vend_nome' => trim($dados['nome']),  
       'vend_chave' => strtolower($chave), 
       'vend_telefone' => trim($dados['telefone']),
       'vend_telemovel' => trim($dados['telemovel']),
       'vend_email' => trim($dados['email']),        
       'vend_status' => $dados['status'],
       'vend_area_negocio' => trim($dados['area'])     
    );

    //return $arr;
        
    return $this->update($arr,$id);
  }

}