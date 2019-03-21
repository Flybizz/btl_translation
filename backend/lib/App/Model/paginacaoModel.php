<?php 
class App_Model_paginacaoModel extends App_Model{
  public $_tabela = PAGINACAO_TABELA;
  public function listarPaginacao($where,$order,$param,$nrows){
      if(!empty($where)): $w = "WHERE ".$where; else: $w = ""; endif;
      $parm = "$w ORDER BY $order LIMIT $param,$nrows";
      $sqllimit  =  $this->readorder($parm);
      $parm2 = "$w ORDER BY $order";
      $sqllimit2  =  $this->readorder($parm2);
      $dados['info']['sqllimit'] = $sqllimit;
      $dados['info']['sqllimit2'] = $sqllimit2;
      return $dados;
  }
  public function listarPaginacaobusca($pesq,$param,$nrows){
          $parm = " q.titulo LIKE '%".$pesq."%' OR q.texto LIKE '%".$pesq."%' ORDER BY q.titulo ASC LIMIT $param,$nrows";
          $sqllimit  =  $this->readorderbusca($parm);
          $parm2 = " q.titulo LIKE '%".$pesq."%' OR q.texto LIKE '%".$pesq."%' ORDER BY q.titulo";
          $sqllimit2  =  $this->readorderbusca($parm2);
          $dados['info']['sqllimit'] = $sqllimit;
          $dados['info']['sqllimit2'] = $sqllimit2;
          return $dados;
  }
}