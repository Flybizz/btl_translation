<?php
class App_Model_formandoModel extends App_Model{
    public $_tabela = "btl_formandos";
    public function listaFormando(){
      $parm = " ORDER BY form_nome ASC";
      return $this->readorder($parm);
    }    
    public function listaFormandoDefault($sigla){
      $parm = "WHERE form_idioma = '".$sigla."' ORDER BY form_nome ASC";
      return $this->readorder($parm);
    }

    public function listaFormandoActive($sigla){
      $parm = "WHERE form_idioma = '".$sigla."' ORDER BY form_ordem ASC";
      return $this->readorder($parm);
    }

    public function formandoSelecionarIn($id){
        if(!empty($id)):
          $parm = " form_id IN (".$id.")";
          $rs = $this->readesp("*",$parm," ORDER BY form_nome ASC"); 
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

    public function loadFormando($offset,$limit,$cat){
        if($cat != null):
          $parm = "WHERE form_categoria = ".$cat." ORDER BY form_Data DESC LIMIT $offset, $limit";
        else:
          $parm = " ORDER BY form_Data DESC LIMIT $offset, $limit";
        endif;
        return $this->readorder($parm);
    }

    public function cadastrarFormando($dados){
     
      $arr = array(
        "form_nome" => $dados["form_nome"],
        "form_email" => $dados["form_email"],
        "form_nif" => $dados["form_nif"]
      );

      $this->insert($arr);

      //retornar o id
      $inf = "MAX(form_id) AS id";
      $order = " ORDER BY form_id DESC";
      $id = $this->readesp($inf, NULL, $order);
      return $id[0]["id"];
      
    }

    public function formandoCadastrar(array $dados){
      if(empty($dados)):
        $return = "Erro: Dados vazios.";
      else:

        $chave = parent::removerAcentos(utf8_decode($dados['titulo']));
        
        $arr = $dados; //passagem directa neste caso

        // $arr = array(
        //   'form_nome' => addslashes(trim($dados['titulo'])),          
        //   'form_chave' => $chave,
        //   'form_chamada' => addslashes($dados['chamada']),
        //   'form_texto' => addslashes($dados['texto']),
        //   'form_foto' => $image,
        //   'form_controle' => $dados['controller'],
        //   'form_idioma' => $dados['idioma'],
        //   'form_status' =>  $dados['destaque'],          
        //   'form_ordem' =>  $dados['ordem'],
        //   'form_preco' =>  $dados['preco'],          
        //   'form_referencia' => $dados['referencia']          
        // );
        $return = $this->insert($arr);
      endif;
      return $return;
  }
  
  public function formandoSelecionar($id){
    $parm = "form_id = ".$id;
    return $this->read($parm);
  }

  public function formandoSelecionarNIF($id){
    $parm = "form_nif = ".$id;
    return $this->read($parm);
  }

  public function formandoSelecionarRef2($ref){
    $parm = "WHERE form_referencia = '".$ref."' AND form_status = 'true' ";
    return $this->readorder($parm);
  }
  public function formandoSelecionarRef($ref, $lang){
    $parm = "WHERE form_idioma = '".$lang."' AND form_referencia = '".$ref."' ";
    return $this->readorder($parm);
  }
  public function formandoSelecionarLang($controller, $lang){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' ";
    return $this->readorder($parm);
  }

  public function formandoSelecionarLangHomepage($controller, $lang){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_status = 'true' ORDER BY form_ordem ASC LIMIT 8 ";
    return $this->readorder($parm);
  }

  public function formandoSelecionarCtrl($ctrl, $ref, $lang){


    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$ctrl."'";
    return $this->readorder($parm);
  }

  public function formandoSelecionarCat($controller, $lang, $cat){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_categoria = '".$cat."' ORDER BY form_categoria ASC ";
    return $this->readorder($parm);
  }

  public function formandoSelecionarSubCat($controller, $lang, $sub){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_Subcategoria = ".$sub." ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }

  public function formandoSelecionarSubCat2($controller, $lang, $sub){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_Sub2 = '".$sub."' ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }

  public function formandoSelecionarSubcatFrontend($controller, $lang, $cat, $sub){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_categoria = ".$cat." AND form_Subcategoria = ".$sub." ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }
  public function formandoSelecionarSubcatFrontend2($controller, $lang, $cat, $sub, $sub2){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_categoria = ".$cat." AND form_Subcategoria = ".$sub." AND form_Sub2 = ".$sub2." ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }

  public function formandoCadastrarCopy(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $return = $this->insert($dados);
    endif;
    return $return;
  }

  public function formandoSelecionarIdioma($id){
    $parm = "form_idioma = '".$id."'";
    return $this->read($parm);
  }


    public function formandoChave($chave, $lang){
      $parm = "form_idioma = '".$lang."' AND form_chave = '".$chave."'";
      return $this->read($parm);
    }
    public function formandoSelecionarChave($id){
      if($id <> null ):
        $parm = "form_chave = '".$id."'";
      elseif( $id == null ):
        $parm = "";
      endif;

      return $this->read($parm);
    }
    public function formandoDeletar($id){
      $dados_id = "form_id =".$id;
      return $this->delete($dados_id,$id);
    }
    public function formandoDeletarIMG($dados){
      $id = "form_id=".$dados;
      $arr = array(
          'form_foto' => ""
      );
      return $this->update($arr,$id);
    }
    public function formandoDeletarBanner($dados){
      $id = "form_id=".$dados;
      $arr = array(
          'form_banner' => ""
      );
      return $this->update($arr,$id);
    }
    public function formandoAlteracao(array $dados){

      $arr = array(
        "form_nome" => $dados["form_nome"],
        "form_email" => $dados["form_email"]
      );

      if($this->update($arr, " form_nif = " . $dados["form_nif"])){
        //retornar o id
        return $dados["form_nif"];
      }
      return false;      

    }

    public function formandoAlteracaoBanner(array $dados,$id){
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
