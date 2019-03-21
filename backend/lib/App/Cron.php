<?php
   
   class App_Cron extends App_Db_Abstract{

      public $notifications;
      public $settings;
      public $pending_notifications = array();
      public $reminders = array();
      

      public function __construct($config){
         $this->load_settings($config);
      }

      function load_settings($config){
         $this->settings = array(
            "minutes_before_notification" => 1440, //24 horas
            "minutes_after_event" => 1440, //24 horas
            "debug" => true,
            "disable_send" => true,
            "client_config" => $config[0]
         );

      }

      function debug($message){
         if($this->debug == true)
            echo $message;
      }
      
      //get the notifications from db filtered by conditions
      public function get_notifications(){
         
         $sql = "SELECT tar_notificacao_enviada AS notificacao_enviada, tar_finalizado AS finalizado, u.usu_nome, u.usu_email, tar_referencia AS referencia, tarefas.cli_id, c.cli_nome, c.cli_referencia, tar_tipo AS tipo, tar_titulo AS titulo, tar_dtstart AS dtstart, tar_dtend AS dtend, tar_hrstart AS hrstart, tar_hrend AS hrend, tar_texto AS texto, tar_prioridade AS prioridade, CONCAT(tar_dtstart, ' ',tar_hrstart) AS `data`, google_event_id from tarefas 
         INNER JOIN btl_cliente c ON c.cli_id = tarefas.cli_id
         INNER JOIN usuarios u ON u.usu_id = c.usu_id
             UNION
         SELECT foll_notificacao_enviada AS notificacao_enviada, foll_finalizado AS finalizado, u.usu_nome, u.usu_email, foll_referencia AS referencia, followup.cli_id, c.cli_nome, c.cli_referencia, 'follow-up' AS tipo, foll_titulo AS titulo, '' AS dtstart, '' AS dtend, '' AS hrstart, '' AS hrend, foll_texto AS texto, '' AS prioridade, foll_data AS `data`, '' google_event_id from followup
         INNER JOIN btl_cliente c ON c.cli_id = followup.cli_id
         INNER JOIN usuarios u ON u.usu_id = c.usu_id
             ORDER BY `data` ASC";

         $query = $this->query($sql);
         $result = $query->fetchAll(PDO::FETCH_OBJ);

         $this->notifications = !empty($result) ? $result : false;

      }

      //depending on criteria, some will be excluded or not
      public function filter_notifications(){

         if(empty($this->notifications)) return false;

         foreach($this->notifications as $notification){

            $now = time();
            $event_date = new DateTime($notification->data);

            //filter - if part of the past, will be set for reminders - only tasks not done
            if($now >= strtotime($notification->data) && $notification->finalizado == 0){

               $notification->time_period = "past";
               $time_gone_by = $event_date->diff(new DateTime());
               $time_gone_by_in_minutes = ($time_gone_by->d * 24 * 60) + ($time_gone_by->h * 60) + ($time_gone_by->i);

               if($time_gone_by_in_minutes <= $this->settings["minutes_after_event"] && $notification->notificacao_enviada != 1){
                  $this->reminders[] = $notification;
               }
               
            }

            else{

               $notification->time_period = "future";
               $time_untill_task = $event_date->diff(new DateTime());
               $time_untill_task_in_minutes = ($time_untill_task->d * 24 * 60) + ($time_untill_task->h * 60) + ($time_untill_task->i);

               //filter - if set for the near future will be set for notifications
               if($time_untill_task_in_minutes <= $this->settings["minutes_before_notification"]){
                  $this->pending_notifications[] = $notification;
               }
               else{
                  //filter - if set for the far future, will not trigger a notification
                  //possible scenario: this->future_notifications[] = $notification;
               }

               //notifications all filtered, clear space
               $this->notifications = false;

            }
           
         }

      }

      public function send(){

         //dependency, to use load_view
         $controller = new App_Controller();

         //grab notifications and reminders into single email
         $all_notifications = array_merge($this->pending_notifications, $this->reminders);
        
         //grab the pending notifications and reminders, load HTML template
         if(!empty($all_notifications)){

            foreach($all_notifications as $notification){

               //trim
               if(!$notification->tipo == "follow-up")
                  $notification->prioridade = priority_label($notification->prioridade);

               if(empty($notification->usu_email)) continue;

  
               //send to view
               $data_to_view = array("config" => $this->settings["client_config"], "content" => $notification);
               switch($notification->time_period){
                  case "past":
                  $html = $controller->load_view(getcwd() . "/lib/App/View/emails/notifications/email-notification-past", $data_to_view);
                  break;

                  case "future":
                  default:
                  $html = $controller->load_view(getcwd() . "/lib/App/View/emails/notifications/email-notification-future", $data_to_view);
                  break;
               }         
   
               //safe switch
               //if($this->settings["disable_send"] == true) return false;

               //...and fire               
               $mail = new App_PHPMailer();
               $mail->isSMTP();
               $mail->SMTPDebug = $this->settings["debug"] == true ? 2 : 0; //2 verbose client + serverside
               $mail->Host = $this->settings["client_config"]["D001_SMTP_host"];
               $mail->CharSet = "UTF-8";
               $mail->Port = $this->settings["client_config"]["D001_SMTP_port"]; //Set the SMTP port number - likely to be 25, 465 or 587
               $mail->SMTPAuth = true; //Whether to use SMTP authentication
               $mail->Username = $this->settings["client_config"]["D001_SMTP_email"]; //Username to use for SMTP authentication
               $mail->Password = $this->settings["client_config"]["D001_SMTP_password"]; //Password to use for SMTP authentication

               $mail->setFrom($this->settings["client_config"]["D001_SMTP_email"], $this->settings["client_config"]["D001_Empresa"]);
               $mail->addAddress($notification->usu_email, !empty($notification->usu_nome) ? $notification->usu_nome : false);
               //$mail->addAddress("franco.silva@flybizz.net");
               $mail->Subject = $this->settings["client_config"]["D001_Empresa"] . ' - Lembrete';
               $mail->isHTML(true);
               $mail->Body = $html;

               $send = $mail->send();

               //update the status
               if($send || true){
                  $this->register_notification_sent($notification->referencia, $notification->tipo);
               }
   
            }

         }   
         else{
            
            if($this->settings["debug"] == true){
               echo json_encode(array("return" => 404, "message" => "No notifications to be sent for the time period setting"));
            }
            
            return false;
         }            

      }

      public function register_notification_sent($notification_ref, $notification_type){
         $table = $notification_type == "follow-up" ? "followup" : "tarefas";
         $column = $notification_type == "follow-up" ? "foll_notificacao_enviada" : "tar_notificacao_enviada";
         $ref = $notification_type == "follow-up" ? "foll_referencia" : "tar_referencia";

         $sql = "UPDATE {$table} SET {$column} = 1 WHERE {$ref} = '{$notification_ref}'";
         $query = $this->query($sql);

         if($query) return true;
         return false;
      }
      
}


//helper?
function priority_label($priority_id){
   
   $labels = array(
      1 => array("label" => "Baixa", "color" => "#0088cc"),
      2 => array("label" => "Média", "color" => "#47a447"),
      3 => array("label" => "Alta", "color" => "#fd7e14"),
      4 => array("label" => "Urgente", "color" => "#dc3545")
   );

   return $labels[$priority_id]["label"];

}