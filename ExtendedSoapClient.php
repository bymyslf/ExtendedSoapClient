<?php
    class ExtendedSoapClient extends SoapClient
    {
        protected $returnResponseAsArray = false;
        
        public function __construct($wsdl, array $options = array(), $returnArray = false) {
            parent::__construct($wsdl, $options);
            $this->responseAsArray($returnArray); 
        }
    
        public function responseAsArray($value = null) {
            if (isset($value) && is_bool($value) === true) {
                $this->returnResponseAsArray = $value;
            } 
            
            return $this->returnResponseAsArray;
        }

        public function __soapCall($function, $arguments, $options = array(), $inputHeaders = null, &$outputHeaders = null) {
            $result = parent::__soapCall($function, $arguments, $options, $inputHeaders, $outputHeaders);
   
            if ($this->returnResponseAsArray) {
                return self::objectToArray($result);
            }
            
            return $result;
        }
    
        public function __call($function, $arguments) {
            return $this->__soapCall($function, $arguments);
        }
        
        protected static function objectToArray($object) {
            if (is_object($object)) {
                $object = get_object_vars($object);
            }
            
            return is_array($object) ? array_map(array('self', 'objectToArray') , $object) : $object;
        }
        
        protected static function debug($data) {
            echo '<pre>', print_r($data), '</pre>';
        }
    }         
?>