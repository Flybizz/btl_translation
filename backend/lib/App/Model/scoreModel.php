<?php
class App_Model_scoreModel extends App_Model{
  
  public $_tabela = "btl_score";

  public function listaScore(){
      $parm = " ORDER BY score_ordem ASC";
      return $this->readorder($parm);
  }

  public function listaScoreStatus(){
      $parm = "WHERE score_status = 'sim' ORDER BY score_nome ASC";
      return $this->readorder($parm);
  }

  public function scoreRegistar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($dados['nome']);
      $arr = array( 
         'score_nome' => trim($dados['nome']),  
         'score_chave' => strtolower($chave),       
         'score_status' => $dados['status']    
      );          
      $return = $this->insert($arr);
      // $return = $arr;
    endif;
    return $return;
  }

  public function scoreSelecionar($id){
     if($id <> null ):
     $parm = "score_id = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }

  public function scoreSelecionarIn($id){
     if($id <> null ):
     $parm = " score_id IN (".$id.")";
     elseif( $id == null ):
     $parm = "";
     endif;
     
     $rs = $this->readesp("score_nome",$parm," ORDER BY score_nome ASC"); 

     //print_r($rs);

     return $rs;
  }

  public function scoreChave($chave){
     $parm = "score_id = '".$chave."'";     
     return $this->read($parm);
  }

  public function scoreDeletar($id){
     $dados_id = "score_id =".$id;
     return $this->delete($dados_id,$id);
  }

  public function scoreAlteracao(array $dados){
    $id = "score_id=".$dados['id']; 
    $chave = parent::removerAcentos($dados['nome']);
    $arr = array( 
       'score_nome' => trim($dados['nome']),  
       'score_chave' => strtolower($chave),       
       'score_status' => $dados['status']    
    );    

    //return $arr;
        
    return $this->update($arr,$id);
  }

}