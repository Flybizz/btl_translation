<?php
    class header extends App_Controller{
        
        public function index_action(){


            $dados = new App_Model_configModel();

            $dados['header'] = $dados->configBuscar();

            print_r($dados);
            
            return $this->View("header",$dados);            
            
        }
        
    }
