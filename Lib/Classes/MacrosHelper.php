<?php

namespace igk\JS\VueJS;

use igk\JS\VueJS\Html\ComponentNode;
use igk\JS\VueJS\Html\CoreNode;
use igk\JS\VueJS\Html\RouterLink;
use igk\JS\VueJS\Html\RouterView;
use igk\JS\VueJS\Html\Slot;
use igk\JS\VueJS\Html\Template;
use igk\JS\VueJS\Html\Transition;
use igk\JS\VueJS\IO\JSAttribExpression;
use igk\JS\VueJS\IO\JSExpression;
use IGKException;
use IGKHtmlItem;
use function igk_resources_gets as __; 


abstract class MacrosHelper{
    private function __construct(){        
    }

    public static function vFor(IGKHtmlItem $node, $conditions){
        return $node->setAttribute("v-for", $conditions);
    }
    public static function vIf(IGKHtmlItem $node, $conditions){
        return $node->setAttribute("v-if", $conditions);
    }
    public static function vElse(IGKHtmlItem $node, $conditions){
        return $node->setAttribute("v-else", $conditions);
    }
    public static function vElseIf(IGKHtmlItem $node, $conditions){
        return $node->setAttribute("v-else-if", $conditions);
    }
    public static function vShow(IGKHtmlItem $node, $conditions){
        return $node->setAttribute("v-show", $conditions);
    }
    public static function vHtml(IGKHtmlItem $node, $expression){
        return $node->setAttribute('v-html', $expression); 
    }
    public static function vOnce(IGKHtmlItem $node){
        return $node->activate('v-once'); 
    }
    public static function vBind(IGKHtmlItem $node, $attribute, $value){
        return $node->setAttribute("v-bind:".$attribute, $value);
    }
    public static function vOn(IGKHtmlItem $node, $eventType, $value){
        return $node->setAttribute("v-on:".$eventType, $value);
    }
    /**
     * bind v-model attribute
     * @param IGKHtmlItem $node 
     * @param mixed $value value or attribute :attribute_name
     * @return mixed 
     * if (attribute_name) must provide an extra property for value
     */
    public static function vModel(IGKHtmlItem $node, $value){
        if (func_num_args()==3){
            if ($value[0]==":"){
                return $node->setAttribute("v-model".$value, func_get_arg(2));
            }
            throw new IGKException(__("definition not valid"));
        }
        return $node->setAttribute("v-model", $value);
    }
    public static function vDisabled(IGKHtmlItem $node, $condition){
        return self::vBind($node, "disabled", $condition);
    }
    public static function vClass(IGKHtmlItem $node, $condition){
        return self::vBind($node, "class", $condition);
    }
    public static function vStyle(IGKHtmlItem $node, $condition){
        return self::vBind($node, "style", $condition);
    }
    public static function vKey(IGKHtmlItem $node, $id){
        return self::vBind($node, "key", $id);
    }
    public static function vTransition(IGKHtmlItem $node){
        $n = new Transition();
        $node->add($n);
        return $n;
    }
    ///<summary>create a vcomponent</summary>
    public static function vComponent(IGKHtmlItem $node, $name, $expectedTag=''){
        $n = new ComponentNode($name, $expectedTag);
        $node->add($n);
        return $n;
    }
    public static function vRouterLink(IGKHtmlItem $node, $to){
        $n = new RouterLink();
        $n->setAttribute("to", $to); 
        $node->add($n);
        return $n;
    }
    ///<summary> bind router link helper</summary>
    /**
     *  bind router link helper
     * @param IGKHtmlItem $node 
     * @param mixed $to_expression 
     * @return RouterLink 
     * @throws IGKException 
     */
    public static function vRouterBindLink(IGKHtmlItem $node, $to_expression){
        $n = new RouterLink();
        $n->setAttribute(":to", JSAttribExpression::Create($to_expression)); 
        $node->add($n);
        return $n;
    }
    public static function vRouterView(IGKHtmlItem $node){
        $n = new RouterView(); 
        $node->add($n);
        return $n;
    }
    /**
     * append slot node
     */
    public static function vSlot(IGKHtmlItem $node){
        $n = new Slot();
        $node->add($n);
        return $n;
    }
    public static function vTemplate(IGKHtmlItem $node){
        $n = new Template();
        $node->add($n);
        return $n;
    }
}