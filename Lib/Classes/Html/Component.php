<?php

namespace igk\JS\VueJS\Html;

use igk\JS\VueJS\IO\JSExpression;
use igk\JS\VueJS\Utils;
use IGKException;
use IGKHtmlItem;

/**
 * create a VUE JS application node
 * @package igk\JS\VueJS\Html
 */
class Component extends CoreNode
{
    var $props;
    var $template; 
    var $emits;
    var bool $inheritAttrs; 
    var array $components;
    var $methods;
    var $provide; // provide data to child
    var $inject; // inject data to use
    var bool $suspensible;

    private $m_name;
    public static function getProperties(){
        return ["props", "template", "emits", "inheritAttrs", "components", "methods", "provide", "inject", "suspensible"];
    }
    public function __construct($name, array $args=null){
        parent::__construct("script");
        $this->setAttribute("type", "text/javascript");
        $this->setAttribute("language", "javascript");
        $this->Content = $this;
        $this->m_name = $name; 
        $this->suspensible = true;
        foreach(array_keys($args) as $k){
            if (property_exists($this, $k)){
                $this->$k = $args[$k];
            }
        } 
    }
    public function getContent(){
        return $this->getValue();
    }

    protected function __AcceptRender($options =null){
        $cm = Utils::PolyfillVersion();  
        if ($cm == 3){ 
            return false;
        }
        return parent::__AcceptRender($options);
    }

    public function getValue(){
        $cm = Utils::PolyfillVersion();  
        if ($cm == 2){
            $g = [];
            $g[] = "Vue.component('{$this->m_name}', {";
            foreach($this->getProperties() as $k){
                if (($k=="suspensible") && ($this->$k)){
                    continue;
                }
                if ($st = JSExpression::Stringify($this->$k)){
                    $g[] = $k .":".$st;
                }
            }
                // if ($this->template){
                //     $g[] = "template: '".$this->template."', ";
                // }
                // if ($this->props){
                //     $g[] = "props: ".json_encode($this->props).", ";
                // }
            $g[] = "});";
            return implode("\n", $g); 
        }       
        return "/* component not allowed */";
    }

}