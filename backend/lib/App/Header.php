<?php
    class App_Header extends App_Db_Abstract{
        
        public function getHeader($id){
            
                $query = $this->query("SELECT usuario_id, usuario_funcao, usuario_login FROM rede_usuarios WHERE usuario_id = ".$id);
                $rg = $query->Fetch(PDO::FETCH_OBJ);
                                                                          
		$mes = date('m');
                switch($mes)
                {
                        case 1: $mes = "Janeiro"; break;
                        case 2: $mes = "Fevereiro"; break;
                        case 3: $mes = "Março"; break;
                        case 4: $mes = "Abril"; break;
                        case 5: $mes = "Maio"; break;
                        case 6: $mes = "Junho"; break;
                        case 7: $mes = "Julho"; break;
                        case 8: $mes = "Agosto"; break;
                        case 9: $mes = "Setembro"; break;
                        case 10: $mes = "Outubro"; break;
                        case 11: $mes = "Novembro"; break;
                        case 12: $mes = "Dezembro"; break;
                        default: $mes = "não existe este mês.";break;
                }

                $semana = date('D');
                switch($semana)
                {
                        case "Sun": $semana = "domingo"; break;
                        case "Mon": $semana = "segunda-feira"; break;
                        case "Tue": $semana = "terça-feira"; break;
                        case "Wed": $semana = "quarta-feira"; break;
                        case "Thu": $semana = "quinta-feira"; break;
                        case "Fri": $semana = "sexta-feira"; break;
                        case "Sat": $semana = "sábado"; break;
                        default: $semana = "inexistente";break;
                }

                $horario = date("H");


                if($horario > 5 && $horario < 12)
                {
                        $HoraDay = 'Bom dia';

                }
                else if($horario >= 12 && $horario < 18) 
                {
                        $HoraDay = 'Boa tarde';

                }else{

                        $HoraDay = 'Boa noite';
                }

                echo '

                            <div id="logo" class="col-xs-12 col-sm-2">
                                <a href="/../backend/index/">
								<img src="/../backend/public/img/logomarca1.png" height="45">
                                </a>
                            </div>
                            <div id="top-panel" class="col-xs-12 col-sm-10">
                                <div class="row">
                                    <div class="col-xs-8 col-sm-4">
                                        <a href="#" class="show-sidebar">
                                          <i class="fa fa-bars"></i>
                                        </a>
                                        <!--div id="search">
                                            <input type="text" placeholder="search"/>
                                            <i class="fa fa-search"></i>
                                        </div-->
                                    </div>
                                    <div class="col-xs-4 col-sm-8 top-panel-right">
                                        <ul class="nav navbar-nav pull-right panel-menu">
                                            <!--li class="hidden-xs">
                                                <a href="index.html" class="modal-link">
                                                    <i class="fa fa-bell"></i>
                                                    <span class="badge">7</span>
                                                </a>
                                            </li>
                                            <li class="hidden-xs">
                                                <a class="ajax-link" href="ajax/calendar.html">
                                                    <i class="fa fa-calendar"></i>
                                                    <span class="badge">7</span>
                                                </a>
                                            </li>
                                            <li class="hidden-xs">
                                                <a href="ajax/page_messages.html" class="ajax-link">
                                                    <i class="fa fa-envelope"></i>
                                                    <span class="badge">7</span>
                                                </a>
                                            </li-->
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle account" data-toggle="dropdown">
                                                    <!--<div class="avatar">
                                                        <img src="/../backend/public/img/usuario/'.$rg->usuario_id.'.jpg" class="img-rounded" alt="avatar" />
                                                    </div>-->
                                                    <i class="fa fa-angle-down pull-right"></i>
                                                    <div class="user-mini pull-right">
                                                        <span class="welcome">'.utf8_encode($rg->usuario_login).'</span>
                                                        <span>'.utf8_encode($rg->usuario_funcao).'</span>
                                                        <input type="hidden" id="sessao_usuario" value="'.$rg->usuario_id.'">
                                                    </div>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="/../backend/cadastro/alterar">
                                                            <i class="fa fa-user"></i>
                                                            <span>Meus dados cadastrais</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/../backend/mensagens" class="ajax-link">
                                                            <i class="fa fa-envelope"></i>
                                                            <span>Mensagens</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="/../backend/logout/">
                                                            <i class="fa fa-power-off"></i>
                                                            <span>Sair</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>




 '; 
                /*<div class='row-fluid'>
                        <div class='span12'>
                            <div class='row-fluid'>
                                <div id='logomarca' class='span3'>
                                    <!--img width='150px' id='logo1' src='/../../public/frontend/img/logo1.png' />
                                    <img width='150px' id='logo2' src='/../../public/frontend/img/logo1.png' /-->
                                </div>
                                <div id='identificacao' class='span7 offset1'><i class='icon-user'></i>  Função: ".$rg->usuario_funcao." <br><span> ".$HoraDay."! ".$rg->usuario_nome." - hoje &eacute; ".$semana.", ".date("d")." de ".$mes." de ".date("Y").".</span></div>
                            </div>
                        </div>
                    </div>*/
               
            
        }
        
    }