<?php
    class App_Config_Ini extends App_Config_Abstract
    {
        protected function toObject($array)
        {
            if (is_array($array)){
                $output = new stdClass();
                foreach ($array as $key => $value){
                    $output->$key = $this->toObject($value);
                }
            }else{
                $output = $array;
            }
            
            return $output;
        }

        protected function parseFile($filename)
        {
            $dataArray = parse_ini_file($filename, true);
            $dataObject = $this->toObject($dataArray);
            return $dataObject;
        }
    }