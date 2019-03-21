<?php
class App_Model_configModel extends App_Model{
  public $_tabela = "d001configuracoes";
  public function listaConfig(){
      $parm = " ORDER BY D001_Data ASC";
      return $this->readorder($parm);
  }
   public function configBuscar(){
  
     $parm = "D001_Uid = 1";
     
     return $this->read($parm);
  }
  
  public function configSelecionar($id){
     if($id <> null ):
     $parm = "D001_Uid = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }
   public function configAlteracao1(array $dados){
    $id = "D001_Uid=".$dados['id']; 

    $arr = array(
       'D001_Empresa' => addslashes($dados['empresa']),
       'D001_Cp' => addslashes($dados['cp']),
       'D001_Morada' => addslashes($dados['morada']),     
       'D001_Cidade' => addslashes($dados['localidade']),
       'D001_Distrito' => addslashes($dados['distrito']),             
       'D001_Telefone' => addslashes($dados['telefone']),
       'D001_Logosite' => addslashes($dados['logo']),
       'D001_Logosite2' => addslashes($dados['logoalt']),
       'D001_Logosocial' => addslashes($dados['logosocial']),
       'D001_Pino' => addslashes($dados['pino']),
       'D001_Favicon' => addslashes($dados['favicon']),             
       'D001_Email' =>  addslashes($dados['email']),
       'D001_Site' =>  addslashes($dados['site']),
       'D001_Obs' => addslashes($dados['obs'])
    ); 

 
   return $this->update($arr,$id);
  }
 
  public function configAlteracao2(array $dados){
    $id = "D001_Uid=".$dados['id']; 

    $arr = array(
      'D001_Fontgoogle' => addslashes(utf8_encode($dados['fontgoogle'])),
      'D001_Fontconfig' => addslashes(utf8_encode($dados['fontconfig'])),
      'D001_Rss' => addslashes(utf8_encode($dados['rss'])),
      'D001_Aovivo' => addslashes(utf8_encode($dados['aovivo'])),
      'D001_Aovivoradio' => addslashes(utf8_encode($dados['aovivoradio'])),
      'D001_Descricao' => addslashes($dados['descricao']),
      'D001_Conteudo' => addslashes($dados['conteudo']),
      'D001_Skypeusu' => addslashes(utf8_encode($dados['skype'])),
      'D001_Whatsapp' => addslashes(utf8_encode($dados['whatsapp'])),
      'D001_Facebook' => addslashes(utf8_encode($dados['facebook'])),
      'D001_Facebooktxt' => addslashes(utf8_encode($dados['facebooktxt'])),
      'D001_Twitter' => addslashes(utf8_encode($dados['twitter'])),
      'D001_Twittertxt' => addslashes(utf8_encode($dados['twittertxt'])),
      'D001_Youtube' => addslashes(utf8_encode($dados['youtube'])),
      'D001_Blog' => addslashes(utf8_encode($dados['blog'])),
      'D001_Google' => addslashes(utf8_encode($dados['google'])),
      'D001_Instagram' => addslashes(utf8_encode($dados['instagram'])),
      'D001_Instagramtxt' => addslashes(utf8_encode($dados['instagramtxt'])),
      'D001_Instagramview' => addslashes(utf8_encode($dados['instagramview'])),               
      'D001_Tema' => $dados['tema'],
      'D001_Tema2' => $dados['tema2'],
      'D001_Latitude' => addslashes(utf8_encode($dados['latitude'])),
      'D001_Longitude' => addslashes(utf8_encode($dados['longitude'])),
      'D001_Googlemap' => addslashes(utf8_encode($dados['googlemap'])),
      'D001_TP_Header' => addslashes($dados['nav'])
    );
      
    return $this->update($arr,$id);
  }

  public function configDeletarIMG($id,$img){
    $line_id = "D001_Uid=".$id; 

    $arr = array(           
        $img => ""
    );   
    return $this->update($arr,$line_id);
  }
 
  public function configAlteracao3(array $dados){
    $id = "D001_Uid=".$dados['id']; 
    $arr = array(
      'D001_PC_status' => addslashes($dados['pcstatus']), 
      'D001_PC_titulo' => addslashes($dados['pctitulo']),
      'D001_PC_descricao' => addslashes($dados['pcdescricao'])       
    );

    return $this->update($arr,$id);
  }

  public function configAlteracao4(array $dados){
    $id = "D001_Uid=".$dados['id']; 
    $arr = array(      
      'D001_Keyvisita' => addslashes($dados['keyvisita']),
      'D001_GA_id' => addslashes($dados['gaid']), 
      'D001_GA_tagscript' => addslashes($dados['gatagscript']),
      'D001_GA_tagiframe' => addslashes($dados['gatagiframe']),
      'D001_FB_pixel' => addslashes($dados['fbpixel']),
      'D001_SMTP_host' => addslashes($dados["smtp_host"]),
      'D001_SMTP_email' => addslashes($dados["smtp_email"]),
      'D001_SMTP_password' => addslashes($dados["smtp_password"]),
      'D001_SMTP_port' => addslashes($dados["smtp_port"])  
    );

    return $this->update($arr,$id);
  }

  public function configAlteracaoCalendario(array $dados){

    $id = "D001_Uid=".$dados['id']; 
    $arr = array(      
      'D001_GA_calendar_id' => addslashes($dados['cfg_ga_calendar_id']),
      'D001_GA_api_key' => addslashes($dados['cfg_ga_api_key']),
      'D001_GA_client_id' => addslashes($dados['cfg_ga_client_id'])
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
      return trim(preg_replace($acentos, array_keys($acentos), htmlentities($str,ENT_NOQUOTES, $enc)));
  }

  
    public function selecionarSlug($id){
      $parm = "SEO_Slug = '".$id."'";     
      return $this->read($parm);
    }
}