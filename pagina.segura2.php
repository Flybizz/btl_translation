<meta charset="utf-8" />
<?php
$config = unserialize(CONFIG_DB);
if ( !( isset($_SESSION['logado']) ) && $config[0]["D001_PC_status"] == "true" ){

    echo "
    <script type='text/javascript'>
	  /*alert('É necessário estar logado.');*/
	  document.location.href='/'
	</script>";

    exit;
}

?>