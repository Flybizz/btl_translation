<meta charset="utf-8" />
<?php
if ( !( isset($_SESSION['logado']) ) ){
    echo "
	<script type='text/javascript'>
	  document.location.href='/'
	</script>";

    exit;
}

?>