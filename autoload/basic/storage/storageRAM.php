<?php

namespace PopUpMakerForCF7\basic\storage;

class storageRAM{
    private $data = [];
    
    function __construct($data = []){
        foreach ($data as $key => $value) { $this->data[$key] = $value; }
    }

    function get($key){
        return $this->data[$key];
    }

    function set($key,$val){
        return $this->data[$key] = $val;
    }


}
