<?php

namespace igk\JS\VueJS\IO;

use Illuminate\Validation\Rules\Exists;

/**
 * use to build option properties . vor VueJS +
 * @package 
 */
class ComponentOptionBuilder{
    public function __construct($data=null)
    {        
        if ($data){
            foreach($data as $k=>$v){
                $this->$k = $v;
            }
        }
    }
    public function build(){
        $el = $this->el; 
        $list = [];
        $require = ["props", "template"];
        $cl = $require;
        foreach( $cl as $k){
            if ($m  = $this->$k){
                $list[$k] = $m;
            }
        }
        foreach($require as $c){
            if (!key_exists($c, $list)){
                igk_die("VueJS: Component property not found ".$c);
            }
        }
        return $list;
    }
    public function __get($name){
        return null;
    }
    public function __call($name, $args){
        $this->name = $args[0];
    }
}