<?php

namespace igk\JS\VueJS\Html;

use IGKException;
use IGKHtmlItem;
use igk\JS\VueJS\Utils;

use function igk\JS\VueJS\module as module;

/**
 * a transition template node
 * @package igk\JS\VueJS\Html
 */
class ComponentNode extends CoreNode{
    private $expectedTag;
    private $m_name; 
    /**
     * bind key name
     * @var mixed
     */
    private $m_bind;
    public function setBind(bool $bind) {
        $this->m_bind = $bind;
        return $this;
    }
    public function getTagName()
    {   
        $rendering = 0;
       if ( $pt = igk_environment()->render_option){
            $rendering = $pt[0]->renderNode === $this; 
       }
        if ($rendering){
            $pversion = Utils::Module()->getPolyfill()->getVersion();
            if (version_compare("3", $pversion,">=")){
                $s = "component";
            }else {
                $s = $this->expectedTag;
            }
            return $s;
        }

        return parent::getTagName();

    }
    public function __construct($componentName, $expectedTag='div')
    {
        parent::__construct("vuejs-component");
        $this->expectedTag = $expectedTag;
        $this->m_name = $componentName; 
    }
    public function __AcceptRender($options=null){
        if (!$this->getIsVisible()){
            return false;
        }
        $key = "is";
        if ($this->m_bind){
            $key = ":".$key;
        }
        $this[$key] = $this->m_name;
        return true;
    }
}