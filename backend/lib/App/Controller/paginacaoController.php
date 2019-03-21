<?php
    class App_Controller_paginacaoController extends App_Controller{
      /* PAGINACAO */
      public function paginacao($tabela,$where=NULL,$order,$link,$pag){
          define("PAGINACAO_TABELA", $tabela);
          // if($where == NULL):
          //   $paginacao = new App_Model_contatoModel();
          //   $rs = $cadastrar->contatoCadastrar($dados);
          //   $sql_noticia = "SELECT * FROM `[d007]noticia` ORDER BY D007_Data DESC";
          // else:
          // // $query = $mysqli->query("SELECT * FROM `[d007]pertence` WHERE D007_Chave = '".$pagper."'");
          // // $rowchave = $query->fetch_assoc();
          // // $sql_noticia = "SELECT * FROM `[d007]noticia` WHERE D007_Pertence = '".$rowchave['D007_Upag']."' ORDER BY D007_Data DESC"; 
            
          // endif;
          $nrows       = 8;
          $nlinks      = 5;
          $arquivo = $link;
          if(empty($pag))
          {
            $param = 0;
            $pag  = 0; 
            $temp  = 0;     
          } 
          else 
          {
            $temp   = $pag;
            $passo1 = $temp - 1;
            $passo2 = $passo1*$nrows;
            $param  = $passo2;
          }
          $paginacao = new App_Model_paginacaoModel();
          $rs = $paginacao->listarPaginacao($where,$order,$param,$nrows);
          //print_r($rs['info']['sqllimit']);
          $rs1       = $rs['info']['sqllimit2'];
          $rs2       = $rs['info']['sqllimit'];
          $totreg    = count($rs1);
          $limitreg  = count($rs2);
          $reg_final = $param + $limitreg;
          $result_div = $totreg/$nrows;
          $n_inteiro = (int)$result_div;
          if ($n_inteiro < $result_div) 
          {
            $n_paginas = $n_inteiro + 1;
          }
          else 
          {
            $n_paginas = $result_div;
          }
          $pg_atual    = $param/$nrows+1;
          $pg_anterior = $pg_atual - 1;
          $pg_proxima  = $pg_atual + 1;
          $lnk_impressos = 0;
          $finalpag = $n_paginas;
          // $total = $rs2->num_rows;
          $total = $limitreg;
          $dados['dados'] = $rs2;
          $dados['reg_final'] = $reg_final;
          $dados['n_paginas'] = $n_paginas;
          $dados['pg_atual'] = $pg_atual;
          $dados['pg_anterior'] = $pg_anterior;
          $dados['pg_proxima'] = $pg_proxima;
          $dados['lnk_impressos'] = $lnk_impressos;
          $dados['finalpag'] = $finalpag;
          $dados['arquivo'] = $arquivo;
          $dados['pag'] = $pag;
          $dados['nlinks'] = $nlinks;
          $dados['temp'] = $temp;
          $dados['totreg'] = $totreg;
          $dados['total'] = $total;
          return $dados;
        }
      
}