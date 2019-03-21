<?php
class App_Model_nivelModel extends App_Model{
  public $_tabela = "nivel";
  public function nivelListar(){
      return $this->read();
  }
  public function nivelListar2(){
    $parm = "niv_id <> 1 ORDER BY niv_nome ASC ";
    return $this->read($parm);
  }
  public function nivelListar3(){
    $parm = "niv_id <> 1 ORDER BY niv_id DESC LIMIT 1";
    return $this->read($parm);
  }
  public function nivelCadastrar(array $dados){
    $arr = array( 'niv_nome' => $dados['nome'],  'niv_sessao' => ""  );
    $return = $this->insert($arr);
    return $return;
  }
  public function nivelSelecionar($id){
     if($id <> null ):
     $parm = "niv_id = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }
  public function nivelDeletar($id){
     $dados_id = " niv_id = ".$id;
     return $this->delete($dados_id,$id);
  }
   public function nivelAlteracao($dados){
    $id = "niv_id=".$dados['id'];     
    $arr = array( 'niv_nome' => $dados['nome'] );
  
    return $this->update($arr,$id);
  }
}