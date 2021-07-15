<?php

namespace igk\JS\VueJS\IO;
/**
 * use to build option properties . vor VueJS +
 * @package 
 */
class OptionBuilder{
    /**
     * list of options methods
     */
    public const METHODS = [
        "created", "computed", "watch", "methods", "mixins"
    ];
    /**
     * route definitions
     * @var mixed
     */
    public $routes;
    public function __construct()
    {        
    }
    public static function JsValue($n){
        return new JSExpression($n);
    }
    public function build(){
        $el = $this->el; 
        $list = [];
        $cl = array_merge(["el", "data"] , self::METHODS );
        foreach( $cl as $k){
            if ($m  = $this->$k){
                $list[$k] = $m;
            }
        }
        $list["routes"] = $this->routes;
        return $list;
    }
    public function __get($name){
        return null;
    }
    public function __call($name, $args){
        $this->name = $args[0];
    }
}