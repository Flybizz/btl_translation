<?php
class App_Model_menuModel extends App_Model{

  public $_tabela = "d008categoriasmenu";

  public function listaMenu(){

      $parm = "ORDER BY D008_PertenceCodigoMaster ASC";
      return $this->readorder($parm);

  }

  public function listaMenu2(){

      $parm = "WHERE D008_PertenceCodigoMaster = 0 ORDER BY D008_PertenceCodigoMaster ASC";
      return $this->readorder($parm);

  }

  public function listaMaster(){

      $parm = "WHERE D008_PertenceCodigoMaster <> 0 ORDER BY D008_PertenceCodigoMaster ASC";
      return $this->readorder($parm);

  }

  public function listaSub1(){

      $parm = "WHERE D008_PertenceCodigoSub1 <> 0 ORDER BY D008_PertenceCodigoSub1 ASC";
      return $this->readorder($parm);

  }

  public function listaSub2(){

      $parm = "WHERE D008_PertenceCodigoSub2 <> 0 ORDER BY D008_PertenceCodigoSub2 ASC";
      return $this->readorder($parm);

  }

  public function listaSub3(){

      $parm = "WHERE D008_PertenceCodigoSub3 <> 0 ORDER BY D008_PertenceCodigoSub3 ASC";
      return $this->readorder($parm);

  }

  public function listaSub4(){

      $parm = "WHERE D008_PertenceCodigoSub4 <> 0 ORDER BY D008_PertenceCodigoSub4 ASC";
      return $this->readorder($parm);

  }

  public function listaSub5(){

      $parm = "WHERE D008_PertenceCodigoSub5 <> 0 ORDER BY D008_PertenceCodigoSub5 ASC";
      return $this->readorder($parm);

  }

  public function listaSmenu($master,$sub1,$sub2,$sub3,$sub4,$sub5){

      if($master != 0 && $sub1 == 0):

        $parm = "WHERE D008_PertenceCodigoMaster=".$master." ORDER BY D008_Ordem ASC";

      elseif($master != 0 && $sub1 != 0 &&  $sub2 == 0):

        $parm = "WHERE D008_PertenceCodigoMaster=".$master." AND D008_PertenceCodigoSub1 = ".$sub1." ORDER BY D008_Ordem ASC";

      elseif($master != 0 && $sub1 != 0 &&  $sub2 != 0 && $sub3 == 0 ):

        $parm = "WHERE D008_PertenceCodigoMaster=".$master." AND D008_PertenceCodigoSub1 = ".$sub1." AND D008_PertenceCodigoSub2 = ".$sub2." ORDER BY D008_Ordem ASC";

      elseif($master != 0 && $sub1 != 0 &&  $sub2 != 0 && $sub3 != 0 && $sub4 == 0 ):

        $parm = "WHERE D008_PertenceCodigoMaster=".$master." AND D008_PertenceCodigoSub1 = ".$sub1." AND D008_PertenceCodigoSub2 = ".$sub2." AND D008_PertenceCodigoSub3 = ".$sub3." ORDER BY D008_Ordem ASC";

      elseif($master != 0 && $sub1 != 0 &&  $sub2 != 0 && $sub3 != 0 && $sub4 != 0 && $sub5 == 0):

        $parm = "WHERE D008_PertenceCodigoMaster=".$master." AND D008_PertenceCodigoSub1 = ".$sub1." AND D008_PertenceCodigoSub2 = ".$sub2." AND D008_PertenceCodigoSub3 = ".$sub3." AND D008_PertenceCodigoSub4 = ".$sub4." ORDER BY D008_Ordem ASC";

      elseif($master != 0 && $sub1 != 0 &&  $sub2 != 0 && $sub3 != 0 && $sub4 != 0 && $sub5 != 0 ):

        $parm = "WHERE D008_PertenceCodigoMaster=".$master." AND D008_PertenceCodigoSub1 = ".$sub1." AND D008_PertenceCodigoSub2 = ".$sub2." AND D008_PertenceCodigoSub3 = ".$sub3." AND D008_PertenceCodigoSub4 = ".$sub4." AND D008_PertenceCodigoSub5 = ".$sub5." ORDER BY D008_Ordem ASC";

      endif;

      if(isset($parm)):

        return $this->readorder($parm);

      endif;

  }

  public function menuCadastrar(array $dados){

    if(empty($dados)):

      $return = "Erro: Dados vazios.";

    else:

      $arr = array(
         'D008_DescriCategoria' => utf8_encode($dados['nome']),
         'D008_PertenceCodigoMaster' => 0,
         'D008_Link' => $dados['link'],
         'D008_Ordem' =>  $dados['ordem'],
         'D008_Tipo' =>  $dados['tipo']
      );

      $return = $this->insert($arr);

    endif;

    return $return;

  }

