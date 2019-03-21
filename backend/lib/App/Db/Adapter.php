<?php
    class App_Db_Adapter
    {
        protected function __construct(){}
        
        public static function factory()
        {
            $configs = App_Registry::Get(App_Config::NAME_CONFIG_REGISTRY);
            $adapter = $configs->db->adapter;
            switch ($adapter) {
                case "mysql":
                    return App_Db_Adapter_Mysql::getConnection($configs->db);
                    break;
                default:
                    throw new Exception("Adaptador não suportado.");
                    break;
            }
        }
    }