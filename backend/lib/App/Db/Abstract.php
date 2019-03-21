<?php
    abstract class App_Db_Abstract
    {
        protected function getDbConnection()
        {
            return App_Db_Adapter::factory();
        }

        public function beginTransaction()
        {
            return $this->getDbConnection()->beginTransaction();
        }
        
        public function commit()
        {
            return $this->getDbConnection()->commit();
        }
        
        public function errorCode()
        {
            return $this->getDbConnection()->errorCode();
        }
        
        public function errorInfo()
        {
            return $this->getDbConnection()->errorInfo();
        }
        
        public function exec($statement)
        {
            return $this->getDbConnection()->exec($statement);
        }
        
        public function getAttribute($attribute)
        {
            return $this->getDbConnection()->getAttribute($attribute);
        }
        
        public function getAvailableDrivers()
        {
            return $this->getDbConnection()->getAvailableDrivers();
        }
        
        public function inTransaction()
        {
            return $this->getDbConnection()->inTransaction();
        }
        
        public function lastInsertId($name = null)
        {
            return $this->getDbConnection()->lastInsertId($name);
        }
        
        public function prepare($statement, $driver_options = array())
        {
            return $this->getDbConnection()->prepare($statement, $driver_options);
        }
        
        public function query($statement)
        {
            return $this->getDbConnection()->query($statement);
        }
        
        public function quote($string, $parameter_type = PDO::PARAM_STR)
        {
            return $this->getDbConnection()->quote($string, $parameter_type);
        }
        
        public function rollBack()
        {
            return $this->getDbConnection()->rollBack();
        }
        
        public function setAttribute($attribute, $value)
        {
            return $this->getDbConnection()->setAttribute($attribute, $value);
        }
    }