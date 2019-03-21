<?php
    class App_Registry
    {
        protected static $registers = array();
        
        public static function Set($registry_name, $registry_value)
        {
            self::$registers[$registry_name] = $registry_value;
        }
        
        public static function Get($registry_name)
        {
            if ( !isset(self::$registers[$registry_name]) ){
                throw new Exception("O registro {$registry_name} não existe.");
            }
            
            return self::$registers[$registry_name];
        }
    }