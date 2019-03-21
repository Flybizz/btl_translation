<?php
    class logout extends App_Logar{
        
        public function index_action(){

        	//echo $_SESSION['usuario_id2'];
        	                                  
            return $this->logout();
            
        }
        
    }