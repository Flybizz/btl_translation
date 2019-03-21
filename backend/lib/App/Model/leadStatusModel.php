<?php
class App_Model_leadStatusModel extends App_Model{
   
   public $_tabela = "btl_lead_status_log";
   
   public function listaLead(){
      $parm = " ORDER BY lead_status_nome ASC";
      return $this->readorder($parm);
   }
   
   public function listaLeadStatus(){
      $parm = "WHERE lead_status_status = 'sim' ORDER BY lead_status_nome ASC";
      return $this->readorder($parm);
   }

   public function get_updates_from_client($client_id){
      $parm = " WHERE client_id IN ({$client_id}) ORDER BY status_date DESC";
      return $this->readorder($parm);
   }
   
   //returns label name depending on status id
   public function get_label($status_id){
      
      $model_status = new App_Model_leadModel();
      $all_status = $model_status->listaLead();
      
      foreach($all_status as $status){
         
         $output[$status["lead_id"]] = $status["lead_nome"];
      }
      
      return !empty($output[$status_id]) ? $output[$status_id] : false;
   }
   
   public function lead_statusRegistar(array $dados){

      if(empty($dados)):
         $return = "Erro: Dados vazios.";
      else:
         $return = $this->insert($dados);
      endif;
      return $return;
   }
   
   public function lead_statusSelecionar($id){
      if($id <> null ):
         $parm = "lead_status_id = ".$id;
         elseif( $id == null ):
            $parm = "";
         endif;
         
         return $this->read($parm);
      }
      
      public function lead_statusChave($chave){
         $parm = "lead_status_id = '".$chave."'";     
         return $this->read($parm);
      }
      
      public function lead_statusDeletar($id){
         $dados_id = "lead_status_id =".$id;
         return $this->delete($dados_id,$id);
      }
      
      public function lead_statusAlteracao(array $dados){
         $id = "lead_status_id=".$dados['id']; 
         $chave = parent::removerAcentos($dados['nome']);
         $arr = array( 
            'lead_status_nome' => trim($dados['nome']),  
            'lead_status_chave' => strtolower($chave),       
            'lead_status_status' => $dados['status']    
         );    
         
         //return $arr;
         
         return $this->update($arr,$id);
      }
      
   }