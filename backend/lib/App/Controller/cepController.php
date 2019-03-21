<?php
class App_controller_cepController extends App_Controller{

    public function simple_curl($url,$post=array(),$get=array()){
        $url = explode('?',$url,2);
        if(count($url)===2){
            $temp_get = array();
            parse_str($url[1],$temp_get);
            $get = array_merge($get,$temp_get);
        }

        $ch = curl_init($url[0]."?".http_build_query($get));
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec ($ch);
    }

    public function busca_cep($cep){  

        $html = self::simple_curl('http://m.correios.com.br/movel/buscaCepConfirma.do',array(
            'cepEntrada'=>urlencode($cep),
            'tipoCep'=>'',
            'cepTemp'=>'',
            'metodo'=>'buscarCep'
        ));

        preg_match_all('/<span class=\"respostadestaque\">(.*?)<\/span>/s', $html, $matches, PREG_SET_ORDER);

        $dados['cidade/uf'] = explode('/',$matches[2][1]);
        $dados['cidade'] = trim($dados['cidade/uf'][0]);
        $dados['uf'] = trim($dados['cidade/uf'][1]);
        $varendereco = trim($matches[0][1]);
        $varbairro = $matches[1][1];
        $varcidade = $dados['cidade'];
        $cep = $matches[3][1];
        
        if(empty($dados['cidade/uf'])):
            $resultado = array(
                'cep'         => "",
                'uf'          => "",
                'cidade'      => "",
                'bairro'      => "",
                'logradouro'  => ""
            );
        else:
            $resultado = array(
                'cep'         => urlencode($cep),
                'uf'          => strtoupper($dados['uf']),
                'cidade'      => strtoupper(parent::removerAcentos($varcidade)),
                'bairro'      => strtoupper(parent::removerAcentos($varbairro)),
                'logradouro'  => strtoupper(parent::removerAcentos($varendereco))
            );
        endif;
        
        echo json_encode($resultado);

    }

}