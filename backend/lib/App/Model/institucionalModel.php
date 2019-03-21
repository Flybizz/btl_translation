<?php

class App_Model_institucionalModel extends App_Model{

    public $_tabela = "d007institucional";

    public function listaInstitucional(){

        $parm = " ORDER BY D007_Titulo ASC";
       return $this->readorder($parm);
  }

  public function listaInstitucionalDefault($sigla){

    $parm = "WHERE D007_Idioma = '".$sigla."' ORDER BY D007_Titulo ASC";
    return $this->readorder($parm);

  }  

    public function listaEnd(){

        $parm = "WHERE D007_Destaque = 'Sim' ORDER BY D007_Data DESC LIMIT 4";
      return $this->readorder($parm);

    }

  public function listaEnd2(){

        $parm = "WHERE D007_Destaque = 'Nao' ORDER BY D007_Data DESC LIMIT 2";
      return $this->readorder($parm);

    }
   
    public function countInstitucional($cat=null){

        if($cat != null):
          $parm = " D007_Categoria = ".$cat;
        else:
          $parm = null;
        endif;

        return $this->readesp("COUNT(D007_Uid) as COUNT", $parm, null);
    }  

    public function listInstitucional($cat=null){
      
        if($cat != null):
          $parm = "WHERE D007_Categoria = ".$cat." ORDER BY D007_Data DESC LIMIT 2";
        else:
          $parm = " ORDER BY D007_Data DESC LIMIT 2";
        endif;
        return $this->readorder($parm);
    }  

  public function loadInstitucional($offset,$limit,$cat){

        if($cat != null):
          $parm = "WHERE D007_Categoria = ".$cat." ORDER BY D007_Data DESC LIMIT $offset, $limit";
        else:
          $parm = " ORDER BY D007_Data DESC LIMIT $offset, $limit";
        endif;

        return $this->readorder($parm);
    }  

    public function institucionalCadastrar(array $dados){

      if(empty($dados)):
          $return = "Erro: Dados vazios.";
    else:
      
        if(!empty($dados['img'])):
          $image = $dados['img'];
        else:
          $image = "";
        endif;

        $arr = array(  
      'D007_Titulo' => trim($dados['titulo']),
      'D007_Chave' => $dados['seo_slug'],
      'D007_Chamada' => $dados['chamada'],
      'D007_Texto' => $dados['texto'],
      'D007_Foto' => $image,
      'D007_Controle' => $dados['controller'],
      'D007_Idioma' => $dados['idioma'],
      'D007_Destaque' =>  $dados['destaque'],
      'SEO_Titulo' =>  $dados['seo_titulo'],
      'SEO_Descricao' =>  $dados['seo_descricao'],
      'SEO_Slug' =>  $dados['seo_slug'],
      'SEO_Key' =>  $dados['seo_key'],
      'D007_Referencia' => $dados['referencia']
        );

        $return = $this->insert($arr);
      endif;
      return $return;

  }

    public function institucionalSelecionar($id){
      if($id <> null ):
      $parm = "D007_Uid = ".$id;
      elseif( $id == null ):
      $parm = "";
      endif; 
      return $this->read($parm);
  }
    
  public function institucionalSelecionarRef($ref, $lang){
    $parm = "WHERE D007_Idioma = '".$lang."' AND D007_Referencia = '".$ref."'";
    return $this->readorder($parm);
  }

  public function institucionalSelecionarLang($controller, $lang){
    $parm = "WHERE D007_Idioma = '".$lang."' AND D007_Controle = '".$controller."'";
    return $this->readorder($parm);
  }

    public function institucionalChave($chave){

      $parm = "D007_Chave = '".$chave."'"; 
      return $this->read($parm);

    }

    public function institucionalSelecionarChave($id){

      if($id <> null ):

        $parm = "D007_chave = '".$id."'";

      elseif( $id == null ):

        $parm = "";

      endif;
     
      return $this->read($parm);

    }



    public function institucionalDeletar($id){

      $dados_id = "D007_Uid =".$id;

      return $this->delete($dados_id,$id);

    }

    public function institucionalDeletarIMG($dados){

      $id = "D007_Uid=".$dados; 
      $arr = array(           
          'D007_Foto' => ""
      );   

      return $this->update($arr,$id);

    }

    public function institucionalAlteracao(array $dados){

      $id = "D007_Uid=".$dados['id']; 
      $arr = array(
      'D007_Titulo' => trim($dados['titulo']),
      'D007_Chave' => $dados['seo_slug'],
      'D007_Chamada' => $dados['chamada'],
      'D007_Texto' => $dados['texto'],
      'D007_Foto' => $dados['img'],
      'D007_Controle' => $dados['controller'],
      'D007_Idioma' => $dados['idioma'],
      'D007_Destaque' =>  $dados['destaque'],
      'SEO_Titulo' =>  $dados['seo_titulo'],
      'SEO_Descricao' =>  $dados['seo_descricao'],
      'SEO_Slug' =>  $dados['seo_slug'],
      'SEO_Key' =>  $dados['seo_key']
      );   

      return $this->update($arr,$id);

    }

}