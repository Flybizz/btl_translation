<body>
<div id="container">
<div id="form">
  
<?php
$deleterecords = "TRUNCATE TABLE nome-da-tabela"; //Esvaziar a tabela
mysql_query($deleterecords);
  
//Transferir o arquivo
if (isset($_POST['submit'])) {
  
    if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
        echo "<h1>" . "File ". $_FILES['filename']['name'] ." transferido com sucesso ." . "</h1>";
        echo "<h2>Exibindo o conteúdo:</h2>";
        readfile($_FILES['filename']['tmp_name']);
    }
  
    //Importar o arquivo transferido para o banco de dados
    $handle = fopen($_FILES['filename']['tmp_name'], "r");
  
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $import="INSERT into importing(text,number)values('$data[0]','$data[1]')";
  
        mysql_query($import) or die(mysql_error());
    }
  
    fclose($handle);
  
    print "Importação feita.";
  
//Visualizar formulário de transferência
} else {
  
    print "Transferir novos arquivos CSV selecionando o arquivo e clicando no botão Upload<br />\n";
  
    print "<form enctype='multipart/form-data' action='upload.php' method='post'>";
  
    print "Nome do arquivo para importar:<br />\n";
  
    print "<input size='50' type='file' name='filename'><br />\n";
  
    print "<input type='submit' name='submit' value='Upload'></form>";
  
}
  
?>
  
</div>
</div>
</body>