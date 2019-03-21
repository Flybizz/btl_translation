<?php 
class lead extends App_Controller{
    
    public function index_action(){

    }   
        
    public function list(){

        global $start;
        $params = $start->_params;

        $model = new App_Model_clienteModel();
        if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
            $parm = "";
        else:
            $parm = " usu_id = ".$_SESSION['id_usuario']."";
        endif;

        $total = $model->parmCliente("COUNT(*) as TOTAL",$parm,"");    
        $dados["cliente_total"] = $total[0]['TOTAL'];
        

        /* LEADS CONVERSION */
        $m_contato = new App_Model_contatoModel();
        $contato_nclassification = $model->clienteSClassification();
        /* $contato_leads = $m_contato->contatoLeads(); */
        $contato_leads = $m_contato->contatoWithCliente(1);
        $contato_prospects = $m_contato->contatoWithCliente(2);
        $contato_clients = $m_contato->contatoWithCliente(3);

        $dados["funil_visitantes"] = count($contato_nclassification);
        $dados["funil_leads"] = count($contato_leads);
        $dados["funil_conversao"] = count($contato_prospects);
        $dados["funil_clientes"] = count($contato_clients);

        if($params["ref"] == "noclassification" ):      
            $dados["data"] = $contato_nclassification;            
            $dados["lead_title"] = "S/ Classificação";
            $dados["ref"] = $params["ref"];
        endif;

        if($params["ref"] == "leads" ):      
            $dados["data"] = $contato_leads;         
            $dados["lead_title"] = "Leads";
            $dados["ref"] = $params["ref"];
            $dados["ref_id"] = 1; 
        endif;

        if($params["ref"] == "prospects" ):      
            $dados["data"] = $contato_prospects; 
            $dados["lead_title"] = "Prospects";         
            $dados["ref"] = $params["ref"];
            $dados["ref_id"] = 2;
        endif;

        if($params["ref"] == "clients" ):      
            $dados["data"] = $contato_clients;     
            $dados["lead_title"] = "Clients";      
            $dados["ref"] = $params["ref"];
            $dados["ref_id"] = 3;
        endif;
                        
        /* END LEAD CONVERSION */

        $this->View("lead_list", $dados);
    }

    public function move(){
        
        $m_contato = new App_Model_contatoModel();
        
        if(!empty($_POST['data'])):
            
            foreach ($_POST['data'] as $contato):
                $contato_action = $m_contato->contatoAction($contato, $_POST["action"]);                
            endforeach;
            
            echo json_encode($contato_action);

        endif;
    }
}    
?>