<?php

class App_Model_aulafotosModel extends App_Model{

  public $_tabela = "aula_arquivos";



  public function listaaula(){

      $parm = " ORDER BY arq_ordem ASC";

      return $this->readorder($parm);

  }

  public function listaArqFree($id){

      $parm = "WHERE aula_id = ".$id." ORDER BY arq_ordem, arq_data ASC";

      return $this->readorder($parm);

  }



  public function aulaCadastrar(array $dados){

    if(empty($dados)):

      $return = "Erro: Dados vazios.";

    else:
     

      if(!empty($_SESSION['aulaID'])):

        $arr = array(
        
          'aula_id' => $dados['aula'],

          'arq_file' => $dados['img']

        );

      else:

        $arr = array(

          'aula_id' => $dados['aula'],

          'arq_file' => $dados['img']

        );

      endif;

      $return = $this->insert($arr);

    endif;

    return $return;

  }



  public function aulaSelecionar($id){

     $parm = "arq_id = ".$id;

    

     return $this->read($parm);

  }



  public function aulaSelecionarFotos($id){

     if($id <> null ):

     $parm = "WHERE aula_id = ".$id." ORDER BY arq_ordem ASC";

     elseif( $id == null ):

     $parm = "";

     endif;

      return $this->readorder($parm);

  }



  public function aulaSelecionarFotosDestaque($id){

     if($id <> null ):

     $parm = "arq_id = ".$id;

     elseif( $id == null ):

     $parm = "";

     endif;

     

     return $this->read($parm);

  }



  public function aulaDeletar($id){

     $dados_id = "arq_id =".$id;

     return $this->delete($dados_id,$id);

  }



  public function aulaOrdenar($order, $id){

    $id = "arq_id=".$id; 

    $arr = array(

        'arq_ordem' => $order

    ); 

    return $this->update($arr,$id);

  }



}