  /* CADASTRA O SUBMENU*/
  public function smenuCadastrar(array $dados,$master,$sub1,$sub2,$sub3,$sub4,$sub5){

    //NIVEL1
    if($master != 0 && $sub1 == 0):

      $arr = array(
         'D008_DescriCategoria' => utf8_encode($dados['nome']),
         'D008_PertenceCodigoMaster' => $master,
         'D008_Link' => $dados['link'],
         'D008_Ordem' =>  $dados['ordem'],
         'D008_Tipo' =>  $dados['tipo']
      );

    //NIVEL2
    elseif($master != 0 && $sub1 != 0 && $sub2 == 0):

      $arr = array(
         'D008_DescriCategoria' => utf8_encode($dados['nome']),
         'D008_PertenceCodigoMaster' => $master,
         'D008_PertenceCodigoSub1' => $sub1,
         'D008_Link' => $dados['link'],
         'D008_Ordem' =>  $dados['ordem'],
         'D008_Tipo' =>  $dados['tipo']
      );

    //NIVEL3
    elseif($master != 0 && $sub1 != 0 &&  $sub2 != 0 && $sub3 == 0 ):

      $arr = array(
         'D008_DescriCategoria' => utf8_encode($dados['nome']),
         'D008_PertenceCodigoMaster' => $master,
         'D008_PertenceCodigoSub1' => $sub1,
         'D008_PertenceCodigoSub2' => $sub2,
         'D008_Link' => $dados['link'],
         'D008_Ordem' =>  $dados['ordem'],
         'D008_Tipo' =>  $dados['tipo']
      );

    //NIVEL4
    elseif($master != 0 && $sub1 != 0 &&  $sub2 != 0 && $sub3 != 0 && $sub4 == 0 ):

      $arr = array(
         'D008_DescriCategoria' => utf8_encode($dados['nome']),
         'D008_PertenceCodigoMaster' => $master,
         'D008_PertenceCodigoSub1' => $sub1,
         'D008_PertenceCodigoSub2' => $sub2,
         'D008_PertenceCodigoSub3' => $sub3,
         'D008_Link' => $dados['link'],
         'D008_Ordem' =>  $dados['ordem'],
         'D008_Tipo' =>  $dados['tipo']
      );
 
    //NIVEL5
    elseif($master != 0 && $sub1 != 0 &&  $sub2 != 0 && $sub3 != 0 && $sub4 != 0 && $sub5 == 0):

      $arr = array(
         'D008_DescriCategoria' => utf8_encode($dados['nome']),
         'D008_PertenceCodigoMaster' => $master,
         'D008_PertenceCodigoSub1' => $sub1,
         'D008_PertenceCodigoSub2' => $sub2,
         'D008_PertenceCodigoSub3' => $sub3,
         'D008_PertenceCodigoSub4' => $sub4,
         'D008_Link' => $dados['link'],
         'D008_Ordem' =>  $dados['ordem'],
         'D008_Tipo' =>  $dados['tipo']
      );
    
    //NIVEL6
    elseif($master != 0 && $sub1 != 0 &&  $sub2 != 0 && $sub3 != 0 && $sub4 != 0 && $sub5 != 0 ):

      $arr = array(
         'D008_DescriCategoria' => utf8_encode($dados['nome']),
         'D008_PertenceCodigoMaster' => $master,
         'D008_PertenceCodigoSub1' => $sub1,
         'D008_PertenceCodigoSub2' => $sub2,
         'D008_PertenceCodigoSub3' => $sub3,
         'D008_PertenceCodigoSub4' => $sub4,
         'D008_PertenceCodigoSub5' => $sub5,
         'D008_Link' => $dados['link'],
         'D008_Ordem' =>  $dados['ordem'],
         'D008_Tipo' =>  $dados['tipo']
      );

    endif;

    $return = $this->insert($arr);

    return $return;

  }

  public function menuSelecionar($id){

     if($id <> null ):

     $parm = "D008_Uid = ".$id;

     elseif( $id == null ):

     $parm = "";

     endif;
     
     return $this->read($parm);

  }

  public function menuDeletar($id){

     $dados_id = "D008_Uid =".$id;

     return $this->delete($dados_id,$id);

  }

   public function menuAlteracao(array $dados){

    $id = "D008_Uid=".$dados['id']; 

     $arr = array(
         'D008_DescriCategoria' => utf8_encode($dados['nome']),
         'D008_PertenceCodigoMaster' => utf8_encode($dados['codigoMaster']),
         'D008_PertenceCodigoSub1' => $dados['CodigoSub1'],
         'D008_PertenceCodigoSub2' => $dados['CodigoSub2'],
         'D008_PertenceCodigoSub3' => $dados['CodigoSub3'],
         'D008_PertenceCodigoSub4' => $dados['CodigoSub4'],
         'D008_PertenceCodigoSub5' => $dados['CodigoSub5'],
         'D008_Link' => $dados['link'],
         'D008_Ordem' =>  $dados['ordem'],
         'D008_Tipo' =>  $dados['tipo']
      );

    return $this->update($arr,$id);

  }

  public function removerAcentos($str, $enc = "UTF-8"){

  $acentos = array(  
  'A' => '/&Agrave;|&Aacute;|&Acute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
  'a' => '/&agrave;|&aacute;|&acute;|&acirc;|&atilde;|&auml;|&aring;/',
  'C' => '/&Ccedil;/',
  'c' => '/&ccedil;/',
  'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
  'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
  'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
  'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
  'N' => '/&Ntilde;/',
  'n' => '/&ntilde;/',
  'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
  'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
  'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
  'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
  'Y' => '/&Yacute;/',
  'y' => '/&yacute;|&yuml;/',
  'a.' => '/&ordf;/',
  'o.' => '/&ordm;/');

      return preg_replace($acentos, array_keys($acentos), htmlentities($str,ENT_NOQUOTES, $enc));
  }

}