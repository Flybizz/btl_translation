<?php
    class indexs extends App_Controller{
        public function index_action(){
          //chama BD tabela **************************
          $menu = new App_Model_menuModel();
          $pagina = new App_Model_paginaModel();

          $menu_link = unserialize (MENU_LINK);
          $header = $pagina->paginaSelecionarLang("index",$menu_link["sigla"]);
          $dados['homepage'] = $header;

          /********************************************/
          //MENU DO SITE - POR TIPO DE LANGUAGE
          $menuLANGUAGE = unserialize (MENU_LIST);          
          $dados['MENU_LIST'] = $menuLANGUAGE;
          /******************************

          $config = unserialize (CONFIG_DB);
          /********************************************/
          $config = unserialize (CONFIG_DB);  
          //CONFIGURAÇÃO DO SITE
          $dados['config'] = $config;
          /************************************************/
              
          /**************************************************************/
          /**************************************************************/
          /**************************************************************/
          //CHAMA INDEX FRONTEND
          parent::Site("index", $dados);
          /* FOOTER*/
          $footer['config'] = $config;
          parent::Site("footer", $footer);                             
        } 
    
        
    }