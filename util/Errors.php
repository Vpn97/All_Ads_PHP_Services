<?php

class Errors{
    public $code;
    public $message;
    public $module;

    public function __construct($code,$message,$module){
        $this->code=$code;
        $this->message=$message;
        $this->module=$module;
    }
}

?>