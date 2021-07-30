<?php

namespace igk\JS\VueJS\Html;

use igk\JS\VueJS\IO\JSExpression;
use igk\JS\VueJS\IO\OptionBuilder;
use igk\JS\VueJS\Polyfill;
use igk\JS\VueJS\PolyfillV3;
use igk\JS\VueJS\Utils;
use igk\JS\VueJS\VueStorage;
use IGKException;
use IGKHtmlItem;

/**
 * create a VUE JS application node
 * @package igk\JS\VueJS\Html
 */
class App extends CoreNode
{
    /**
     * storage to use
     * @var VueStorage
     */
    private $m_storage;
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

    /**
     * use router
     * @var mixed
     */
    var $use_router;

    /**
     * use vuex
     */
    var $use_vuex;

    /**
     * history type : default will be webhistory on Vue3
     * @var mixed
     */
    var $historyType;
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
        $this->use_router = Utils::Module()->Configs->VueRouter;
        $this->use_vuex = Utils::Module()->Configs->VueEx;
    }
    /**
     * 
     * @param mixed $name 
     * @param mixed|array|null $data 
     * @return $this 
     */
    public function addComponent($name, $data=null){
        if (is_string($data)){
            $this->components[$name] = JSExpression::Create($data);
        }
        else {
            $i = igk_html_node_vuejs_component($name, $data);
            $this->components[$name] = $i;
        }
        return $this;
    }
    ///<summary>add allowed filter to application</summary>
    public function addFilter($name, $data){
        return $this;
    }
    ///<summary>add directive to application</summary>
    public function addDirective($name, $data){
        return $this;
    }

    public function useStorage(?VueStorage $storage){
        $this->m_storage = $storage; 
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
        $mod = Utils::Module();
        $polyfill = $mod->getPolyfill(); 
        $is_web = $opt && ($opt->Context == "html");
        if ($is_web && ($polyfill)){             
            $polyfill->bindData($this, $opt);          
        }else if ($this->data) {
            Polyfill::Create(2)->bindData($this, $opt);          
        } 
        if ($opt){
            $opt->vueJSApp = $this;
        }
        if ($this->m_storage){
            igk_wln_e("use storage function "); 
            $c = igk_createnode("script");
            $c->Content = "alert('init storage'); "; 
            igk_html_render_append_item($opt, $c); 
        }
        return true;
    }
    protected function __RenderComplete($opt = null)
    {
        parent::__RenderComplete($opt);
        // remove render 
        unset($opt->vueJSApp);  
    }
}
