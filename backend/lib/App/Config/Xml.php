<?php
    class App_Config_Xml extends App_Config_Abstract
    {
        protected function parseFile($filename)
        {
            return simplexml_load_file($filename);
        }
    }