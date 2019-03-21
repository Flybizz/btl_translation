<?php
class App_Model_cursoModel extends App_Model{
  public $_tabela = "d016curso";
  public function listaCurso(){
      $parm = " ORDER BY D016_Data ASC";
      return $this->readorder($parm);
  }
  public function listaEnds(){
      $parm = "WHERE D016_Destaque = 'Sim' ORDER BY D016_Ordem ASC LIMIT 6";
      return $this->readorder($parm);
  }
  public function listaEnd(){
      $parm = "WHERE D016_Status = 'Sim' ORDER BY D016_Ordem DESC LIMIT 3";
      return $this->readorder($parm);
  }
  public function listaEndcurso2()
  {
      $parm = " ORDER BY D016_Uid DESC limit 1";
      return $this->readorder($parm);
  }
  public function listaData($data){
    $parm = "WHERE date(D016_Data) = '".$data."' ORDER BY D016_Data ASC";
    return $this->readorder($parm);
  }
  public function listaData2($data,$id){
    $parm = "WHERE date(D016_Data) = '".$data."' AND find_in_set(".$id.", D016_Tutor) > 0  ORDER BY D016_Data ASC";
    return $this->readorder($parm);
  }
  public function cursoCadastrar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $chave = parent::removerAcentos($dados['titulo']);
      if(!empty($dados['data'])):
        $data = explode(" ", $dados['data']);
        $dt = implode("/", array_reverse(explode("-", $data[0]))) . " " .$data[1];
      else:
        $dt = "0000-00-00 00:00:00";
      endif;
      if(!empty($dados['dataend'])):
        $dataend = explode(" ", $dados['dataend']);
        $dtend = implode("/", array_reverse(explode("-", $dataend[0]))) . " " .$dataend[1];
      else:
        $dtend = "0000-00-00 00:00:00";
      endif;      
      $arr = array( 
         'D016_Titulo' => trim($dados['titulo']),
         'D016_Tutor' => trim($dados['tutor']),
         'D016_Periodo' => trim($dados['periodo']),
         'D016_Ordem' => trim($dados['ordem']),       
         'D016_Descricao' => $dados['descricao'],
         'D016_Chamada' => $dados['chamada'],
         'D016_Chave' => strtolower($chave),
         'D016_Foto' => $dados['img'],
         'D016_Video' => $dados['video'],
         'D016_Tipo' => $dados['tipo'],
         'D016_Status' => $dados['status'],
         'D016_Destaque' => $dados['destaque'],
         'D016_Data' => $dt,
         'D016_Dataend' => $dtend,
         'D016_Categoria' => $dados['categoria'],
         'D016_Icon' => $dados['icon'],
         'D016_Valor' => $dados['valor'],
         'D016_Valor2' => $dados['valor2'],
         'D016_Codigo' => $dados['codigo'],
         'D016_Pacote' => $dados['pacote']
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
   public function cursoSelecionar2($id,$pacote){
     $parm = "WHERE D016_Uid = '".$id."' AND D016_Pacote = '".$pacote."' ORDER BY D016_Ordem ASC";
     return $this->readorder($parm);
  }
  public function cursoChave($chave){
     $parm = "D016_Chave = '".$chave."'";     
     return $this->read($parm);
  }
  public function cursoCat($cat){
     $parm = "WHERE D016_Categoria = '".$cat."' ORDER BY D016_Categoria, D016_Data ASC";     
     return $this->readorder($parm);
  }
  public function cursoPacote($id,$pacote){
     $parm = "WHERE D016_Categoria = '".$id."' AND D016_Pacote = '".$pacote."' ORDER BY D016_Data ASC";     
     return $this->readorder($parm);
  }
  public function cursoDeletar($id){
     $dados_id = "D016_Uid =".$id;
     return $this->delete($dados_id,$id);
  }
  public function cursoAlteracao(array $dados){
    $id = "D016_Uid=".$dados['id']; 
    $chave = parent::removerAcentos($dados['titulo']);
    
      if(!empty($dados['data'])):
        $data = explode(" ", $dados['data']);
        $dt = implode("/", array_reverse(explode("-", $data[0]))) . " " .$data[1];
      else:
        $dt = "0000-00-00 00:00:00";
      endif;
      if(!empty($dados['dataend'])):
        $dataend = explode(" ", $dados['dataend']);
        $dtend = implode("/", array_reverse(explode("-", $dataend[0]))) . " " .$dataend[1];
      else:
        $dtend = "0000-00-00 00:00:00";
      endif;      
      $arr = array( 
         'D016_Titulo' => trim($dados['titulo']),
         'D016_Tutor' => trim($dados['tutor']),
         'D016_Periodo' => trim($dados['periodo']),
         'D016_Ordem' => trim($dados['ordem']),       
         'D016_Descricao' => $dados['descricao'],
         'D016_Chamada' => $dados['chamada'],
         'D016_Chave' => strtolower($chave),
         'D016_Foto' => $dados['img'],
         'D016_Video' => $dados['video'],
         'D016_Tipo' => $dados['tipo'],
         'D016_Status' => $dados['status'],
         'D016_Destaque' => $dados['destaque'],
         'D016_Data' => $dt,
         'D016_Dataend' => $dtend,
         'D016_Categoria' =>  $dados['categoria'],
         'D016_Icon' => $dados['icon'],
         'D016_Valor' => $dados['valor'],
         'D016_Valor2' => $dados['valor2'],
         'D016_Codigo' => $dados['codigo'],
         'D016_Pacote' => $dados['pacote']
      );
        
    return $this->update($arr,$id);
  }
}