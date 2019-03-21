<?php
	class excel extends App_Controller{

        public function upcliente(){

	        $path = "../backend/public/excel/btl/";
	        $path2 = "../../backend/public/excel/";
	        $diretorio = dir($path);

	        while($arquivo = $diretorio->read()):

	            if($arquivo != ".." && $arquivo != "." ):

	                $exp = explode(".", $arquivo);
	                $nome = $exp[0];
	                $ext = $exp[1];

	                require_once 'App/PHPExcel.php';

	                // iniciar o objecto para leitura

	                $objReader = new PHPExcel_Reader_CSV();

	                $objReader->setInputEncoding('UTF-8');
	                $objReader->setDelimiter(';');
	                $objReader->setEnclosure('');
	                $objReader->setLineEnding("\r\n");
	                $objReader->setSheetIndex(0);

	                $objPHPExcel = $objReader->load("../backend/public/excel/btl/".$nome.".csv");
	                $objPHPExcel2 = $objReader->load("../backend/public/excel/btl/".$nome.".csv");

	                // iniciar o objecto para leitura MPD
	                //$objPHPExcelnmot = $objReader->load("../backend/public/excel/motorista/motorista.csv");

	                // construção da tabela

	                for($linha=1; $linha<=900; $linha++){

						$vendedor = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $linha)->getValue());
						$cpf = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $linha)->getValue());
						$nome2 = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $linha)->getValue());
						$tipo = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $linha)->getValue());
						$area = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $linha)->getValue());
						$contato = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $linha)->getValue());
						$especialidade = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $linha)->getValue());
						$endereco = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $linha)->getValue());
						$cidade = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $linha)->getValue());
						$estado = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, $linha)->getValue());
						$telefone = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10, $linha)->getValue());
						$email = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(11, $linha)->getValue());
						$produto = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(12, $linha)->getValue());
						$mapa = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(31, $linha)->getValue());
						$website = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(32, $linha)->getValue());
						$locacao = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(35, $linha)->getValue());
						$obs = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(37, $linha)->getValue());
						$classificacao = str_replace("/", "|", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(40, $linha)->getValue());

						$produto = substr($produto, 0, strlen($produto) - 1);
 
                                           
                        /* $vendedor = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(11, $linha)->getValue(); */
						
						/* echo $vendedor."<br>".$cpf."<br>".$nome2."<br>".$tipo."<br>".$area."<br>".$endereco."<br>".$cidade."<br>".$estado."<br>".$telefone."<br>".$email."<br>".$produto."<br>".$mapa."<br>".$website."<br>".$locacao."<br>".$obs."<br>".$classificacao; */
              
                        if(!empty($nome2)):

                            $model_cliente = new App_Model_clienteModel();
                            $cad_cliente = $model_cliente->clienteCsv($vendedor, $cpf, $nome2, $tipo, $area, $endereco, $cidade, $estado, $telefone, $email, $produto, $mapa, $website, $locacao, $obs, $classificacao);
                            echo "Cliente: ".$cad_cliente."<br>";
                            $sel_cliente = $model_cliente->clienteUltimoRg();

                            if(!empty($contato)):

                                $model_contato = new App_Model_contatoModel();
                                $cad_contato = $model_contato->contatoCsv($contato,$especialidade,$sel_cliente[0]["cli_id"]);

                                echo "Contato: ".$cad_contato."<br>";

                            endif;

                        endif;

	                }

	                unlink($path.$nome.".".$ext);
	            endif;
	        endwhile;

	        $diretorio->close();
	    }

	    public function dwexcel(){

	    	// seta o id do cliente
            $id = new App_System();
            $id->_urlAjax = $_POST['url'];
            $id->setExplodeAjax();
            $id->setControllerAjax();
            $id->setActionAjax();
            $id->setParamsAjax();
            $ident = $id->getParamsAjax();

            if(!empty($ident["datastart"]) && !empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => $ident["dataend"]);
                $base[] = $query;
            endif;

            if(!empty($ident["datastart"]) && empty($ident["dataend"])):
                $query = array("dtstart" => $ident["datastart"], "dtend" => "");
                $base[] = $query;
            endif;

            if(!empty($ident["campo"])):
                $query = array("campo" => $ident["campo"]);
                $base[] = $query;
            endif;

            if(!empty($ident["vendedor"])):
                $query = array("usu_id" => $ident["vendedor"]);
                $base[] = $query;
            endif;

            if(!empty($ident["area"])):
                $query = array("cli_area" => $ident["area"]);
                $base[] = $query;
			endif;
			
			if(!empty($ident["produto"])):
                $query = array("cli_produto" => $ident["produto"]);
                $base[] = $query;
            endif;

            if(!empty($ident["tipo"])):
                $query = array("cli_tipo" => $ident["tipo"]);
                $base[] = $query;
            endif;

            if(!empty($ident["distrito"])):
                $query = array("cli_distrito" => $ident["distrito"]);
                $base[] = $query;
            endif;

            if(isset($base)):
                array_push($base, $query);
                array_pop($base);
            endif;

            $cliente = new App_Model_clienteModel();
			$cliente_lista = $cliente->clienteBuscarXls($base,$ident["coluna"]);
			
			$m_log = new App_Model_logDBGeradosModel();
            $insert_log = $m_log->logRegistar("excel", $base);

            $contato = new App_Model_contatoModel();
            $vendedor = new App_Model_usuarioModel();
            $distrito = new App_Model_distritoModel();
			$area = new App_Model_areaModel();
			$produto = new App_Model_produtoModel();
            $tipo = new App_Model_tipoModel();
            $cargo = new App_Model_cargoModel();
            $especialidade = new App_Model_especialidadeModel();

			if(!empty( $cliente_lista )):
				
				$exp = explode(",", $ident["coluna"]);				  
				foreach ($cliente_lista as $ch => $rs):
					
					if($rs["cli_saudacao"] == 0):
                        $rf = "";
                    elseif($rs["cli_saudacao"] == 1):
                        $rf = "Sr.";
                    elseif($rs["cli_saudacao"] == 2):
                        $rf = "Sra.";
                    elseif($rs["cli_saudacao"] == 3):
                        $rf = "Srta.";
                    elseif($rs["cli_saudacao"] == 4):
                        $rf = "Dr.";
                    elseif($rs["cli_saudacao"] == 5):
                        $rf = "Dra.";
					endif;
					
					if(!empty($ident["coluna"])):

							if(in_array("cli_referencia", $exp)):
								$arr["referencia"] = $rs["cli_referencia"];
							endif;

							if(in_array("cli_saudacao", $exp)):
								$arr["saudacao"] = $rf;
							endif;

							if(in_array("cli_nome", $exp)):
								$arr["nome"] = $rs["cli_nome"];
							endif;

							if(in_array("cli_rua", $exp)):
								$arr["morada"] = $rs["cli_rua"];
							endif;

							if(in_array("cli_numero", $exp)):
								$arr["numero"] = $rs["cli_numero"];
							endif;

							if(in_array("cli_andar", $exp)):
								$arr["andar"] = $rs["cli_andar"];
							endif;

							if(in_array("cli_cp", $exp)):
								$arr["codigo_postal"] = $rs["cli_cp"];
							endif;

							if(in_array("cli_localidade", $exp)):
								$arr["localidade"] = $rs["cli_localidade"];
							endif;

							if(in_array("cli_distrito", $exp)):
								$sel_distrito = $distrito->distritoSelecionar($rs["cli_distrito"]);
								$arr["distrito"] = $sel_distrito[0]["sigla_distrito"];
							endif;

							if(in_array("cli_email", $exp)):
								$arr["email"] = $rs["cli_email"];
							endif;

							if(in_array("cli_telefone", $exp)):
								$arr["telefone"] = $rs["cli_telefone"];
							endif;

							if(in_array("cli_tipo", $exp)):
								$sel_tipo = $tipo->tipoSelecionar($rs["cli_tipo"]);
								$arr["tipo"] = $sel_tipo[0]["tipo_nome"];
							endif;

							if(in_array("cli_area_negocio", $exp)):
								$sel_area = $area->areaSelecionarIn($rs["cli_area_negocio"]);
								$arr["area_negocio"] = $sel_area;
							endif;

							if(in_array("cli_produto", $exp)):
								$sel_produto = $produto->produtoSelecionarIn($rs["cli_produto"]);
								$arr["produto"] = $sel_produto; 
							endif;				

							if(in_array("cli_url", $exp)):
								$arr["website"] = $rs["cli_url"];
							endif;

							if(in_array("cli_descricao", $exp)):
								$arr["descricao"] = $rs["cli_descricao"];
							endif;

							if(in_array("cli_status", $exp)):
								$arr["status"] = $rs["cli_status"];
							endif;

							if(in_array("cli_nif", $exp)):
								$arr["nif"] = $rs["cli_nif"];
							endif;

							if(in_array("contato", $exp)):
								$sel_contato = $contato->contatoSelecionarCliente($rs["cli_id"]);

								if(!empty($sel_contato)):								
									if(isset($sel_contato[0])):
										$sl_contato1_nome = $sel_contato[0]["cont_nome"];
										$sl_contato1_cargo = $sel_contato[0]["cont_cargo"];
										$sl_contato1_especialidade = $sel_contato[0]["cont_especialidade"];
										$sl_contato1_email = $sel_contato[0]["cont_email"];
										$sl_contato1_telemovel = $sel_contato[0]["cont_telemovel"];
									else:
										$sl_contato1_nome = "";
										$sl_contato1_cargo = "";
										$sl_contato1_especialidade = "";
										$sl_contato1_email = "";
										$sl_contato1_telemovel = "";
									endif;
	
									if(isset($sel_contato[1])):
										$sl_contato2_nome = $sel_contato[1]["cont_nome"];
										$sl_contato2_cargo = $sel_contato[1]["cont_cargo"];
										$sl_contato2_especialidade = $sel_contato[1]["cont_especialidade"];
										$sl_contato2_email = $sel_contato[1]["cont_email"];
										$sl_contato2_telemovel = $sel_contato[1]["cont_telemovel"];
									else:
										$sl_contato2_nome = "";
										$sl_contato2_cargo = "";
										$sl_contato2_especialidade = "";
										$sl_contato2_email = "";
										$sl_contato2_telemovel = "";
									endif;
	
									if(isset($sel_contato[2])):
										$sl_contato3_nome = $sel_contato[2]["cont_nome"];
										$sl_contato3_cargo = $sel_contato[2]["cont_cargo"];
										$sl_contato3_especialidade = $sel_contato[2]["cont_especialidade"];
										$sl_contato3_email = $sel_contato[2]["cont_email"];
										$sl_contato3_telemovel = $sel_contato[2]["cont_telemovel"];
									else:
										$sl_contato3_nome = "";
										$sl_contato3_cargo = "";
										$sl_contato3_especialidade = "";
										$sl_contato3_email = "";
										$sl_contato3_telemovel = "";
									endif;
								endif;								

								$arr["Contato 1 Nome"] = $sl_contato1_nome;
								$arr["Contato 1 Cargo"]= $sl_contato1_cargo;
								$arr["Contato 1 Especialidade"] = $sl_contato1_especialidade;
								$arr["Contato 1 Email"] = $sl_contato1_email;
								$arr["Contato 1 Telefone"] = $sl_contato1_telemovel;
								$arr["Contato 2 Nome"] = $sl_contato2_nome;
								$arr["Contato 2 Cargo"] = $sl_contato2_cargo;
								$arr["Contato 2 Especialidade"] = $sl_contato2_especialidade;
								$arr["Contato 2 Email"] = $sl_contato2_email;
								$arr["Contato 2 Telefone"] = $sl_contato2_telemovel;
								$arr["Contato 3 Nome"] = $sl_contato3_nome;
								$arr["Contato 3 Cargo"] = $sl_contato3_cargo;
								$arr["Contato 3 Especialidade"] = $sl_contato3_especialidade;
								$arr["Contato 3 Email"] = $sl_contato3_email;
								$arr["Contato 3 Telefone"] = $sl_contato3_telemovel;
							endif;

							if(in_array("usu_id", $exp)):
								$sel_vendedor = $vendedor->usuarioSelecionar($rs["usu_id"]);
								$arr["vendedor"] = $sel_vendedor[0]["usu_nome"];
							endif;

							if(in_array("cli_associado", $exp)):
								$sel_associado = $vendedor->usuarioSelecionarIn($rs["cli_associado"]);
								$arr["associado"] = $sel_associado;
							endif;

							if(in_array("cli_rgpd", $exp)):
								if($rs["cli_rgpd"] == 'true'): $rgpd = "Concordo"; endif;
								if($rs["cli_rgpd"] == 'false'): $rgpd = "Não Concordo"; endif;
								if(empty($rs["cli_rgpd"])): $rgpd = ""; endif;
								$arr["rgpd"] = $rgpd;
							endif;

							$info[] = $arr;

							if(!empty($info)):
								array_push($info, $arr);
								array_pop($info);
							endif;

					else:

							$sel_contato = $contato->contatoSelecionarCliente($rs["cli_id"]);
							$sel_distrito = $distrito->distritoSelecionar($rs["cli_distrito"]);
							$sel_area = $area->areaSelecionarIn($rs["cli_area_negocio"]);
							$sel_produto = $produto->produtoSelecionarIn($rs["cli_produto"]);
							$sel_tipo = $tipo->tipoSelecionar($rs["cli_tipo"]);
							$sel_vendedor = $vendedor->usuarioSelecionar($rs["usu_id"]);
							$sel_associado = $vendedor->usuarioSelecionarIn($rs["cli_associado"]);

							if(!empty($sel_distrito)):
								$sl_distrito = $sel_distrito[0]["sigla_distrito"];
							else:
								$sl_distrito = "";
							endif;

							if(!empty($sel_vendedor)):
								$sl_vendedor = $sel_vendedor[0]["usu_nome"];
							else:
								$sl_vendedor = "";
							endif;

							if(!empty($sel_contato)):								
								if(isset($sel_contato[0])):
									$sl_contato1_nome = $sel_contato[0]["cont_nome"];
									$sl_contato1_cargo = $sel_contato[0]["cont_cargo"];
									$sl_contato1_especialidade = $sel_contato[0]["cont_especialidade"];
									$sl_contato1_email = $sel_contato[0]["cont_email"];
									$sl_contato1_telemovel = $sel_contato[0]["cont_telemovel"];
								else:
									$sl_contato1_nome = "";
									$sl_contato1_cargo = "";
									$sl_contato1_especialidade = "";
									$sl_contato1_email = "";
									$sl_contato1_telemovel = "";
								endif;

								if(isset($sel_contato[1])):
									$sl_contato2_nome = $sel_contato[1]["cont_nome"];
									$sl_contato2_cargo = $sel_contato[1]["cont_cargo"];
									$sl_contato2_especialidade = $sel_contato[1]["cont_especialidade"];
									$sl_contato2_email = $sel_contato[1]["cont_email"];
									$sl_contato2_telemovel = $sel_contato[1]["cont_telemovel"];
								else:
									$sl_contato2_nome = "";
									$sl_contato2_cargo = "";
									$sl_contato2_especialidade = "";
									$sl_contato2_email = "";
									$sl_contato2_telemovel = "";
								endif;

								if(isset($sel_contato[2])):
									$sl_contato3_nome = $sel_contato[2]["cont_nome"];
									$sl_contato3_cargo = $sel_contato[2]["cont_cargo"];
									$sl_contato3_especialidade = $sel_contato[2]["cont_especialidade"];
									$sl_contato3_email = $sel_contato[2]["cont_email"];
									$sl_contato3_telemovel = $sel_contato[2]["cont_telemovel"];
								else:
									$sl_contato3_nome = "";
									$sl_contato3_cargo = "";
									$sl_contato3_especialidade = "";
									$sl_contato3_email = "";
									$sl_contato3_telemovel = "";
								endif;
							endif;
						
						
						if(isset($rs["cli_rgpd"])):
							if($rs["cli_rgpd"] == true):
								$sl_rgpd = "Concordo";
							endif;
							if($rs["cli_rgpd"] == false):
								$sl_rgpd = "Não Concordo";
							endif;
						else: 
							$sl_rgpd = "";
						endif;

						$arr = array(								
							"referencia"=>$rs["cli_referencia"],
							"saudacao"=>$rf,
							"nome"=>$rs["cli_nome"],
							"nif"=>$rs["cli_nif"],
							"morada"=>$rs["cli_rua"],
							"cp"=>$rs["cli_cp"],
							"localidade"=>$rs["cli_localidade"],
							"distrito"=>$sl_distrito,
							"email"=>$rs["cli_email"],
							"telefone"=>$rs["cli_telefone"],
							"area_negocio"=>$sel_area,
							"produto"=>$sel_produto,
							"Contato 1 Nome"=>$sl_contato1_nome,
							"Contato 1 Cargo"=>$sl_contato1_cargo,
							"Contato 1 Especialidade"=>$sl_contato1_especialidade,
							"Contato 1 Email"=>$sl_contato1_email,
							"Contato 1 Telefone"=>$sl_contato1_telemovel,
							"Contato 2 Nome"=>$sl_contato2_nome,
							"Contato 2 Cargo"=>$sl_contato2_cargo,
							"Contato 2 Especialidade"=>$sl_contato2_especialidade,
							"Contato 2 Email"=>$sl_contato2_email,
							"Contato 2 Telefone"=>$sl_contato2_telemovel,
							"Contato 3 Nome"=>$sl_contato3_nome,
							"Contato 3 Cargo"=>$sl_contato3_cargo,
							"Contato 3 Especialidade"=>$sl_contato3_especialidade,
							"Contato 3 Email"=>$sl_contato3_email,
							"Contato 3 Telefone"=>$sl_contato3_telemovel,
							"vendedor"=>$sl_vendedor,
							"Vendedores Associados"=>$sel_associado,
							"rgpd"=>$sl_rgpd
						);
						$info2[] = $arr;

					endif;
                endforeach;

                if(!empty($info2)):
                    array_push($info2, $arr);
                    array_pop($info2);
                    $dados['cliente'] = $info2;
                endif;

                if(!empty($info)):
                	$dados['cliente'] = $info;
            	endif;

            else:

                $dados['cliente'] = array();
            endif;

			if(!empty($dados['cliente'])):
				
	          		$filename = "relatorio_xls_".date("d-m-Y H:s");
		    		$name = "Relatorio Excel";

			        // nome do arquivo
			        $fileName = $filename . '.xls';
			        // Abrindo tag tabela e criando título da tabela
			        $html = '';
			        $html .= '<table border="1">';
			        $html .= '<tr>';
			        $html .= '<th colspan="' . count($dados['cliente'][0]) . '"><h2>' . $name . ' - '.date("d/m/Y H:s").'</h2></th>';
			        $html .= '</tr>';
			        // criando cabeçalho
			        $html .= '<tr>';

			        if(!empty($dados['cliente'])):
				        foreach ($dados['cliente'][0] as $k => $v){
				            $html .= '<th> <h5>' . ucfirst($k) . '</h5></th>';
				        }
				    	endif;
		        	$html .= '</tr>';
			        // criando o conteúdo da tabela

			        for($i=0; $i < count($dados['cliente']); $i++){
			            $html .= '<tr>';
			            foreach ($dados['cliente'][$i] as $k => $v):

			            	if( $k == "area_negocio"):

			            		$vl_area = "";

								if(!empty($v)):
									foreach ($v as $area):
									$vl_area .= $area["area_nome"].", ";
									endforeach;
								else:
									$rs_area = "";
								endif;

								$rs_area = substr($vl_area,0,-2);

								$html .= '<td>' . $rs_area . '</td>';
							
							elseif( $k == "produto"):

								$vl_produto = "";
								
								if(!empty($v)):
								foreach ($v as $produto):       
									$vl_produto .= $produto["prod_nome"].", ";
								endforeach;
								else:
									$rs_produto = "";  
								endif; 

								$rs_produto = substr($vl_produto,0,-2);

								$html .= '<td>' . $rs_produto . '</td>';


							elseif( $k == "Vendedores Associados" || $k == "associado"):

								$vl_associado = "";

								if(!empty($v)):
									foreach ($v as $associado):
									$vl_associado .= $associado["usu_nome"].", ";
									endforeach;
								else:
									$rs_associado = "";
								endif;

								$rs_associado = substr($vl_associado,0,-2);

								$html .= '<td>' . $rs_associado . '</td>';

							elseif( $k == "Contato 1 Cargo"):

								if(!empty($v)):

									$sel_cargo = $cargo->cargoSelecionar($v);
									if(!empty($sel_cargo)):
										$sl_cargo = $sel_cargo[0]["cargo_nome"];
									else:
										$sl_cargo = "";
									endif;
									$html .= '<td>' . $sl_cargo .'</td>';

								else:
									$html .= '<td></td>';
								endif;

							elseif( $k == "Contato 1 Especialidade"):

								if(!empty($v)):

									$sel_especialidade = $especialidade->especialidadeSelecionar($v);
									if(!empty($sel_especialidade)):
										$sl_especialidade = $sel_especialidade[0]["esp_nome"];
									else:
										$sl_especialidade = "";
									endif;
									$html .= '<td>' . $sl_especialidade.'</td>';

								else:
									$html .= '<td></td>';
								endif;

							elseif( $k == "Contato 2 Cargo"):

								if(!empty($v)):

									$sel_cargo = $cargo->cargoSelecionar($v);
									if(!empty($sel_cargo)):
										$sl_cargo = $sel_cargo[0]["cargo_nome"];
									else:
										$sl_cargo = "";
									endif;
									$html .= '<td>' . $sl_cargo .'</td>';

								else:
									$html .= '<td></td>';
								endif;

							elseif( $k == "Contato 2 Especialidade"):

								if(!empty($v)):

									$sel_especialidade = $especialidade->especialidadeSelecionar($v);
									if(!empty($sel_especialidade)):
										$sl_especialidade = $sel_especialidade[0]["esp_nome"];
									else:
										$sl_especialidade = "";
									endif;
									$html .= '<td>' . $sl_especialidade.'</td>';

								else:
									$html .= '<td></td>';
								endif;

							elseif( $k == "Contato 3 Cargo"):

								if(!empty($v)):

									$sel_cargo = $cargo->cargoSelecionar($v);
									if(!empty($sel_cargo)):
										$sl_cargo = $sel_cargo[0]["cargo_nome"];
									else:
										$sl_cargo = "";
									endif;
									$html .= '<td>' . $sl_cargo .'</td>';

								else:
									$html .= '<td></td>';
								endif;

							elseif( $k == "Contato 3 Especialidade"):

								if(!empty($v)):

									$sel_especialidade = $especialidade->especialidadeSelecionar($v);
									if(!empty($sel_especialidade)):
										$sl_especialidade = $sel_especialidade[0]["esp_nome"];
									else:
										$sl_especialidade = "";
									endif;
									$html .= '<td>' . $sl_especialidade.'</td>';

								else:
									$html .= '<td></td>';
								endif;

							else:

								$html .= '<td>'.$v.'</td>';
							endif;

					endforeach;
					$html .= '</tr>';
		        }
		        $html .= '</table>';

		        /*$html = mb_convert_encoding($html, 'UTF-16LE', 'UTF-8');*/

		        // header("Content-Description: PHP Generated Data");
		        // header("Content-Type: application/x-msexcel;charset=UTF-8;base64");
		        // header("Content-Disposition: attachment; filename=\"$fileName\"");
		        // header("Expires: 0");
		        // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				// header("Pragma: no-cache");
								
		        echo $html;

		        exit;

		    endif;


	    }

	}
?>
