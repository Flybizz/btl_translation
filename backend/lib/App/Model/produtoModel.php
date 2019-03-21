<?php
class App_Model_produtoModel extends App_Model{
    public $_tabela = "btl_produto";
    public function listaProduto(){
      $parm = " ORDER BY prod_nome ASC";
       return $this->readorder($parm);
    }    
    public function listaProdutoDefault($sigla){
      $parm = "WHERE prod_idioma = '".$sigla."' ORDER BY prod_nome ASC";
      return $this->readorder($parm);
    }

    public function listaProdutoActive($sigla){
      $parm = "WHERE prod_idioma = '".$sigla."' ORDER BY prod_ordem ASC";
      return $this->readorder($parm);
    }

    public function produtoSelecionarIn($id){
        if(!empty($id)):
          $parm = " prod_id IN (".$id.")";
          $rs = $this->readesp("prod_nome",$parm," ORDER BY prod_nome ASC"); 
        else:
          $rs = "";
        endif;	    
        return $rs;
    }

    public function listaEnd(){
        $parm = "WHERE prod_status = 'true' ORDER BY prod_id DESC LIMIT 4";
      return $this->readorder($parm);
    }

    public function listaEnd2(){
        $parm = "WHERE prod_status = 'false' ORDER BY prod_id DESC LIMIT 2";
      return $this->readorder($parm);
    }

    public function countProduto($cat=null){
        if($cat != null):
          $parm = " prod_categoria = ".$cat;
        else:
          $parm = null;
        endif;
        return $this->readesp("COUNT(prod_id) as COUNT", $parm, null);
    }

    public function listProduto($cat=null){
        if($cat != null):
          $parm = "WHERE prod_categoria = ".$cat." ORDER BY prod_Data DESC LIMIT 2";
        else:
          $parm = " ORDER BY prod_Data DESC LIMIT 2";
        endif;
        return $this->readorder($parm);
    }

    public function loadProduto($offset,$limit,$cat){
        if($cat != null):
          $parm = "WHERE prod_categoria = ".$cat." ORDER BY prod_Data DESC LIMIT $offset, $limit";
        else:
          $parm = " ORDER BY prod_Data DESC LIMIT $offset, $limit";
        endif;
        return $this->readorder($parm);
    }

