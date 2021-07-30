<?php

namespace igk\JS\VueJS\Html;

use igk\JS\VueJS\Utils;
use IGKException;
use IGKHtmlItem;

///<summary>support v2 and v3</summary>
/**
 * attribute name to define multi view in a layout
 * @package igk\JS\VueJS\Html
 */
class RouterView extends CoreNode{
    /**
     * transition options
     * @var mixed
     */
    var $transitions;
    protected function _AddChild($item, $index=null)
    {
        if ($item instanceof Transition){
            $this->transitions = $item;
            return true;
        } 
        return false;
    }
    public function __construct()
    {
        parent::__construct("router-view");
        $this->transitions= null; 
    }
    public function vSlot($attr){
        return $this->setAttribute("v-slot", $attr);
    }
    public function getTagName($options=null)
    {
        $version = Utils::polyfillversion();
        if (($version == 2) && $this->transitions ){
            return "transition";
        }
        return parent::getTagName();
    }

    public function getAttributeString($options=null){               
        if ((Utils::polyfillversion() == 2) && ($this->transitions)){
            return $this->transitions->getAttributeString($options);
        } 
        return parent::getAttributeString($options); 
    }
   
    public function __AcceptRender($options = null){
        if (!parent::__AcceptRender($options))
            return false;       
        return true;
    }
    protected function __getRenderingChildren($options=null){

        $version = Utils::polyfillversion(); 
        $app = igk_getv($options, "vueJSApp");
        $supportRoute = $app ? Utils::SupportRoute($app) : true; 
        $tab = array();
        //igk_wln_e("the version : ".$version, "route ? ", $supportRoute, $app->data);
        if ($supportRoute && $this->transitions){
            if ($version==2){
                $routerView = new IGKHtmlItem("router-view");
                $tab[] = $routerView;
            }    
            else {
                $tab[] = $this->transitions;
            }
        }
        return $tab;
    }
}