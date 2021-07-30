<?php
namespace igk\JS\VueJS\IO;

use igk\JS\VueJS\IO\JSExpression;
use IGKException;

///<summary>represent expression data</summary>
class JSExpressionMethod extends JSExpression
{
    var $name;
    public function setValue(...$args){
        list($name, $data) = $args;
        $this->name = $name;
        $this->value = $data;
    }
    public function getValue(){
        $s="";
        if (is_string($this->value)){
            $s = $this->name;
            if (strpos($s, "(")=== false)
                $s .= '()';
            $s .= '{'.$this->value.'}';
        }
        return $s;
    }
}