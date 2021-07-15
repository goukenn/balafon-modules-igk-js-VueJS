<?php

namespace igk\JS\VueJS;

use igk\JS\VueJS\Html\ComponentNode;
use IGKHtmlItem;

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
    public static function vModel(IGKHtmlItem $node, $value){
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
    public static function vComponent(IGKHtmlItem $node, $expectedTag=''){
        $n = new ComponentNode($expectedTag);
        $node->add($n);
        return $n;

    }
}