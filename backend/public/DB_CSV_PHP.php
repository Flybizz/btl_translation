<?php
$handle = fopen('planilha.csv','r');
while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
$num = count($data);

	for($c=0; $c < $num; $c++){
		$nome = $data[0];
		$email = $data[1];
		
		$sql = " SQL AQUI";
			
	}
}
fclose($handle);
?>