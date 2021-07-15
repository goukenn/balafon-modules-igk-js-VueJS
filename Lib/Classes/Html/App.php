<?php

namespace igk\JS\VueJS\Html;

use igk\JS\VueJS\IO\OptionBuilder;
use igk\JS\VueJS\Polyfill;
use igk\JS\VueJS\PolyfillV3;
use IGKException;
use IGKHtmlItem;

/**
 * create a VUE JS application node
 * @package igk\JS\VueJS\Html
 */
class App extends IGKHtmlItem
{
    /**
     * application data to render
     * @var mixed
     */
    var $data;
    /**
     * component to use
     * @var array
     */
    var $components;
    /**
     * directive to use
     * @var array
     */
    var $directives;
    /**
     * filters to use
     * @var array
     */
    var $filters;  

    /**
     * mixing properties to use
     * @var mixed
     */
    var $mixins; 
    ///<summary>.ctrl</summary>
    /**
     * 
     * @param string $tagname 
     * @return void 
     */
    public function __construct($tagname = "div")
    {
        parent::__construct($tagname);
        $this->setClass("vuejs-app");
        $this->components = [];
        $this->directives  =[];
        $this->filters = []; 
    }
    public function addComponent($name, $data=null){
        $i = igk_html_node_vuejs_component($name, $data);
        $this->components[$name] = $i;
    }
    /**
     * override accept render to attach view js in node
     * @param mixed|null $opt 
     * @return bool 
     * @throws IGKException 
     */
    protected function __AcceptRender($opt = null)
    {
        if (!$this->getIsVisible())
            return false;

        $mod = igk_get_module(\igk\JS\VueJS::class);
        $polyfill = $mod->getPolyfill();
 

        $src = null;
        $is_web = $opt && ($opt->Context == "html");
        if ($is_web && ($polyfill)){             
            $polyfill->bindData($this, $opt);          
        }else if ($this->data) {
            Polyfill::Create(2)->bindData($this, $opt);          
        } 
        return true;
    }
}
