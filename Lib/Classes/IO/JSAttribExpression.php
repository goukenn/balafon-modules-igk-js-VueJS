<?php
namespace igk\JS\VueJS\IO;

class JSAttribExpression{
    private $value;
    protected function __construct(){

    }
    public static function Create($expression){
        $s = new static;
        $s->value = $expression;
        return $s;
    }
    public function getValue($options=null){
        if ($options)
            $options->setnoAttribEscape = true;
        return $this->value;
    }
}