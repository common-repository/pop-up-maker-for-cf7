<?php
namespace PopUpMakerForCF7\basic\storage;

class storage{

    static public $storages = [
        'RAM' => [],
        'DB' => []
    ];

    function __construct($storage){
        foreach (self::$storages as $storageName => $empty_val) {
            $storageClassName = 'PopUpMakerForCF7\basic\storage\storage'.$storageName;
            if(key_exists($storageName,$storage)){
                self::$storages[$storageName] = new $storageClassName($storage[$storageName]);
            }else{
                self::$storages[$storageName] = new $storageClassName([]);
            }
        }
    }

    function one_call_init(){
        foreach (self::$storages as $storage_obj){
            if(method_exists($storage_obj,'one_call_init')){
                $storage_obj->one_call_init();
            }
        }
    }

    function destroy(){
        foreach (self::$storages as $storage_obj){
            if(method_exists($storage_obj,'destroy')){
                $storage_obj->destroy();
            }
        }
    }

    function get($key,$storage = 'RAM'){
        return self::$storages[$storage]->get($key);
    }

    function set($key,$val,$storage = 'RAM'){
        return self::$storages[$storage]->set($key,$val);
    }

    function del($key,$storage = 'RAM'){
        return self::$storages[$storage]->del($key);
    }

    

    

}
