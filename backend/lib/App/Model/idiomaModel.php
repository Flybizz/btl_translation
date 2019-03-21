<?php
class App_Model_idiomaModel extends App_Model{
  public $_tabela = "d008idioma";
  public function listaIdioma(){
      $parm = "ORDER BY D008_Ordem ASC";
      return $this->readorder($parm);
  }
  public function countIdioma($id){
      $parm = "WHERE D008_PertenceCodigoMaster = ".$id." ORDER BY D008_Ordem ASC";
      return $this->readorder($parm);
  }
  public function countSub1($id,$sub1){
      $parm = "WHERE D008_PertenceCodigoMaster = ".$id." AND D008_PertenceCodigoSub1 = ".$sub1." ORDER BY D008_Ordem ASC";
      return $this->readorder($parm);
  }
  public function listaIdioma2(){
      $parm = "WHERE D008_PertenceCodigoMaster = 0 ORDER BY D008_Ordem ASC";
      return $this->readorder($parm);
  }
  public function listaIdioma3(){
      $parm = "WHERE D008_PertenceCodigoMaster = 0 AND D008_Tipo = '_self' ORDER BY D008_Ordem ASC";
      return $this->readorder($parm);
  }
  public function idiomaCadastrar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($dados['nome']);
      $arr = array(
         'D008_Nome' => $dados['nome'],
         'D008_Chave' => $chave,
         'D008_Sigla' => $dados['sigla'],
         'D008_Icon' => $dados['icon'],
         'D008_Ordem' => $dados['ordem'],
         'D008_Destaque' => $dados['destaque'],
         'D008_Status' => $dados['status']
      );
      $return = $this->insert($arr);
    endif;
    return $return;
  }
  
  public function idiomaSelecionar($id){
     if($id <> null ):
     $parm = "D008_Uid = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }

  public function idiomaSelecionarUltimoRg(){    
    $parm = "ORDER BY D008_Uid DESC LIMIT 1";       
    return $this->readorder($parm);
 }

  public function idiomaSelecionarSigla($id){
    
    $parm = "D008_Sigla = '".$id."'";       
    return $this->read($parm);
 }

  public function idiomaDeletar($id){
     $dados_id = "D008_Uid =".$id;
     return $this->delete($dados_id,$id);
  }

  public function idiomaAlteracao(array $dados){
    $id = "D008_Uid=".$dados['id']; 
    $chave = parent::removerAcentos($dados['nome']);
    $arr = array(
      'D008_Nome' => $dados['nome'],
      'D008_Chave' => $chave,
      'D008_Sigla' => $dados['sigla'],
      'D008_Icon' => $dados['icon'],
      'D008_Ordem' => $dados['ordem'],
      'D008_Destaque' => $dados['destaque'],
      'D008_Status' => $dados['status']
    );
    return $this->update($arr,$id);
  }
}