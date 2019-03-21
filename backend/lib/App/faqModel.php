<?php
class App_Model_faqModel extends App_Model{

  public $_tabela = "d004faq";

  public function listafaq(){

      return $this->read();
  }

  public function faqCadastrar(array $dados){

    if(empty($dados)):

      $return = "Erro: Dados vazios.";

    else:

      $chave = parent::removerAcentos(utf8_decode($dados['titulo']));

      $arr = array( 
         'D004_Secao' => utf8_encode($dados['secao']),
         'D004_Titulo' => trim(utf8_encode($dados['titulo'])),
         'D004_Chave' => strtolower($chave),
         'D004_Chamada' =>  utf8_encode($dados['chamada']),
         'D004_Texto' => utf8_encode($dados['conteudo']),
      );

      $return = $this->insert($arr);

    endif;

    return $return;

  }

  public function faqSelecionar($id){

     if($id <> null ):

     $parm = "D004_Uid = ".$id;

     elseif( $id == null ):

     $parm = "";

     endif;
     
     return $this->read($parm);

  }

  public function faqDeletar($id){

     $dados_id = "D004_Uid =".$id;

     return $this->delete($dados_id,$id);

  }

   public function faqAlteracao(array $dados){

    $id = "D004_Uid=".$dados['id']; 

    $chave = parent::removerAcentos(utf8_decode($dados['titulo']));

    $arr = array( 
        'D004_Secao' => utf8_encode($dados['secao']),
        'D004_Titulo' => trim(utf8_encode($dados['titulo'])),
        'D004_Chave' => strtolower($chave),
        'D004_Chamada' =>  utf8_encode($dados['chamada']),
        'D004_Texto' => utf8_encode($dados['conteudo'])
    );

    return $this->update($arr,$id);

  }

}