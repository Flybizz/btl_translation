<?php
    class App_Model extends App_Db_Abstract{

        public $_tabela;

        public function insert(array $dados){

            foreach ($dados as $ind => $value) {

                $valores[] = $value;
                $campos[] = $ind;

            }

            $campos = implode(", ", $campos);
            $valores = "'".implode("','", $valores)."'";
            if($this->query("INSERT INTO {$this->_tabela} ({$campos}) VALUES ({$valores})"))

               return "Dados inseridos com sucesso.";

            else

               return $this->errorInfo();

        }

        public function readclient($where = null){            
            $where = ($where != null ? " {$where}" : "");
            if($q = $this->query("SELECT * FROM (
            SELECT * FROM `btl_cliente`
            UNION  
            SELECT * FROM `btl_cliente`) as q WHERE {$where}")):
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->FetchAll();
            else:
                return $this->errorInfo();    
            endif;            
        }

        public function showcolunns(){            
            
            if($q = $this->query("SHOW COLUMNS FROM {$this->_tabela}")):
            
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->FetchAll();
                          
            else: 
           
                return $this->errorInfo();

            endif;
            
        }

        public function read($where = null){

            $where = ($where != null ? "WHERE {$where}" : "");
            if($q = $this->query("SELECT * FROM {$this->_tabela} {$where}")):
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->FetchAll();
            else:
                return $this->errorInfo();
            endif;
        }
    
        public function sqlquery($query){

            if($q = $this->query("{$query}")):
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->FetchAll();
            else:
                return $this->errorInfo();
            endif;
        }

        public function readesp($inf = null, $where = null, $order = null){

            $where = ($where != null ? "WHERE {$where}{$order}" : "{$order}");
            $inf = ($inf != null ? " {$inf}" : "*");
            if($q = $this->query("SELECT {$inf} FROM {$this->_tabela} {$where}")):
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->FetchAll();
            else:
                return $this->errorInfo();
            endif;
        }

        public function readgroup($where = null){

            $where = ($where != null ? " {$where}" : "");
            if($q = $this->query("SELECT * FROM {$this->_tabela} {$where}")):
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->FetchAll();
            else:
                return $this->errorInfo();
            endif;
        }
        public function readorder($where = null){

            $where = ($where != null ? " {$where}" : "");
            if($q = $this->query("SELECT * FROM {$this->_tabela} {$where}")):
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->FetchAll();
            else:
                return $this->errorInfo();
            endif;

        }

         public function readordertimeline($where = null){

            $where = ($where != null ? " {$where}" : "");
            if($q = $this->query("SELECT * FROM (
            SELECT foll_id as id, cli_id as cliente, foll_tipo as tipo, foll_titulo as titulo, '' as dtstart, '' as dtend, foll_texto as texto, foll_data as data, '' as prioridade, 'followup' as modulo FROM `followup`
            UNION
            SELECT tar_id as id, cli_id as cliente, tar_tipo as tipo, tar_titulo as titulo, tar_dtstart as dtstart, tar_dtend as dtend, tar_texto as texto, tar_data as data, tar_prioridade as prioridade, 'tarefa' as modulo FROM `tarefas`) as q WHERE {$where}")):
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->FetchAll();
            else:
                return $this->errorInfo();
            endif;

        }

        public function readorderbusca($where = null){

            $where = ($where != null ? " {$where}" : "");
            if($q = $this->query("SELECT * FROM (
            SELECT D007_Uid as id, D007_Titulo as titulo, D007_Chave as chave, D007_Texto as texto, 'noticia' as tipo FROM `d007noticia`
            UNION
            SELECT D007_Uid as id, D007_Titulo as titulo, D007_Chave as chave, D007_Texto as texto, 'artigo' as tipo FROM `d007artigo`
            UNION
            SELECT D004_Uid as id, D004_Titulo as titulo, D004_Chave as chave, D004_Texto as texto, 'conteudo' as tipo FROM `d004conteudo`
            UNION
            SELECT D014_Uid as id, D014_Titulo as titulo, D014_Chave as chave, D014_Descricao as texto, 'video' as tipo FROM `d014video`
            UNION
            SELECT D011_Uid as id, D011_Titulo as titulo, D011_Chave as chave, D011_Descricao as texto, 'produto' as tipo FROM `d011produto`
            UNION
            SELECT D011_Uid as id, D011_Titulo as titulo, D011_Chave as chave, D011_Descricao as texto, 'cifra' as tipo FROM `d011cifra`
            UNION
            SELECT D015_Uid as id, D015_Titulo as titulo, D015_Chave as chave, D015_Descricao as texto, 'galeria' as tipo FROM `d015galeria`) as q WHERE {$where}")):
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->FetchAll();
            else:
                return $this->errorInfo();
            endif;

        }
        public function read2($where = null){

            $where = ($where != null ? "WHERE {$where}" : "");
            if($q = $this->query("SELECT * FROM {$this->_tabela} {$where}")):
                $q->setFetchMode(PDO::FETCH_ASSOC);
                return $q->FetchAll();
            else:
                return $this->errorInfo();
            endif;

        }

        public function update(array $dados, $where = null, $id = null){

            foreach ($dados as $key => $value) {

                if(is_int($value)):
                    $campos[] = "{$key}={$value}";
                else:
                    $campos[] = "{$key}='{$value}'";
                endif;
             }

           $campos = implode(", ", $campos);
           //return $campos;
           $sql = "UPDATE {$this->_tabela} SET {$campos} WHERE $where";

           

           if($this->query($sql)):


                return 1;
           else:

                $output = $this->errorInfo();
                var_dump($output);
               //return 0;
           endif;

        }

        public function updatelead(array $dados, $where = null, $id = null){

            foreach ($dados as $key => $value) {

                if(is_int($value)):
                    $campos[] = "{$key}={$value}";
                else:
                    $campos[] = "{$key}='{$value}'";
                endif;
             }

           $campos = implode(", ", $campos);
           //return $campos;
           $sql = "UPDATE {$this->_tabela} SET {$campos} WHERE $where";

           if($this->query($sql)):
                $return["text"] = "Dados alterado com sucesso.";
                $return["code"] = 1;
                return $return;
           else:
                $return["text"] = print_r($this->errorInfo());
                $return["code"] = 0;
                return $return;
           endif;

        }

        public function delete($where, $id = null){

            if($this->query("DELETE FROM {$this->_tabela} WHERE {$where}"))

                return "<input type='hidden' name='id_retorno' value='$id'/>Registro $id deletado com sucesso.";

            else{
                return false;
            }

                

        }
        public function truncate(){

            if($this->query("TRUNCATE TABLE {$this->_tabela}"))

                return "Registro $id deletado com sucesso.";

            else

                return "Ocorreu um erro não foi possível deletar os dados.";

        }
        public function update_bloq(array $dados, $where = null, $id = null){

            foreach ($dados as $key => $value) {

                    $campos[] = "{$key}={$value}";

             }

           $campos = implode(", ", $campos);
           if($this->query("UPDATE {$this->_tabela} SET {$campos} WHERE {$where}"))
                return "<input type='hidden' name='id_retorno' value='$id'/>";
           else
               return "Ocorreu um erro não foi possível alterar os dados.";
        }
        public function update_status(array $dados, $where = null){

            foreach ($dados as $key => $value) {

                    $campos[] = "{$key}={$value}";

             }

           $campos = implode(", ", $campos);
           if($this->query("UPDATE {$this->_tabela} SET {$campos} WHERE {$where}"))


                return "Registro alterado com sucesso.";
           else

               return "Ocorreu um erro não foi possível alterar os dados.";
        }
        
        function removerAcentos($string){
            $string = preg_replace("`\[.*\]`U","",$string);
            $string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
            $string = htmlentities($string, ENT_COMPAT, 'utf-8');
            $string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
            $string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);
            return strtolower(trim($string, '-'));
        }

    }
