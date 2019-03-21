<?php
	header("access-control-allow-origin: https://pagseguro.uol.com.br");
	//require_once("PagSeguro.class.php");

	if(isset($_POST['notificationType']) && $_POST['notificationType'] == 'transaction'){
		$PagSeguro = new App_PagSeguro();
		$response = $PagSeguro->executeNotification($_POST);
		if( $response->status==3 || $response->status==4 ){
        	//PAGAMENTO CONFIRMADO
			//ATUALIZAR O STATUS NO BANCO DE DADOS
			echo "pagamento confirmado";
			
		}else{
			//PAGAMENTO PENDENTE
			echo $PagSeguro->getStatusText($PagSeguro->status);
		}
	}
?>