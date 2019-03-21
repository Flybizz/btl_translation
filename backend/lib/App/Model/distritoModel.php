<?php
class App_Model_distritoModel extends App_Model{

  public $_tabela = "btl_distrito";

  public function get_all_into_array(){
    $result = $this->listadistrito();
    if(!empty($result)){
      foreach($result as $distrito){
        $output[$distrito["cod_distrito"]] = $distrito;
      }
      return $output;
    }
    return false;
  }

  public function listadistrito(){
      $parm = " ORDER BY nome_distrito ASC";
      return $this->readorder($parm);
  }

  public function listaDistritoStatus(){
      $parm = "WHERE status_distrito = 'true' ORDER BY nome_distrito ASC";
      return $this->readorder($parm);
  }

  public function listaEnd(){
      $parm = "WHERE D005_Status = 'false' ORDER BY D005_Data_Cadastro DESC LIMIT 4";
      return $this->readorder($parm);
  }

  public function listaEndCount(){
      $parm = "WHERE D005_Status = 'false' ORDER BY D005_Data_Cadastro DESC";
      return $this->readorder($parm);
  }

  public function distritoCadastrar(array $dados){
    if(empty($dados)):
      $return = "Erro: Dados vazios.";
    else:
      $arr = array( 
         'D005_Tipo' => trim(utf8_encode($dados['tipo'])),
         'D005_Nome' => trim($dados['nome']),
         'D005_Email' => trim($dados['email']),
         'D005_Mensagem' => $dados['descricao'],
         'D005_Estado' => $dados['estado'],
         'D005_Cidade' => $dados['cidade'],
         'D005_Telefone' => $dados['celular']
      );
      $return = $this->insert($arr);
    endif;
    return $return;
  }
  public function distritoSelecionar($id){
     if($id <> null ):
     $parm = "cod_distrito = ".$id;
     elseif( $id == null ):
     $parm = "";
     endif;
     
     return $this->read($parm);
  }

  public function distritoSelecionarIn($id){
     if($id <> null ):
     $parm = " cod_distrito IN (".$id.")";
     elseif( $id == null ):
     $parm = "";
     endif;
     
     $rs = $this->readesp("nome_distrito",$parm," ORDER BY nome_distrito ASC"); 

     //print_r($rs);

     return $rs;
  }

  public function distritoDeletar($id){
     $dados_id = "D005_Uid =".$id;
     return $this->delete($dados_id,$id);
  }

  public function distritoAlteracao(array $dados){
    $id = "D005_Uid=".$dados['id']; 
    $arr = array( 
     'D005_Tipo' => trim(utf8_encode($dados['tipo'])),
     'D005_Nome' => trim($dados['nome']),
     'D005_Email' => trim($dados['tipo']),
     'D005_Mensagem' => $dados['msg'],
     'D005_Estado' => $dados['estatdo'],
     'D005_Cidade' => $dados['cidade'],
     'D005_Telefone' => $dados['telefone']
    );
    return $this->update($arr,$id);
  }

  public function distritoAlterarStatus(array $dados){
    $id = "cod_distrito=".$dados['id']; 
    //echo $dados['id']." /// ".$dados['status']; 
    $arr = array(
     'status_distrito' => $dados['status']
    );
    return $this->update($arr,$id);
  }

}