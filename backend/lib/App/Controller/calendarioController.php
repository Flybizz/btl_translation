<?php
    class calendario extends App_Controller{

        public function index_action(){

            $model = new App_model();
            $model->tabela = "usuarios";            

            if($_SESSION['nivel_acesso'] == 1 || $_SESSION['nivel_acesso'] == 2 ):
                $parm = "";
            else:
                $parm = " AND usu_id = ".$_SESSION['id_usuario']."";
            endif;

            $sql = "SELECT * FROM $model->tabela WHERE 1 AND usu_calendar_id <> '' {$parm}";

            $result = $model->sqlquery($sql);
            foreach($result as $item){
                $dados["calendars"][] = $item["usu_calendar_id"];
                $dados["data"][$item["usu_calendar_id"]] = $item;
            }

            return $this->View("indexCalendario", (!empty($dados) ? $dados : false) );

        }

    }