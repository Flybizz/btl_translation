<?php
    class App_Logar extends App_Db_Abstract{
        
        public $usuario;
        public $senha;
        public $id;
        function logar($login, $senha)
        {
            // conecta ao banco de dados
        
            $this->usuario = $login;
            $this->senha = $senha;
       
            $query = $this->prepare("SELECT * FROM usuarios WHERE usu_login = :log AND usu_senha = :sen");
            $query->bindValue(':log', $this->usuario, PDO::PARAM_STR);
            $query->bindValue(':sen', $this->senha, PDO::PARAM_STR);
            $query->execute();
            $rg = $query->fetch(PDO::FETCH_OBJ);
                                 
            if ($query->rowCount() < 1){
                return '<script type="text/javascript"> document.location.href="../backend/login&c=1" </script>';
                
                //throw new Exception("Nada foi encontrado.");
            }
            else{
                
                $session = session_id();
                date_default_timezone_set("Europe/Lisbon");
                // $up = $this->query("UPDATE rede_usuarios SET usuario_online = 1, usuario_sessao = '".$session."', usuario_ativo = 0 WHERE usuario_id = ".$rg->usuario_id);
                
                // $up->execute();
                $_SESSION['logado'] = true;
                $_SESSION['usuarioLogado2'] = true;
                $_SESSION['sessao_atual'] = $session;
                $_SESSION['registro'] = time(); // armazena o momento em que autenticado //
                $_SESSION['limite'] = 3600;
                $_SESSION['id_usuario'] = $rg->usu_id;
                $_SESSION['usuario_foto'] = $rg->usu_foto;
                /*$sel_rc = $this->query("SELECT * FROM rede_cadastro WHERE rd_usuario = ".$_SESSION['id_usuario']);
                $sel_rc->execute();
                $rg_rc = $sel_rc->fetch(PDO::FETCH_OBJ);*/
                
                $_SESSION['nivel_acesso'] = $rg->niv_id;
                $_SESSION['vendedor_acesso'] = $rg->usu_id;
                $_SESSION['usuario_nome'] = $rg->usu_nome;
                //register login
                $App_login = new App_Model_logDBLoginsModel();
                $App_login->logRegistar();
                
                
                return "<script type='text/javascript'> document.location.href='../backend/index' </script>";
            }
        }
        
        public function logout(){
            
             $query1 = $this->query("SELECT usu_id , niv_id FROM usuarios WHERE usu_id = ".$_SESSION['id_usuario']);
             $rg = $query1->fetch(PDO::FETCH_OBJ);	
             // $up2 = $this->query("UPDATE [d002]usuarios SET usuario_online = 0, usuario_sessao = '', usuario_ativo = 0 WHERE usuario_id = ".$rg->usuario_id);
             
             // $up2->execute();             
             
             $_SESSION = array();	
             echo "<script type='text/javascript'> document.location.href='../backend/' </script>";
        }
        function logar_cliente($login, $senha)
        {
            // conecta ao banco de dados
        
            $this->usuario = $login;
            $this->senha = $senha;
       
            $query = $this->prepare("SELECT * FROM usuarios WHERE usu_login = :log AND usu_senha = :sen");
            $query->bindValue(':log', $this->usuario, PDO::PARAM_STR);
            $query->bindValue(':sen', $this->senha, PDO::PARAM_STR);
            $query->execute();
            $rg = $query->fetch(PDO::FETCH_OBJ);
            if ($query->rowCount() < 1){
               return '<script type="text/javascript"> document.location.href="/" </script>';
                
                //throw new Exception("Nada foi encontrado.");
            }
            else{
                
                $session = session_id();
                date_default_timezone_set("Europe/Lisbon");
                // $up = $this->query("UPDATE rede_usuarios SET usuario_online = 1, usuario_sessao = '".$session."', usuario_ativo = 0 WHERE usuario_id = ".$rg->usuario_id);
                
                // $up->execute();
                $_SESSION['clienteLogado'] = true;
                $_SESSION['sessao_atual_cliente'] = $session;
                $_SESSION['registro_cliente'] = time(); // armazena o momento em que autenticado //
                $_SESSION['limite_cliente'] = 3600;
                $_SESSION['id_cliente'] = $rg->usu_id;
                /*$sel_rc = $this->query("SELECT * FROM rede_cadastro WHERE rd_usuario = ".$_SESSION['id_usuario']);
                $sel_rc->execute();
                $rg_rc = $sel_rc->fetch(PDO::FETCH_OBJ);*/
                
                $_SESSION['cliente_nome'] = $rg->usu_nome;
                //echo $_SESSION['cliente_nome'];
                
                return "<script type='text/javascript'> document.location.href='/' </script>";
            }
        }
        public function logout_cliente(){    
             $_SESSION = array();
             echo "<script type='text/javascript'> document.location.href='/' </script>";
        }
        
 }
