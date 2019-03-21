<?php 
class log extends App_Controller{
    
    public function index_action(){

    }   
        
    public function dbgerados(){

        $m_log = new App_Model_logDBGeradosModel();
        $logs = $m_log->listaLog();

        $dados["logs"] = $logs;

        $this->View("logs_bd_gerados", $dados);
    }

    public function logins(){
        
        $m_log = new App_Model_logDBLoginsModel();
        $logs = $m_log->listaLog();

        $dados["logs"] = $logs;

        $this->View("logs_login", $dados);
    }

    public function eventos(){

        //$m_log = new App_Model_logDBLoginsModel();
        $tarefa_obj = new App_Model_tarefaModel();
        $resumo_tarefas_feitas = $tarefa_obj->listaEventos();
        
        $dados["logs"] = $resumo_tarefas_feitas;

        $this->View("logs_eventos", $dados);

    }
}    
?>