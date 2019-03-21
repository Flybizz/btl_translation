<?php
    class relatorio extends App_Controller{
        
        public function index_action(){
            
            return $this->View("indexRelatorio");
            
        }        
    

        public function paciente(){            

            return $this->View("pacienteRelatorio");
           
        }
        
        public function pdf(){

                // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            //print_r($dados);

            if($dados["status"] == 0 && trim($dados["datnas"]) == "" && $dados["cidres"] == ""):
               
                $model = new App_Model_pacienteModel();
                $model_fluxo = $model->listaPacienteCadastrado();

            else:

                $model = new App_Model_pacienteModel(); 

                if($dados["datnas"] == " "):
                    $dados["datnas"] = "";
                else:
                    $dados["datnas"] = $dados["datnas"];
                endif;

                $arr = array(
                    'STATUS'=>$dados["status"],
                    'DATNAS'=>$dados["datnas"],
                    'CIDRES'=>$dados["cidres"]
                );
                $model_fluxo = $model->pacienteSelecionarRel($arr);

                //print_r($model_fluxo);

            endif;            
         
            if(isset($model_fluxo)):                  
                
                $dados['relatorio_paciente'] = self::array_sort($model_fluxo,"PRINOM");

                //print_r($dados);
                
                //funcao que chama a view
                return $this->View("relatorioPDF",$dados);

            else:

                $dados['relatorio_epi'] = array();

                //funcao que chama a view
                return $this->View("relatorioPDF",$dados);

            endif;     

        }
        

        public function inserir(){
            
             // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();
            
            //print_r($dados);

            $model = new App_Model_categoriaModel();
            $model_inserir = $model->categoriaCadastrar($dados);
                       
            echo $model_inserir;
            
        }
        
        //ALTERAR
        public function selecionar($view = NULL){            
            
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax("id");
                                   
            $categoria_alt = new App_Model_categoriaModel();
            $categoria_lista_alt = $categoria_alt->categoriaSelecionar($ident);                 
           
      
            //cria o array para a view
            $var = array('cat_id' => $categoria_lista_alt[0]['cat_id'],    
               'cat_status' => $categoria_lista_alt[0]['cat_status'],
               'cat_nome' => strtoupper($categoria_lista_alt[0]['cat_nome'])
               );

            $input1[] = $var;
                                                            
            //endforeach;
            
            //envia cada registro para o final do array
            array_push($input1, $var);
            //retira um elemento no final do array
            array_pop($input1);
            
            $dados['categoria_lista_alt'] = $input1;          
            
            
            //$json = json_encode($input);
            
            //funcao que chama a view
            
            if($view != NULL):
            
                return $dados;
            
            else:
            
                $this->View("alterarCategoria", $dados);
                
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
            
            $model = new App_Model_categoriaModel();
            $model_update = $model->categoriaAlteracao($dados);
                       
            echo $model_update;
            
            
        }
        
        //DELETAR
        public function deletar(){
            
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();
            
            $model = new App_Model_categoriaModel();
            $model_delete = $model->categoriaDeletar($ident);
                       
            echo $model_delete;
            
        }       
        
        /***************     PAGINA     *********************/
        
         public function cadastrado(){
         
            $categoria_cad = new App_Model_categoriaModel();
            $categoria_lista_cad = $categoria_cad->listaCategoriaCadastrado();
                                  
            foreach ($categoria_lista_cad as $categoria):  
                              
               //cria o array para a view
               $var = array('cat_id' => $categoria['cat_id'],    
               'cat_status' => $categoria['cat_status'],
               'cat_nome' => strtoupper($categoria['cat_nome'])
               );
                              
               $input[] = $var;
                                                            
            endforeach;

            if(isset($var)):
            
                //envia cada registro para o final do array
                array_push($input, $var);
                //retira um elemento no final do array
                array_pop($input);
                
                $dados['categoria_lista_cad'] = $input;
                
                //funcao que chama a view
                $this->View("cadastradoCategoria", $dados);
            
            else:

                //funcao que chama a view
                $this->View("cadastradoCategoria");   

            endif;
            
        }  
        
      
        /***************     PAGINA     *********************/
        
        public function epi(){
         
            
            $this->View("epiRelatorio");  

             
            
        }  

        
      


        
        //BLOQUEAR & LIBERAR
        public function bloquear(){
            
            // seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $dados = $id->getParamsAjax();

            //print_r($dados);
            
            $model = new App_Model_clienteModel();
            $model_update = $model->clienteBloquear($dados);
                       
            echo $model_update;
            
        }

        public function array_sort($array, $on, $order=SORT_ASC)
        {
            $new_array = array();
            $sortable_array = array();

            if (count($array) > 0) {
                foreach ($array as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            if ($k2 == $on) {
                                $sortable_array[$k] = $v2;
                            }
                        }
                    } else {
                        $sortable_array[$k] = $v;
                    }
                }

                switch ($order) {
                    case SORT_ASC:
                        asort($sortable_array);
                    break;
                    case SORT_DESC:
                        arsort($sortable_array);
                    break;
                }

                foreach ($sortable_array as $k => $v) {
                    $new_array[$k] = $array[$k];
                }
            }

            return $new_array;
        }
        
    }
