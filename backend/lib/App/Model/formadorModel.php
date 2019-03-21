<?php
class App_Model_formadorModel extends App_Model{
    public $_tabela = "usuarios";
    public function listaFormador(){
      $parm = " WHERE niv_id = 4 ORDER BY usu_nome ASC";
       return $this->readorder($parm);
    }    
    public function listaFormadorDefault($sigla){
      $parm = "WHERE form_idioma = '".$sigla."' ORDER BY usu_nome ASC";
      return $this->readorder($parm);
    }

    public function listaFormadorActive($sigla){
      $parm = "WHERE form_idioma = '".$sigla."' ORDER BY form_ordem ASC";
      return $this->readorder($parm);
    }

    public function formadorSelecionarIn($id){
        if(!empty($id)):
          $parm = " form_id IN (".$id.")";
          $rs = $this->readesp("usu_nome",$parm," ORDER BY usu_nome ASC"); 
        else:
          $rs = "";
        endif;	    
        return $rs;
    }

    public function listaEnd(){
        $parm = "WHERE form_status = 'true' ORDER BY form_id DESC LIMIT 4";
      return $this->readorder($parm);
    }

    public function listaEnd2(){
        $parm = "WHERE form_status = 'false' ORDER BY form_id DESC LIMIT 2";
      return $this->readorder($parm);
    }

    public function countFormador($cat=null){
        if($cat != null):
          $parm = " form_categoria = ".$cat;
        else:
          $parm = null;
        endif;
        return $this->readesp("COUNT(form_id) as COUNT", $parm, null);
    }

    public function listFormador($cat=null){
        if($cat != null):
          $parm = "WHERE form_categoria = ".$cat." ORDER BY form_Data DESC LIMIT 2";
        else:
          $parm = " ORDER BY form_Data DESC LIMIT 2";
        endif;
        return $this->readorder($parm);
    }

    public function loadFormador($offset,$limit,$cat){
        if($cat != null):
          $parm = "WHERE form_categoria = ".$cat." ORDER BY form_Data DESC LIMIT $offset, $limit";
        else:
          $parm = " ORDER BY form_Data DESC LIMIT $offset, $limit";
        endif;
        return $this->readorder($parm);
    }

    public function formadorCadastrar(array $dados){
      if(empty($dados)):
        $return = "Erro: Dados vazios.";
      else:

        if(!empty($dados['img'])):
          $image = $dados['img'];
        else:
          $image = "";
        endif;
        $chave = parent::removerAcentos(utf8_decode($dados['titulo']));
        $arr = array(
          'usu_nome' => addslashes(trim($dados['titulo'])),          
          'form_chave' => $chave,
          'form_chamada' => addslashes($dados['chamada']),
          'form_texto' => addslashes($dados['texto']),
          'form_foto' => $image,
          'form_controle' => $dados['controller'],
          'form_idioma' => $dados['idioma'],
          'form_status' =>  $dados['destaque'],          
          'form_ordem' =>  $dados['ordem'],
          'form_preco' =>  $dados['preco'],          
          'form_referencia' => $dados['referencia']          
        );
        $return = $this->insert($arr);
      endif;
      return $return;
  }
    public function formadorSelecionar($id){
      $parm = "form_id = ".$id;
      return $this->read($parm);
  }

  public function formadorSelecionarRef2($ref){
    $parm = "WHERE form_referencia = '".$ref."' AND form_status = 'true' ";
    return $this->readorder($parm);
  }
  public function formadorSelecionarRef($ref, $lang){
    $parm = "WHERE form_idioma = '".$lang."' AND form_referencia = '".$ref."' ";
    return $this->readorder($parm);
  }
  public function formadorSelecionarLang($controller, $lang){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' ";
    return $this->readorder($parm);
  }

  public function formadorSelecionarLangHomepage($controller, $lang){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_status = 'true' ORDER BY form_ordem ASC LIMIT 8 ";
    return $this->readorder($parm);
  }

  public function formadorSelecionarCtrl($ctrl, $ref, $lang){


    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$ctrl."'";
    return $this->readorder($parm);
  }

  public function formadorSelecionarCat($controller, $lang, $cat){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_categoria = '".$cat."' ORDER BY form_categoria ASC ";
    return $this->readorder($parm);
  }

  public function formadorSelecionarSubCat($controller, $lang, $sub){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_Subcategoria = ".$sub." ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }

  public function formadorSelecionarSubCat2($controller, $lang, $sub){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_Sub2 = '".$sub."' ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }

  public function formadorSelecionarSubcatFrontend($controller, $lang, $cat, $sub){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_categoria = ".$cat." AND form_Subcategoria = ".$sub." ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }
  public function formadorSelecionarSubcatFrontend2($controller, $lang, $cat, $sub, $sub2){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_categoria = ".$cat." AND form_Subcategoria = ".$sub." AND form_Sub2 = ".$sub2." ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }

  public function formadorCadastrarCopy(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $return = $this->insert($dados);
    endif;
    return $return;
  }

  public function formadorSelecionarIdioma($id){
    $parm = "form_idioma = '".$id."'";
    return $this->read($parm);
  }


    public function formadorChave($chave, $lang){
      $parm = "form_idioma = '".$lang."' AND form_chave = '".$chave."'";
      return $this->read($parm);
    }
    public function formadorSelecionarChave($id){
      if($id <> null ):
        $parm = "form_chave = '".$id."'";
      elseif( $id == null ):
        $parm = "";
      endif;

      return $this->read($parm);
    }
    public function formadorDeletar($id){
      $dados_id = "form_id =".$id;
      return $this->delete($dados_id,$id);
    }
    public function formadorDeletarIMG($dados){
      $id = "form_id=".$dados;
      $arr = array(
          'form_foto' => ""
      );
      return $this->update($arr,$id);
    }
    public function formadorDeletarBanner($dados){
      $id = "form_id=".$dados;
      $arr = array(
          'form_banner' => ""
      );
      return $this->update($arr,$id);
    }
    public function formadorAlteracao(array $dados){
      $id = "form_id=".$dados['id'];
      $chave = parent::removerAcentos(utf8_decode($dados['titulo']));
      $arr = array(
        'usu_nome' => trim($dados['titulo']),        
        'form_chave' => $chave,
        'form_chamada' => $dados['chamada'],
        'form_texto' => $dados['texto'],
        'form_foto' => $dados['img'],
        'form_controle' => $dados['controller'],
        'form_idioma' => $dados['idioma'],
        'form_status' =>  $dados['destaque'],        
        'form_ordem' =>  $dados['ordem'],
        'form_preco' =>  $dados['preco']                
      );
      return $this->update($arr,$id);
    }
    public function formadorAlteracaoBanner(array $dados,$id){
      $r_id = "form_id=".$id;
      $arr = array(
      'form_banner' => $dados['img']
      );
      return $this->update($arr,$r_id);
    }


    public function selecionarSlug($id){
      $parm = "SEO_Slug = '".$id."'";
      return $this->read($parm);
    }
}
