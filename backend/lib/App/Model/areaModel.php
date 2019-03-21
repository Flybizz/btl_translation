<?php
class App_Model_areaModel extends App_Model{
  
  public $_tabela = "btl_area_negocio";

  public function listaArea(){
      $parm = " ORDER BY area_nome ASC";
      return $this->readorder($parm);
  }

  public function areaRegistar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($dados['nome']);
      $arr = array( 
         'area_nome' => trim($dados['nome']),  
         'area_chave' => strtolower($chave),       
         'area_status' => $dados['status']    
      );          
      $return = $this->insert($arr);
      // $return = $arr;
    endif;
    return $return;
  }

   public function areaSelecionarIn($id){
     if(!empty($id)):
     $parm = " area_id IN (".$id.")";
     $rs = $this->readesp("area_nome",$parm," ORDER BY area_nome ASC"); 
     else:
      $rs = "";
     endif;
    
     return $rs;
  }

  public function areaSelecionar($id){
     if($id <> null ):
     $parm = "area_id = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }

  public function areaChave($chave){
     $parm = "area_id = '".$chave."'";     
     return $this->read($parm);
  }

  public function areaDeletar($id){
     $dados_id = "area_id =".$id;
     return $this->delete($dados_id,$id);
  }

  public function areaAlteracao(array $dados){
    $id = "area_id=".$dados['id']; 
    $chave = parent::removerAcentos($dados['nome']);
    $arr = array( 
       'area_nome' => trim($dados['nome']),  
       'area_chave' => strtolower($chave),       
       'area_status' => $dados['status']    
    );    

    //return $arr;
        
    return $this->update($arr,$id);
  }

}