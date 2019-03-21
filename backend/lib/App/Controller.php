<?php

    Class App_Controller extends App_System{
                      
        protected function View($nome, $var = null){               
            
            //cria uma variavel 
            //e insere um prefixo para indentifica-la
            
            if( is_array($var) && count($var) > 0)                
                extract ($var, EXTR_PREFIX_ALL, 'view');
            
            require_once VIEWS . $nome . '.phtml';
        }

        public function load_view($nome, $var = null){
                   
            if( is_array($var) && count($var) > 0)
                extract ($var, EXTR_PREFIX_ALL, 'view');

            $view_file = $nome . '.phtml';
            if(file_exists($view_file)){

                ob_start();
                $dados = $var; //will be accessible within the view
                require_once $view_file;                
                $html = ob_get_clean();
                return $html;

            }
            else
                echo "Ficheiro de view não encontrado: <strong>".$view_file."</strong>";
        }

        protected function Site($nome, $var = null){
            
            //cria uma variavel 
            //e insere um prefixo para indentifica-la
            
            if( is_array($var) && count($var) > 0)
                extract ($var, EXTR_PREFIX_ALL, 'site');
            
            require_once SITES . $nome . '.phtml';
        }

        /**/
        protected function removerAcentos($string, $slug = false) {

            $string = strtolower($string);
            // Código ASCII das vogais
            $ascii['a'] = range(224, 230);
            $ascii['e'] = range(232, 235);
            $ascii['i'] = range(236, 239);
            $ascii['o'] = array_merge(range(242, 246), array(240, 248));
            $ascii['u'] = range(249, 252);

            // Código ASCII dos outros caracteres
            $ascii['b'] = array(223);
            $ascii['c'] = array(231);
            $ascii['d'] = array(208);
            $ascii['n'] = array(241);
            $ascii['y'] = array(253, 255);

            foreach ($ascii as $key=>$item) {
            $acentos = '';
            foreach ($item AS $codigo) $acentos .= chr($codigo);
            $troca[$key] = '/['.$acentos.']/i';
            }

            $string = preg_replace(array_values($troca), array_keys($troca), $string);

            // Slug?
            if ($slug) {
            // Troca tudo que não for letra ou número por um caractere ($slug)
            $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
            // Tira os caracteres ($slug) repetidos
            $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
            $string = trim($string, $slug);
            }

            $string = preg_replace("/[^a-zA-Z0-9\s]/", "", $string);
            $string = trim($string);

            return $string;

          }
        
    }
