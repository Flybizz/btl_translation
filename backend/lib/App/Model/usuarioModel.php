<?php
class App_Model_usuarioModel extends App_Model{
  public $_tabela = "usuarios";
  public function listaUsuario(){
        $parm = " ORDER BY usu_nome ASC";
       return $this->readorder($parm);
  }
  public function listaUsuarioDefault($sigla){
    $parm = "WHERE usu_Idioma = '".$sigla."' ORDER BY usu_nome ASC";
    return $this->readorder($parm);
  }  
   
  public function countUsuario($cat=null){
        if($cat != null):
          $parm = " usu_Categoria = ".$cat;
        else:
          $parm = null;
        endif;
        return $this->readesp("COUNT(usu_id) as COUNT", $parm, null);
  } 
  public function listUsuario($cat=null){
      
        if($cat != null):
          $parm = "WHERE usu_Categoria = ".$cat." ORDER BY usu_Data DESC LIMIT 2";
        else:
          $parm = " ORDER BY usu_Data DESC LIMIT 2";
        endif;
        return $this->readorder($parm);
  }  
  public function usuarioSelecionarIn($id){
    if(!empty($id)):
    $parm = " usu_id IN (".$id.")";
    $rs = $this->readesp("usu_nome",$parm," ORDER BY usu_nome ASC"); 
    else:
     $rs = "";
    endif;
   
    return $rs;
 }
  public function listaUsuarioNivel($nivel){
    
    $parm = " niv_id = ".$nivel."";    
    return $this->readesp("usu_id, usu_nome", $parm, " ORDER BY usu_nome ASC");
  }  
  public function loadUsuario($offset,$limit,$cat){
        if($cat != null):
          $parm = "WHERE usu_Categoria = ".$cat." ORDER BY usu_Data DESC LIMIT $offset, $limit";
        else:
          $parm = " ORDER BY usu_Data DESC LIMIT $offset, $limit";
        endif;
        return $this->readorder($parm);
  }  
  public function usuarioCadastrar(array $dados){
      if(empty($dados)):
          $return = "Erro: Dados vazios.";
    else:
      
        if(!empty($dados['img'])):
          $image = $dados['img'];
        else:
          $image = "";
        endif;
        $arr = array(  
          'usu_nome' => trim($dados['nome']),
          'usu_funcao' => trim($dados['funcao']),
          'usu_texto' => $dados['texto'],
          'usu_foto' => $image,
          'usu_controle' => $dados['controller'],
          'usu_idioma' => $dados['idioma'],
          'usu_status' =>  $dados['status'],     
          'usu_morada' =>  $dados['morada'],
          'usu_localidade' =>  $dados['localidade'],
          'usu_distrito' =>  $dados['distrito'],
          'usu_cp' =>  $dados['cp'],
          'usu_telefone' =>  $dados['telefone'],
          'usu_telemovel' =>  $dados['telemovel'],
          'usu_email' =>  $dados['email'],
          'usu_website' =>  $dados['website'],
          'usu_login' =>  trim(strtolower($dados['login'])),
          'usu_senha' =>  md5($dados['senha']),
          'niv_id' =>  $dados['nivel'],
          'usu_calendar_id' => $dados['calendar_id']
        );
        $return = $this->insert($arr);
      endif;
      return $return;
  }
    public function usuarioCadastrar2(array $dados){
      if(empty($dados)):
          $return = "Erro: Dados vazios.";
    else:      
        $ref = rand(100, 999999);
        $arr = array(  
          'usu_nome' => trim($dados['nome']),
          'usu_funcao' => "",
          'usu_texto' => "",
          'usu_foto' => "",
          'usu_controle' => "usuario",
          'usu_idioma' => "pt",
          'usu_status' =>  "Sim",     
          'usu_referencia' => $ref,
          'usu_morada' =>  "",
          'usu_localidade' =>  "",
          'usu_distrito' =>  "",
          'usu_cp' =>  "",
          'usu_telefone' =>  $dados['telefone'],
          'usu_telemovel' =>  "",
          'usu_email' =>  $dados['email'],
          'usu_website' =>  "",
          'usu_login' =>  trim(strtolower($dados['login'])),
          'usu_senha' =>  $dados['senha'],
          'niv_id' =>  $dados['nivel'],
          'usu_calendar_id' => $dados['calendar_id']
        );
        $return = $this->insert($arr);
        
      endif;
      return $return;
  }
    public function usuarioSelecionar($id){
      if($id <> null ):
      $parm = "usu_id = ".$id;
      elseif( $id == null ):
      $parm = "";
      endif; 
      return $this->read($parm);
  }
    
