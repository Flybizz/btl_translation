<?php
class App_Model_permissaoModel extends App_Model{

  public $_tabela = "permissao";

  public function permissaoListar(){

      return $this->read();
  }

  public function permissaoListar2(){

    $parm = "per_id <> 1 ORDER BY per_id ASC ";

    return $this->read($parm);
  }

  public function permissaoListar3($id){

    $parm = "per_id = ".$id."";

    return $this->read($parm);
  }

  public function permissaoListarColunas(){

    return $this->readcol();
  }


  public function permissaoCadastrar(array $dados){

    if(empty($dados)):

      $return = "Erro: Dados vazios.";

    else:

      $return = $this->insert($dados);

    endif;

    return $return;

  }

  public function permissaoSelecionar($id){

     if($id <> null ):

     $parm = "per_id = ".$id;

     elseif( $id == null ):

     $parm = "";

     endif;
     
     return $this->read($parm);

  }

  public function permissaoDeletar($id){

     $dados_id = "per_id =".$id." AND per_id <> 1";

     return $this->delete($dados_id,$id);

  }


   public function permissaoAlteracao($id, $dados){

    $info = "per_id=".$id; 
    return $this->update($dados,$info);

  }

}