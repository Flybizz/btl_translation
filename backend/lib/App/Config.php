<?php
    class App_Config
    {
        const NAME_CONFIG_REGISTRY = 'configs';
        
        protected function __construct(){
        }

        public static function factory($filename)
        {
            if ( empty($filename) ){
                throw new Exception("Arquivo de configuração vazio.");
            }
            
            if ( !file_exists($filename) ){
                throw new Exception("O arquivo de configuração não existe ou não foi encontrado.");
            }
            
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            
            switch ($extension) {
                case "ini":
                    return new App_Config_Ini($filename);
                    break;
                case "xml":
                    return new App_Config_Xml($filename);
                    break;
                default:
                    throw new Exception("Arquivo de configuração inválido.");
                    break;
            }
        }
    }