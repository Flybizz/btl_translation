<?php
class App_Model_produtogaleriaModel extends App_Model{
    public $_tabela = "btl_produto_galeria";
    public function listaPortfolio(){
        $parm = " ORDER BY prod_titulo ASC";
       return $this->readorder($parm);
  }
  public function listaPortfolioDefault($sigla){
    $parm = "WHERE prod_idioma = '".$sigla."' ORDER BY prod_titulo ASC";
    return $this->readorder($parm);
  }  
    public function listaEnd(){
        $parm = "WHERE prod_status = 'true' ORDER BY prod_data DESC LIMIT 4";
      return $this->readorder($parm);
    }
  public function listaEnd2(){
        $parm = "WHERE prod_status = 'false' ORDER BY prod_data DESC LIMIT 2";
      return $this->readorder($parm);
    }
   
    public function countPortfolio($cat=null){
        if($cat != null):
          $parm = " prod_categoria = ".$cat;
        else:
          $parm = null;
        endif;
        return $this->readesp("COUNT(prod_id) as COUNT", $parm, null);
    }  
    public function listPortfolio($cat=null){
      
        if($cat != null):
          $parm = "WHERE prod_categoria = ".$cat." ORDER BY prod_data DESC LIMIT 2";
        else:
          $parm = " ORDER BY prod_data DESC LIMIT 2";
        endif;
        return $this->readorder($parm);
    }  
  public function loadPortfolio($offset,$limit,$cat){
        if($cat != null):
          $parm = "WHERE prod_categoria = ".$cat." ORDER BY prod_data DESC LIMIT $offset, $limit";
        else:
          $parm = " ORDER BY prod_data DESC LIMIT $offset, $limit";
        endif;
        return $this->readorder($parm);
    }  
    public function produtoCadastrar(array $dados){
      if(empty($dados)):
          $return = "Erro: Dados vazios.";
    else:
     
      $arr = array(  
      'prod_titulo' => "",
      'prod_chave' => "",
      'prod_alternativo' => "",
      'prod_foto' => str_replace(" ", "-", $dados['img']),
      'prod_controle' => $dados['controller'],
      'prod_idioma' => $dados['idioma'],
      'prod_status' =>  "false",
      'prod_ordem' =>  0,
      'prod_referencia' => $dados['referencia']
        );
        $return = $this->insert($arr);
      endif;

      return $return;
  }
    public function produtoSelecionar($id){
      if($id <> null ):
      $parm = "prod_id = ".$id;
      elseif( $id == null ):
      $parm = "";
      endif; 
      return $this->read($parm);
  }
   
  public function produtoSelecionarRef2($ref){
    $parm = "WHERE prod_referencia = '".$ref."'";
    return $this->readorder($parm);
  }
  public function produtoSelecionarRef($ref, $lang){
    $parm = "WHERE prod_idioma = '".$lang."' AND prod_referencia = '".$ref."' ORDER BY prod_ordem ASC";
    return $this->readorder($parm);
  }
  public function produtoSelecionarLang($controller, $lang, $ref){
    $parm = "WHERE prod_idioma = '".$lang."' AND prod_controle = '".$controller."' AND prod_referencia = '".$ref."' ORDER BY prod_ordem ASC";
    return $this->readorder($parm);
  }
  public function produtoSelecionarCtrl($ctrl, $ref, $lang){
    $parm = "WHERE prod_idioma = '".$lang."' AND prod_tipo = 'produtos'";
    return $this->readorder($parm);
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
      $id = "prod_foto = '".$dados."'";   
      return $this->delete($id);
    }
    public function produtoDeletarBanner($dados){
      $id = "prod_id=".$dados; 
      $arr = array(           
          'prod_Banner' => ""
      );   
      return $this->update($arr,$id);
    }

    public function produtoAlteracao(array $dados){
      $id = "prod_id=".$dados['id']; 
      $arr = array(
      'prod_titulo' => trim($dados['titulo']),
      'prod_alternativo' => $dados['alternativo'],
      'prod_status' =>  $dados['destaque']
      );   
      return $this->update($arr,$id);
    }

    public function produtoAlteracaoBanner(array $dados,$id){
      $r_id = "prod_id=".$id; 
      $arr = array(
      'prod_Banner' => $dados['img']
      );   
      return $this->update($arr,$r_id);
    }

    public function produtoOrdenar($order, $id, $idioma) {
      $id = "prod_id=".$id." AND prod_idioma= '".$idioma."'";
      $arr = array('prod_ordem' => $order);
      return $this-> update($arr, $id);
    }

    
    public function selecionarSlug($id){
      $parm = "SEO_Slug = '".$id."'";     
      return $this->read($parm);
    }
}