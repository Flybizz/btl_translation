<?php
    class App_Model_pedidoModel extends App_Model{
        
        public $_tabela = "pedidos";
        
        public function listar(){
            
            return $this->read();
            
        }

        public function pedidoRecebido(){

          $parm = " ORDER BY ped_data DESC";          
                    
          return $this->readorder($parm);
            
        } 
       
        public function pedidoCadastrar(array $dados){
                      
           $arr = array(                   
                  'ped_numero'=>$dados['ped_numero'],
                  'cli_id'=>$dados['cli_id'],
                  'ped_status'=>$dados['ped_status']                
                  );
                      
            //print_r($arr);

            return $this->insert($arr);
            
        }        
        
        public function pedidoSelecionar($id){
            
           if($id <> null ):
                      
           $parm = "ped_id = ".$id;
           
           elseif( $id == null ):
           
           $parm = "";   
            
           endif;
           
           return $this->read($parm);
            
        } 

        public function pedidoSelecionar2($numero){
            
           if($numero <> null ):
                      
           $parm = "ped_numero = ".$numero;
           
           elseif( $numero == null ):
           
           $parm = "";   
            
           endif;
           
           return $this->read($parm);
            
        } 

        public function pedidoCode(array $dados){
                                         
           $parm = "ped_numero = ".$dados["ref"];

           $arr = array(

             'ped_code' => trim($dados['code'])

          );            
                 
           return $this->update($arr,$parm);
            
        } 

        public function pedidoStatus($dados,$ref){
                                         
           $parm = "ped_numero = ".$ref;

           $arr = array(

             'ped_status' => $dados

          );            
                 
           return $this->update($arr,$parm);
            
        } 

        public function pedidoSelecionar3($cliente){
            
           if($cliente <> null ):
                      
           $parm = "cli_id = ".$cliente;
           
           elseif( $cliente == null ):
           
           $parm = "";   
            
           endif;
           
           return $this->read($parm);
            
        } 
   
            
         public function pedidoDeletar(array $dados){
            
           $dados_id = $dados['id'];           
           $id = "cli_id=".$dados_id;           
                      
           return $this->delete($id,$dados_id);
            
        } 
        
          public function pedidoBloquear(array $dados){
            
            $dados_id = $dados['id'];           
            $id = "cli_id=".$dados_id;

            if($dados['status'] == "false"):
              $d = 1;
            else:
              $d = 0;
            endif; 

           
            $arr = array(
                   'cli_id'=> (int) $dados['id'],
                   'cli_bloquear'=> (int) $d
                   );
                      
            return $this->update_bloq($arr,$id,$dados_id);
            
        }   

        public function clienteVerifica($cnpj){
            
           if($cnpj <> null ):
                      
           $parm = "cli_cnpj = ".$cnpj;
           
           elseif( $cnpj == null ):
           
           $parm = "";   
            
           endif;
           
           return $this->read($parm);
            
        } 
          
    }