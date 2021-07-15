<?php

namespace igk\JS\VueJS\Html;

use IGKException;
use IGKHtmlItem;

/**
 * create a VUE JS application node
 * @package igk\JS\VueJS\Html
 */
class Component extends IGKHtmlItem
{
    var $props;
    var $template; 
    private $m_name;
    public function __construct($name, array $args=null){
        parent::__construct("script");
        $this->setAttribute("type", "text/javascript");
        $this->setAttribute("language", "javascript");
        $this->Content = $this;
        $this->m_name = $name;

        if ($args){
            $this->template = igk_getv($args, "template");
            $this->props = igk_getv($args, "props");
        }
    }
    public function getContent(){
        return $this->getValue();
    }

    public function getValue(){
        $cm = igk_get_module(\igk\JS\VueJS::class )->getPolyfill()->getVersion();

        if ($cm == 2){
            $g = [];
            $g[] = "Vue.component('{$this->m_name}', {";
                if ($this->template){
                    $g[] = "template: '".$this->template."', ";
                }
                if ($this->props){
                    $g[] = "props: ".json_encode($this->props).", ";
                }
            $g[] = "});";
            return implode("\n", $g); 
        }
        return "// component not allowed";
    }

}