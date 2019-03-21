<?php
class seo extends App_Controller{
    public function index_action(){
       
    }

    public function seoTitulo(){

  
    }

    public function seoDescricao(){
        
    }

    public function seoSlug(){
        $frase = self::removerAcentos2($_POST['frase']);
        echo $frase;        
    }

    public function seoKey(){
            
    }

    public function verifica(){   

         // seta o id do cliente
        $id = new App_System();
        $id->_urlAjax = $_POST['url'];
        $id->setExplodeAjax();
        $id->setControllerAjax();
        $id->setActionAjax();
        $id->setParamsAjax();
        $dados = $id->getParamsAjax();

        $key = explode(" ", str_replace("?", "", strtolower(trim($_POST['key'])) ));
        $frase = explode(" ", str_replace("?", "", strtolower(trim($_POST['frase'])) ));
        $contem = "";
        foreach($key as $chave):
            if (in_array($chave, $frase)) {
              $contem .= $chave.",";
            }
        endforeach;
        $resultado = substr($contem,0,-1);
        echo $resultado;           
    }

    public function verificaSlug(){   
         // seta o id do cliente
        $id = new App_System();
        $id->_urlAjax = $_POST['url'];
        $id->setExplodeAjax();
        $id->setControllerAjax();
        $id->setActionAjax();
        $id->setParamsAjax();
        $dados = $id->getParamsAjax();

        $key_slug = self::removerAcentos2($dados['key']);

        $key =  explode(" ", $key_slug);
        $frase = explode("-", $dados['frase']);

        print_r($key);
        print_r($frase);

        $contem = "";
        foreach($key as $chave):
            if (in_array($chave, $frase)) {
              $contem .= $chave.",";
            }
        endforeach;
        $resultado = substr($contem,0,-1);
        //echo $resultado;           
    }

    public function removerAcentos2($string){
        $string = preg_replace("`\[.*\]`U","",$string);
        $string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
        $string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);
        return strtolower(trim($string, '-'));
    }

}