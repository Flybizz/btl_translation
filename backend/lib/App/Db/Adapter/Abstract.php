<?php
    abstract class App_Db_Adapter_Abstract
    {
        protected static $connection;
        
        public static function getConnection($db_configs)
        {
            if ( empty(self::$connection) ){
                $dsn = "{$db_configs->adapter}:host={$db_configs->host};dbname={$db_configs->db_name}";
                self::$connection = new PDO($dsn, $db_configs->user, $db_configs->pass);
            }
            
            return self::$connection;
        }
    }