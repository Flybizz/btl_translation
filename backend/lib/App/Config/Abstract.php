<?php
    abstract class App_Config_Abstract
    {
        protected $fileContents = array();
        
        public function __construct($filename)
        {
            $this->loadFile($filename);
        }
        
        protected function loadFile($filename)
        {
            $contents = $this->parseFile($filename);
            $this->fileContents = $contents;
        }

        abstract protected function parseFile($filename);
        
        public function catchObject()
        {
            return $this->fileContents;
        }
    }