    public function produtoCadastrar(array $dados){
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
          'prod_nome' => addslashes(trim($dados['titulo'])),          
          'prod_chave' => $chave,
          'prod_chamada' => addslashes($dados['chamada']),
          'prod_texto' => addslashes($dados['texto']),
          'prod_foto' => $image,
          'prod_controle' => $dados['controller'],
          'prod_idioma' => $dados['idioma'],
          'prod_status' =>  $dados['destaque'],          
          'prod_ordem' =>  $dados['ordem'],
          'prod_preco' =>  $dados['preco'],          
          'prod_referencia' => $dados['referencia']          
        );
        $return = $this->insert($arr);
      endif;
      return $return;
  }
    public function produtoSelecionar($id){
      $parm = "prod_id = ".$id;
      return $this->read($parm);
  }

  public function produtoSelecionarRef2($ref){
    $parm = "WHERE prod_referencia = '".$ref."' AND prod_status = 'true' ";
    return $this->readorder($parm);
  }
  public function produtoSelecionarRef($ref, $lang){
    $parm = "WHERE prod_idioma = '".$lang."' AND prod_referencia = '".$ref."' ";
    return $this->readorder($parm);
  }
  public function produtoSelecionarLang($controller, $lang){
    $parm = "WHERE prod_idioma = '".$lang."' AND prod_controle = '".$controller."' ";
    return $this->readorder($parm);
  }

  public function produtoSelecionarLangHomepage($controller, $lang){
    $parm = "WHERE prod_idioma = '".$lang."' AND prod_controle = '".$controller."' AND prod_status = 'true' ORDER BY prod_ordem ASC LIMIT 8 ";
    return $this->readorder($parm);
  }

  public function produtoSelecionarCtrl($ctrl, $ref, $lang){


    $parm = "WHERE prod_idioma = '".$lang."' AND prod_controle = '".$ctrl."'";
    return $this->readorder($parm);
  }

  public function produtoSelecionarCat($controller, $lang, $cat){
    $parm = "WHERE prod_idioma = '".$lang."' AND prod_controle = '".$controller."' AND prod_categoria = '".$cat."' ORDER BY prod_categoria ASC ";
    return $this->readorder($parm);
  }

  public function produtoSelecionarSubCat($controller, $lang, $sub){
    $parm = "WHERE prod_idioma = '".$lang."' AND prod_controle = '".$controller."' AND prod_Subcategoria = ".$sub." ORDER BY prod_ordem ASC ";
    return $this->readorder($parm);
  }

  public function produtoSelecionarSubCat2($controller, $lang, $sub){
    $parm = "WHERE prod_idioma = '".$lang."' AND prod_controle = '".$controller."' AND prod_Sub2 = '".$sub."' ORDER BY prod_ordem ASC ";
    return $this->readorder($parm);
  }

  public function produtoSelecionarSubcatFrontend($controller, $lang, $cat, $sub){
    $parm = "WHERE prod_idioma = '".$lang."' AND prod_controle = '".$controller."' AND prod_categoria = ".$cat." AND prod_Subcategoria = ".$sub." ORDER BY prod_ordem ASC ";
    return $this->readorder($parm);
  }
  public function produtoSelecionarSubcatFrontend2($controller, $lang, $cat, $sub, $sub2){
    $parm = "WHERE prod_idioma = '".$lang."' AND prod_controle = '".$controller."' AND prod_categoria = ".$cat." AND prod_Subcategoria = ".$sub." AND prod_Sub2 = ".$sub2." ORDER BY prod_ordem ASC ";
    return $this->readorder($parm);
  }

  public function produtoCadastrarCopy(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $return = $this->insert($dados);
    endif;
    return $return;
  }

  public function produtoSelecionarIdioma($id){
    $parm = "prod_idioma = '".$id."'";
    return $this->read($parm);
  }


    public function produtoChave($chave, $lang){
      $parm = "prod_idioma = '".$lang."' AND prod_chave = '".$chave."'";
      return $this->read($parm);
    }
    public function produtoSelecionarChave($id){
      if($id <> null ):
        $parm = "prod_chave = '".$id."'";
      elseif( $id == null ):
        $parm = "";
      endif;

      return $this->read($parm);
    }
    public function produtoDeletar($id){
      $dados_id = "prod_id =".$id;
      return $this->delete($dados_id,$id);
    }
    public function produtoDeletarIMG($dados){
      $id = "prod_id=".$dados;
      $arr = array(
          'prod_foto' => ""
      );
      return $this->update($arr,$id);
    }
    public function produtoDeletarBanner($dados){
      $id = "prod_id=".$dados;
      $arr = array(
          'prod_banner' => ""
      );
      return $this->update($arr,$id);
    }
    public function produtoAlteracao(array $dados){
      $id = "prod_id=".$dados['id'];
      $chave = parent::removerAcentos(utf8_decode($dados['titulo']));
      $arr = array(
        'prod_nome' => trim($dados['titulo']),        
        'prod_chave' => $chave,
        'prod_chamada' => $dados['chamada'],
        'prod_texto' => $dados['texto'],
        'prod_foto' => $dados['img'],
        'prod_controle' => $dados['controller'],
        'prod_idioma' => $dados['idioma'],
        'prod_status' =>  $dados['destaque'],        
        'prod_ordem' =>  $dados['ordem'],
        'prod_preco' =>  $dados['preco']                
      );
      return $this->update($arr,$id);
    }
    public function produtoAlteracaoBanner(array $dados,$id){
      $r_id = "prod_id=".$id;
      $arr = array(
      'prod_banner' => $dados['img']
      );
      return $this->update($arr,$r_id);
    }


    public function selecionarSlug($id){
      $parm = "SEO_Slug = '".$id."'";
      return $this->read($parm);
    }
}
