<?php
    class configuracao extends App_Controller{
        public function index_action(){
            return $this->View("indexConfig");
        }
    
        //ALTERAR
        public function ajustar(){

            // echo "running ajustar - where is the view?";
            // $config_alt = new App_Model_configModel();
            // $config_lista_alt = $config_alt->configBuscar();
            // $dados['config_lista'] = $config_lista_alt;
            // $this->View("alterarConfig", $dados);
            self::config_1();
        }

        public function config_1(){
            $config_alt = new App_Model_configModel();
            $config_lista_alt = $config_alt->configBuscar();
            $dados['config_lista'] = $config_lista_alt;
            $this->View("alterarConfig1", $dados);
        }

        public function config_2(){
            return false;
            // $config_alt = new App_Model_configModel();
            // $config_lista_alt = $config_alt->configBuscar();
            // $dados['config_lista'] = $config_lista_alt;
            // $this->View("alterarConfig2", $dados);
        }

        public function config_3(){
            $config_alt = new App_Model_configModel();
            $config_lista_alt = $config_alt->configBuscar();
            $dados['config_lista'] = $config_lista_alt;
            $this->View("alterarConfig3", $dados);
        }
          
        public function config_4(){
            $config_alt = new App_Model_configModel();
            $config_lista_alt = $config_alt->configBuscar();
            $dados['config_lista'] = $config_lista_alt;
            $this->View("alterarConfig4", $dados);
        }

        public function config_calendario(){
            $config_alt = new App_Model_configModel();
            $config_lista_alt = $config_alt->configBuscar();
            $dados['config_lista'] = $config_lista_alt;
            $this->View("alterarCalendario", $dados);
        }

        //Listar
        public function listar(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $config_alt = new App_Model_configModel();
            $config_lista_alt = $config_alt->configBuscar();
            $dados['config_lista'] = $config_lista_alt;
            //echo $dados['status'];
            if($dados["status"] == 1):
                $this->View("alterarConfig1", $dados);
            elseif($dados["status"] == 2):
                $this->View("alterarConfig2", $dados);
            elseif($dados["status"] == 3):
                $this->View("alterarConfig3", $dados);
            elseif($dados["status"] == 4):
                $this->View("alterarConfig4", $dados);
            endif;
        }

        //ALTERAÇÃO
        public function alteracao(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $model = new App_Model_configModel();

            
            if($dados['status']==1):
                $dados_config = $model->configSelecionar($dados['id']);

                if(empty($dados["favicon"])): 
                    $dados["favicon"] = $dados_config[0]['D001_Favicon'];
                elseif( !empty($dados_config[0]['D001_Favicon']) && $dados_config[0]['D001_Favicon'] != $dados["favicon"]):
                    unlink($_SERVER['DOCUMENT_ROOT'].'/images/config/'.$dados_config[0]['D001_Favicon']);
                endif; 

                if(empty($dados["logo"])): 
                    $dados["logo"] = $dados_config[0]['D001_Logosite'];
                elseif( !empty($dados_config[0]['D001_Logosite']) && $dados_config[0]['D001_Logosite'] != $dados["logo"]):
                    unlink($_SERVER['DOCUMENT_ROOT'].'/images/config/'.$dados_config[0]['D001_Logosite']);
                endif; 

                if(empty($dados["logoalt"])): 
                    $dados["logoalt"] = $dados_config[0]['D001_Logosite2'];
                elseif( !empty($dados_config[0]['D001_Logosite2']) && $dados_config[0]['D001_Logosite2'] != $dados["logoalt"]):
                    unlink($_SERVER['DOCUMENT_ROOT'].'/images/config/'.$dados_config[0]['D001_Logosite2']);
                endif;

                if(empty($dados["logosocial"])): 
                    $dados["logosocial"] = $dados_config[0]['D001_Logosocial'];
                elseif( !empty($dados_config[0]['D001_Logosocial']) && $dados_config[0]['D001_Logosocial'] != $dados["logosocial"]):
                    unlink($_SERVER['DOCUMENT_ROOT'].'/images/config/'.$dados_config[0]['D001_Logosocial']);
                endif;  

                if(empty($dados["pino"])): 
                    $dados["pino"] = $dados_config[0]['D001_Pino'];
                elseif( !empty($dados_config[0]['D001_Pino']) && $dados_config[0]['D001_Pino'] != $dados["pino"]):
                    unlink($_SERVER['DOCUMENT_ROOT'].'/images/config/'.$dados_config[0]['D001_Pino']);
                endif;  

                $model_update = $model->configAlteracao1($dados);

            elseif($dados['status']==2):
                $model_update = $model->configAlteracao2($dados);
            elseif($dados['status']==3):
                $model_update = $model->configAlteracao3($dados);
            elseif($dados['status']==4):
                $model_update = $model->configAlteracao4($dados);
            elseif ($dados["status"] == "calendario"):
                 $model_update = $model->configAlteracaoCalendario($dados);
            endif;
            //print_r($model_update);
        }

        //DELETAR IMAGEM
        public function del_img(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            $model = new App_Model_configModel();
            $dados = $model->configSelecionar(1);
            unlink($_SERVER['DOCUMENT_ROOT'].'/images/config/'.$dados[0][$ident["img"]]);
            $model_delete = $model->configDeletarIMG(1,$ident["img"]);
            echo $model_delete;
        }
 
        public function consultaCep(){
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            $cep = new App_Controller_cepController();
            $dados_cep = $cep->busca_cep($dados['cep']);
            echo $dados_cep;
        }
  
    }