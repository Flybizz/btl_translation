<?php

class App_Model_cursocatModel extends App_Model{



  public $_tabela = "d016curso_cat";



  public function listaCurso(){

      $parm = " ORDER BY D016_Nome ASC";

      return $this->readorder($parm);

  }


  public function listaCurso2(){

      $parm = " ORDER BY D016_Ordem ASC LIMIT 6";

      return $this->readorder($parm);

  }



  public function cursoCadastrar(array $dados){

    if(empty($dados)):

      $return = "Erro: Dados vazios.";

    else:

  

      $chave = parent::removerAcentos($dados['nome']);

      $arr = array(          

         'D016_Nome' => trim($dados['nome']),

         'D016_Chave' => strtolower($chave),

         'D016_Descricao' => trim($dados['descricao']),

         'D016_Imagem' => trim($dados['img']),

         'D016_Destaque' => trim($dados['destaque']),

         'D016_Status' => trim($dados['status']),

         'D016_Ordem' => trim($dados['ordem'])

      );

      $return = $this->insert($arr);

    endif;

    return $return;

  }



  public function cursoSelecionar($id){

     if($id <> null ):

     $parm = "D016_Uid = ".$id;

     elseif( $id == null ):

     $parm = "";

     endif;

     

     return $this->read($parm);

  }

   public function cursoSelecionarChave($id){
    
     $parm = "D016_Chave = '".$id."'"; 

     return $this->read($parm);

  }


  public function cursoSelecionarcat($id){

     if($id <> null ):

     $parm = "D016_Categoria = ".$id;

     elseif( $id == null ):

     $parm = "";

     endif;

     return $this->read($parm);

  }



  public function cursoDeletar($id){

     $dados_id = "D016_Uid =".$id;

     return $this->delete($dados_id,$id);

  }



   public function cursoAlteracao(array $dados){

      $id = "D016_Uid=".$dados['id']; 

      $chave = parent::removerAcentos($dados['nome']);

      $arr = array(

         'D016_Nome' => trim($dados['nome']),

         'D016_Chave' => strtolower($chave),

         'D016_Descricao' => trim($dados['descricao']),

         'D016_Imagem' => trim($dados['img']),

         'D016_Destaque' => trim($dados['destaque']),

         'D016_Status' => trim($dados['status']),

         'D016_Ordem' => trim($dados['ordem'])

      );

    return $this->update($arr,$id);

  }

  

}