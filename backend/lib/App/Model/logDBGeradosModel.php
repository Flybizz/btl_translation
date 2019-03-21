<?php

class App_Model_logDBGeradosModel extends App_Model{

  public $_tabela = "btl_log_bd_gerados";

  public function listaLog(){
    $parm = "WHERE MONTH(date) = '".date("n")."' ORDER BY date DESC";
    return $this->readorder($parm);
  }

  public function listaEnds(){
    $parm = "WHERE destaque = 'Sim' ORDER BY ordem ASC LIMIT 6";
    return $this->readorder($parm);
  }

  public function listaEnd(){
    $parm = "WHERE status = 'Sim' ORDER BY ordem DESC LIMIT 3";
    return $this->readorder($parm);
  }

  public function listaDate($date){
    $parm = "WHERE date(date) = '".$date."' ORDER BY date ASC";
    return $this->readorder($parm);
  }

  public function listaData2($date,$id){
    $parm = "WHERE date(date) = '".$date."' AND find_in_set(".$id.", tutor) > 0  ORDER BY date ASC";
    return $this->readorder($parm);
  }

  public function logRegistar($action,$data){
    
    $arr = array(       
      'user' => $_SESSION['usuario_nome'],
      'action' => $action,
      'filter' => json_encode(array(time() => $data))         
    ); 
        
    return $this->insert($arr);
      
  }


  public function logSelecionar($id){

     if($id <> null ):

     $parm = "id = ".$id;

     elseif( $id == null ):

     $parm = "";

     endif;     

     return $this->read($parm);

  }



  public function logSelecionarcat($id){
     if($id <> null ):
     $parm = "disciplina = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif; 
     return $this->read($parm);
  }

  public function listaCat($cat){
  $parm = "WHERE disciplina = '".$cat."' ORDER BY date ASC";
  return $this->readorder($parm);
  }



  public function logChave($chave){

     $parm = "chave = '".$chave."'";     

     return $this->read($parm);

  }



  public function logDeletar($id){

     $dados_id = "id =".$id;

     return $this->delete($dados_id,$id);

  }



  public function logAlteracao(array $dados){

      $id = "id=".$dados['id']; 

      $chave = parent::removerAcentos($dados['titulo']);

      $arr = array( 

         'titulo' => trim($dados['titulo']),

         'chave' => strtolower($chave),

         'tutor' => trim($dados['tutor']),

         'chamada' => trim($dados['chamada']),       

         'descricao' => $dados['descricao'],

         'video' => $dados['video'],

         'tipovideo' => $dados['tpvideo'],

         'status' => $dados['status'],

         'tipostatus' => $dados['tpstatus'],

         'ordem' => $dados['ordem']     

      );        

    return $this->update($arr,$id);

  }



}