  public function usuarioSelecionarRef($ref, $lang){
    $parm = "WHERE usu_id = '".$ref."'";
    return $this->readorder($parm);
  }
  public function usuarioSelecionarLang($controller, $lang){
    $parm = "WHERE usu_Idioma = '".$lang."' AND usu_Controle = '".$controller."'";
    return $this->readorder($parm);
  }
    public function usuarioCadastrarCopy(array $dados){
      if(empty($dados)):
        $return = "Erro: Dados vazios.";
      else:  
        $return = $this->insert($dados);
      endif;
      return $return;
    }
    public function usuarioSelecionarIdioma($id){
      $parm = "usu_Idioma = '".$id."'";       
      return $this->read($parm);
    }
    public function usuarioSelecionarLogin($id){
      $parm = "usu_Login = '".$id."'";       
      return $this->read($parm);
    }
    
    public function usuarioDeletar($id){
      $dados_id = "usu_id =".$id;
      return $this->delete($dados_id,$id);
    }
    public function usuarioDeletarIMG($dados){
      $id = "usu_id=".$dados; 
      $arr = array(           
          'usu_Foto' => ""
      );   
      return $this->update($arr,$id);
    }
    public function usuarioAlteracao(array $dados){
      $id = "usu_id=".$dados['id']; 
      if(empty($dados['senha'])):
        $arr = array(  
          'usu_nome' => trim($dados['nome']),
          'usu_Funcao' => trim($dados['funcao']),
          'usu_Texto' => $dados['texto'],
          'usu_Foto' => $dados['img'],
          'usu_Controle' => $dados['controller'],
          'usu_Idioma' => $dados['idioma'],
          'usu_Status' =>  $dados['status'],    
          'usu_Morada' =>  $dados['morada'],
          'usu_Localidade' =>  $dados['localidade'],
          'usu_Distrito' =>  $dados['distrito'],
          'usu_Cp' =>  $dados['cp'],
          'usu_Telefone' =>  $dados['telefone'],
          'usu_Telemovel' =>  $dados['telemovel'],
          'usu_Email' =>  $dados['email'],
          'usu_Website' =>  $dados['website'],
          'usu_Login' =>  trim(strtolower($dados['login'])),
          'niv_id' =>  $dados['nivel'],
          'usu_calendar_id' => $dados['calendar_id']
        );
      else:
        $arr = array(  
          'usu_nome' => trim($dados['nome']),
          'usu_Funcao' => trim($dados['funcao']),
          'usu_Texto' => $dados['texto'],
          'usu_Foto' => $dados['img'],
          'usu_Controle' => $dados['controller'],
          'usu_Idioma' => $dados['idioma'],
          'usu_Status' =>  $dados['status'],    
          'usu_Morada' =>  $dados['morada'],
          'usu_Localidade' =>  $dados['localidade'],
          'usu_Distrito' =>  $dados['distrito'],
          'usu_Cp' =>  $dados['cp'],
          'usu_Telefone' =>  $dados['telefone'],
          'usu_Telemovel' =>  $dados['telemovel'],
          'usu_Email' =>  $dados['email'],
          'usu_Website' =>  $dados['website'],
          'usu_Login' =>  trim(strtolower($dados['login'])),
          'usu_Senha' =>  md5($dados['senha']),
          'niv_id' =>  $dados['nivel'],
          'usu_calendar_id' => $dados['calendar_id']
        );
      endif;
      return $this->update($arr,$id);
    }
    
    public function usuarioRecuperar($id, $token, $ip){
      $cli_id = "usu_id=".$id;
  
      $arr = array(
        'usu_token'=> md5("flybizz_").$token,
        'usu_time'=> $token,
        'usu_ip'=> $ip,
        'usu_rec_date'=> date("Y-m-d H:m:s")
      );
      return $this->update($arr,$cli_id);
    }
    public function usuarioUpdateSenha($id,$senha){
      $id = "usu_id=".$id;
      $arr = array(
          'usu_senha' => $senha
      );
      return $this->update($arr,$id);
    }
  
    public function usuarioRecuperarClear($id){
      $cli_id = "usu_id=".$id;
  
      $arr = array(
        'usu_token'=> "",
        'usu_time'=> ""
      );
      return $this->update($arr,$cli_id);
    }
  
    public function usuarioSelecionarToken($token){
  
      $parm = "usu_token = '".$token."'";
      return $this->read($parm);
  
    }
    public function usuarioVerifica($email){
      $parm = "usu_login = '".$email."'";
      return $this->read($parm);
    }
}