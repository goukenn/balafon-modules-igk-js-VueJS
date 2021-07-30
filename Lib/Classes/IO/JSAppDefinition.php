<?php
namespace igk\JS\VueJS\IO;

use igk\JS\VueJS\Polyfill;
use IGKException;
use function igk_resources_gets as __ ;

///<summary>factory app definition</summary>
abstract class JSAppDefinition {

    protected $id;
    protected $definition; 

    protected function __construct(){        
    }

    public static function Create($name, $id, array $definition){
        $cl = __NAMESPACE__."\\JSApp".ucfirst($name)."Definition";
        
        if (class_exists(($cl))){
            $o = new $cl();
            $o->id = $id;
            $o->definitions = $definition;
            return $o;
        }
        throw new IGKException(__("No definition  {0} found ", $name)); 
    }


    abstract function BuildDef($polyfill, $sciptObjectToUpdate );        

}