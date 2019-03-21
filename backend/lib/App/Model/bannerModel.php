<?php
class App_Model_bannerModel extends App_Model{

  public $_tabela = "d028banner";

  public function listabanner(){

      $parm = " ORDER BY D028_Ordem ASC";
      return $this->readorder($parm);

  }

  public function bannerCadastrar(array $dados){

    if(empty($dados)):

      $return = "Erro: Dados vazios.";

    else:
      
      $img = explode('.', $dados['img']);
      $image = date("dmYHi") . md5($img[0]).".".$img[1];
      $arr = array( 
         'D028_Nome' => trim(utf8_encode($dados['nome'])),
         'D028_Texto' => utf8_encode($dados['texto']),
         'D028_Data' =>  date('Y-m-d H:i:s', strtotime($dados['data'].':00')),
         'D028_Arquivo' => $image,
         'D028_Status' => $dados['status'],
         'D028_Link' => $dados['link'],
         'D028_Site' => strtolower($dados['url']),
         'D028_Ordem' =>  $dados['ordem']
      );

      $return = $this->insert($arr);

    endif;

    return $return;

  }

  public function bannerSelecionar($id){

     if($id <> null ):

     $parm = "D028_Uid = ".$id;

     elseif( $id == null ):

     $parm = "";

     endif;
     
     return $this->read($parm);

  }

  public function bannerDeletar($id){

     $dados_id = "D028_Uid =".$id;

     return $this->delete($dados_id,$id);

  }

   public function bannerAlteracao(array $dados){

    $id = "D028_Uid=".$dados['id']; 

    if(!empty($dados['img'])):
          $img = explode('.', $dados['img']);
          $image = date("dmYHi") . md5($img[0]).".".$img[1];
          $arr = array( 
           'D028_Nome' => trim(utf8_encode($dados['nome'])),
           'D028_Texto' => utf8_encode($dados['texto']),
           'D028_Data' =>  date('Y-m-d H:i:s', strtotime($dados['data'].':00')),
           'D028_Arquivo' => $image,
           'D028_Status' => $dados['status'],
           'D028_Link' => $dados['link'],
           'D028_Site' => strtolower($dados['url']),
           'D028_Ordem' =>  $dados['ordem']
          );

      endif;
      if(empty($dados['img'])):
          $arr = array( 
           'D028_Nome' => trim(utf8_encode($dados['nome'])),
           'D028_Texto' => utf8_encode($dados['texto']),
           'D028_Data' =>  date('Y-m-d H:i:s', strtotime($dados['data'].':00')),
           'D028_Status' => $dados['status'],
           'D028_Link' => $dados['link'],
           'D028_Site' => strtolower($dados['url']),
           'D028_Ordem' =>  $dados['ordem']
          );
      endif;

    return $this->update($arr,$id);

  }

  
}