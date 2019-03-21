<?php
class App_Model_formacaoModel extends App_Model{
    public $_tabela = "btl_formacao";
    public function prepararPdf($ref){
      $sql = "select *, u2.usu_nome AS formador_nome from btl_formacao bf
      inner join btl_cliente bc ON bf.form_client_id = bc.cli_referencia
      inner join usuarios u2 ON u2.usu_id = bf.form_formador_id AND u2.niv_id = 4
      inner join usuarios u ON u.usu_id = bc.usu_id AND u.niv_id = 3
      inner join btl_formacao_tipo bft ON bft.form_tipo_id = bf.form_tipo
      inner join btl_distrito bd ON bd.cod_distrito = bc.cli_distrito
      WHERE bf.form_ref = {$ref}";   
      
      return $this->sqlquery($sql);
      //inner join btl_formador bff ON bff.form_id = bf.form_formador_id
    }   
    public function listaFormacao(){
      $parm = " ORDER BY form_nome ASC";
      return $this->readorder($parm);
    }    
    public function listaFormacaoDefault($sigla){
      $parm = "WHERE form_idioma = '".$sigla."' ORDER BY form_nome ASC";
      return $this->readorder($parm);
    }
    //retorna o tipo de formação
    public function get_tipo_formacao(){
      $this->_tabela = "btl_formacao_tipo";
       $parm = " ORDER BY form_tipo_sequence ASC";
       return $this->readorder($parm);
    }
    //retorna o tipo de formação
    public function get_tipo_formacao_by_id($id){
      $this->_tabela = "btl_formacao_tipo";
      $parm = "WHERE form_tipo_id = ".$id."";
      return $this->readorder($parm);     
      $this->_tabela = "btl_formacao";  
    }
    public function get_list_from_client_by_ref($client_ref){
      $this->_tabela = "btl_formacao";
      $parm = "WHERE form_client_id = '".$client_ref."' ORDER BY form_data DESC";
      return $this->readorder($parm);
      
    }
    public function get_list_from_formacao_by_ref($client_ref){
      $this->_tabela = "btl_formacao";
      $parm = "WHERE form_ref = '".$client_ref."'";
      return $this->readorder($parm);      
    }
    public function get_total_etapas(){
      $this->_tabela = "btl_formacao_tipo";
      return $this->readesp("COUNT(form_tipo_id) as COUNT", null, null);
    }
    public function listaFormacaoActive($sigla){
      $parm = "WHERE form_idioma = '".$sigla."' ORDER BY form_ordem ASC";
      return $this->readorder($parm);
    }
    public function formacaoSelecionarIn($id){
        if(!empty($id)):
          $parm = " form_id IN (".$id.")";
          $rs = $this->readesp("form_nome",$parm," ORDER BY form_nome ASC"); 
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
    public function countFormacao($data){        
        return $this->readesp($data);
    }
    public function listFormacao($cat=null){
        if($cat != null):
          $parm = "WHERE form_categoria = ".$cat." ORDER BY form_Data DESC LIMIT 2";
        else:
          $parm = " ORDER BY form_Data DESC LIMIT 2";
        endif;
        return $this->readorder($parm);
    }
    public function loadFormacao($offset,$limit,$cat){
        if($cat != null):
          $parm = "WHERE form_categoria = ".$cat." ORDER BY form_Data DESC LIMIT $offset, $limit";
        else:
          $parm = " ORDER BY form_Data DESC LIMIT $offset, $limit";
        endif;
        return $this->readorder($parm);
    }
    public function formacaoCadastrar(array $dados){
      
      if(empty($dados)):
        $return = "Erro: Dados vazios.";
      else:
        $return = $this->insert($dados);
      endif;
      return $return;
  }
    public function formacaoSelecionar($id){
      $parm = "form_ref = ".$id;
      return $this->read($parm);
  }
  public function formacaoSelecionarRef2($ref){
    $parm = "WHERE form_referencia = '".$ref."' AND form_status = 'true' ";
    return $this->readorder($parm);
  }
  public function formacaoSelecionarRef($ref){
    $parm = "WHERE form_ref = '".$ref."' ";
    return $this->readorder($parm);
  }
  public function formacaoSelecionarLang($controller, $lang){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' ";
    return $this->readorder($parm);
  }
  public function formacaoSelecionarLangHomepage($controller, $lang){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_status = 'true' ORDER BY form_ordem ASC LIMIT 8 ";
    return $this->readorder($parm);
  }
  public function formacaoSelecionarCtrl($ctrl, $ref, $lang){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$ctrl."'";
    return $this->readorder($parm);
  }
  public function formacaoSelecionarCat($controller, $lang, $cat){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_categoria = '".$cat."' ORDER BY form_categoria ASC ";
    return $this->readorder($parm);
  }
  public function formacaoSelecionarSubCat($controller, $lang, $sub){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_Subcategoria = ".$sub." ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }
  public function formacaoSelecionarSubCat2($controller, $lang, $sub){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_Sub2 = '".$sub."' ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }
  public function formacaoSelecionarSubcatFrontend($controller, $lang, $cat, $sub){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_categoria = ".$cat." AND form_Subcategoria = ".$sub." ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }
  public function formacaoSelecionarSubcatFrontend2($controller, $lang, $cat, $sub, $sub2){
    $parm = "WHERE form_idioma = '".$lang."' AND form_controle = '".$controller."' AND form_categoria = ".$cat." AND form_Subcategoria = ".$sub." AND form_Sub2 = ".$sub2." ORDER BY form_ordem ASC ";
    return $this->readorder($parm);
  }
  public function formacaoCadastrarCopy(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $return = $this->insert($dados);
    endif;
    return $return;
  }
  public function formacaoSelecionarIdioma($id){
    $parm = "form_idioma = '".$id."'";
    return $this->read($parm);
  }
    public function formacaoChave($chave, $lang){
      $parm = "form_idioma = '".$lang."' AND form_chave = '".$chave."'";
      return $this->read($parm);
    }
    public function formacaoSelecionarChave($id){
      if($id <> null ):
        $parm = "form_chave = '".$id."'";
      elseif( $id == null ):
        $parm = "";
      endif;
      return $this->read($parm);
    }
    public function formacaoDeletar($id){
      $dados_id = "form_id =".$id;
      return $this->delete($dados_id,$id);
    }
    public function formacaoDeletarIMG($dados){
      $id = "form_id=".$dados;
      $arr = array(
          'form_foto' => ""
      );
      return $this->update($arr,$id);
    }
    public function formacaoDeletarBanner($dados){
      $id = "form_id=".$dados;
      $arr = array(
          'form_banner' => ""
      );
      return $this->update($arr,$id);
    }
    public function formacaoAlteracao(array $dados){
      $r_id = "form_id=".$dados["form_id"];      
      return $this->update($dados, $r_id);
      
    }
    public function formacaoAlteracaoFormando($dados, $ref){
      $r_id = "form_ref=".$ref;
      $arr = array(
        'form_formandos' => $dados
      );
      return $this->update($arr, $r_id);
      
    }
    
    
    public function formacaoAlteracaoBanner(array $dados,$id){
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
