<?php

namespace igk\JS\VueJS\IO;
/**
 * use to build vuejs's application options . vor VueJS 2.0+
 * @package 
 */
class OptionBuilder{
    /**
     * list of options methods for definitions
     */
    public const METHODS = [
        "created", "computed", "watch", "methods", "mixins", 'beforeMount',
        'beforeUpdate', 'beforeDestroy','mounted','updated', 'provide', 'inject', "components"
    ];
    /**
     * route definitions
     * @var mixed
     */
    public $routes;

    /**
     * to initialize router script
     * @var array|string 
     */
    var $routerScript;

    /**
     * extra routes options. do not set routes and history there. 
     * @var mixed
     */
    var $routerOptions;

    /**
     * represent application script
     * @var mixed
     */
    var $appScripts;


    /**
     * 
     * @return array of 
     */
    private $m_library;

    public function __construct()
    {    
        $this->m_library = array();    
    }

    public function pushLibrary(JSAppDefinition $def ){
        array_push($this->m_library, $def);
    }
    public function clearLibrary(){
        $this->m_library = array();
    }
    public function getLibrary(){
        return $this->m_library; 
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
        // + | properties
        foreach(["routes", "routerScript", "routerOptions", "appScripts"] as $k){
            if ($p = $this->$k){
                $list[$k]= $p;
            }
        } 
        if ($this->m_library){
            $list["library"] = $this->m_library ;
        }
        return $list;
    }
    public function __get($name){
        return null;
    }
    public function __call($name, $args){
        if (in_array($name, self::METHODS)){
            $this->$name = $args[0];
        }
        else 
            die("operation not allowed : ".$name);
    }
}