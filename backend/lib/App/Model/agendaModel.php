<?php
class App_Model_agendaModel extends App_Model{

  public $_tabela = "d040agenda";

  public function listaAgenda(){

      $parm = " ORDER BY D040_Data ASC";
      return $this->readorder($parm);

  }

  public function listaEnd(){

      $parm = "WHERE D040_Data >= '".date('Y-m-d')."' ORDER BY D040_Data, D040_Hora ASC LIMIT 1";
      return $this->readorder($parm);

  }

  

  public function listaEnds(){

      $parm = " ORDER BY D040_Data, D040_Hora  DESC LIMIT 4";
      return $this->readorder($parm);

  }

  public function agendaCadastrar(array $dados){

    if(empty($dados)):

      $return = "Erro: Dados vazios.";

    else:
      if(!empty($dados['img'])):
        $img = explode('.', $dados['img']);
        $image = date("dmYHi") . md5($img[0]).".".$img[1];
      else:
        $image = "";
      endif;

      $dt = explode(" ", $dados['data']);

      $chave = parent::removerAcentos(utf8_decode($dados['titulo']));

      $arr = array(
         'D040_Evento' => trim(utf8_encode($dados['titulo'])),
         'D040_Chave' => strtolower($chave),
         'D040_Chamada' => utf8_encode($dados['chamada']),
         'D040_Descricao' => utf8_encode($dados['texto']),
         'D040_Data' => implode("/", array_reverse(explode("-", $dt[0]))),
         'D040_Hora' => $dt[1],
         'D040_Imagem' => $image,
         'D040_Status' =>  $dados['destaque']
      );

      $return = $this->insert($arr);

    endif;

    return $return;

  }

  public function agendaSelecionar($id){

     if($id <> null ):

     $parm = "D040_Uid = ".$id;

     elseif( $id == null ):

     $parm = "";

     endif;
     
     return $this->read($parm);

  }

  public function agendaChave($chave){

     $parm = "D040_Chave = '".$chave."'";
     
     return $this->read($parm);

  }

  public function agendaDeletar($id){

     $dados_id = "D040_Uid =".$id;

     return $this->delete($dados_id,$id);

  }

   public function agendaAlteracao(array $dados){

      $id = "D040_Uid=".$dados['id']; 

      $chave = parent::removerAcentos(utf8_decode($dados['titulo']));

      $dt = explode(" ", $dados['data']);

      if(!empty($dados['img'])):
          $img = explode('.', $dados['img']);
          $image = date("dmYHi") . md5($img[0]).".".$img[1];
          $arr = array(
             'D040_Evento' => trim(utf8_encode($dados['titulo'])),
             'D040_Chave' => strtolower($chave),
             'D040_Chamada' => utf8_encode($dados['chamada']),
             'D040_Descricao' => utf8_encode($dados['texto']),
             'D040_Data' => implode("-", array_reverse(explode("-", $dt[0]))),
             'D040_Hora' => $dt[1],
             'D040_Imagem' => $image,
             'D040_Status' =>  $dados['destaque']
          );
      endif;
      if(empty($dados['img'])):
          $arr = array(
             'D040_Evento' => trim(utf8_encode($dados['titulo'])),
             'D040_Chave' => strtolower($chave),
             'D040_Chamada' => utf8_encode($dados['chamada']),
             'D040_Descricao' => utf8_encode($dados['texto']),
             'D040_Data' => implode("-", array_reverse(explode("-", $dt[0]))),
             'D040_Hora' => $dt[1],
             'D040_Status' =>  $dados['destaque']
          );
      endif;

      return $this->update($arr,$id);

  }

}