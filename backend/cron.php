<?php

error_reporting(E_ALL);
if($_GET["access_token"] != "ce4b32e125a682f4416e949d66ca8a3a"){
   die("Access denied");
}

require_once 'lib/App/Autoloader.php';
$config = unserialize(CONFIG_DB);

//using autoload
$cron = new App_Cron($config);
$cron->get_notifications();
$cron->filter_notifications();
$cron->send();

?>