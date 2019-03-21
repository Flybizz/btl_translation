<?php
    class App_Model_itemModel extends App_Model{
        
        public $_tabela = "item";
        
        public function listaItemCadastrado(){
            
            return $this->read();
            
        }
        
        public function itemCadastrar(array $dados){
                      
           $arr = array(                   
                   'ped_id'=>$dados['ped_id'],
                   'prod_id'=>$dados['prod_id'],
                   'item_qtd'=>$dados['item_qtd'],
                   'item_valor'=>$dados['item_valor'],
                   'item_total'=>$dados['item_total']
                   );
                      
            //print_r($arr);

            return $this->insert($arr);
            
        }        
        
        public function itemSelecionar($id){
            
           if($id <> null ):
                      
           $parm = "item_id = ".$id;
           
           elseif( $id == null ):
           
           $parm = "";   
            
           endif;
           
           return $this->read($parm);
            
        } 

        public function itemSelecionarEnd()
        {

            $parm = " ORDER BY item_id DESC LIMIT 1";

            return $this->readorder($parm);

        }

         public function itemSelecionar2($id){
            
           if($id <> null ):
                      
           $parm = "ped_id = ".$id;
           
           elseif( $id == null ):
           
           $parm = "";   
            
           endif;
           
           return $this->read($parm);
            
        } 

        public function itemSelecionarQtd($id){            
                              
           $parm = "ped_id = ".$id;  
           
           $this->read($parm);

           $count = count($parm);

           return $count;
            
        } 

        public function itemSelecionarTotal($id){            
                              
          $parm = "ped_id = ".$id;  
           
          return $this->read($parm);

        } 
                     
         public function itemDeletar(array $dados){
            
           $dados_id = $dados['id'];           
           $id = "item_id=".$dados_id;           
                      
           return $this->delete($id,$dados_id);
            
        } 
        
    
               
    }