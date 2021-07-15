<?php

namespace igk\JS\VueJS\Html;

use IGKException;
use IGKHtmlItem;

/**
 * a transition template node
 * @package igk\JS\VueJS\Html
 */
class TransitionGroup extends CoreNode{
    //
    public function setAttribute($name, $value){
        // only available attribute are allowed 
        return parent::setAttribute($name, $value);
    }
    public function setName($name){
        die("not allowed");        
    }
    public function setType($type){        
        $s = $type ? ( strtolower($type) == "animation"?"animation":"transition" ) : null;        
        return $this->setAttribute("type", $s);
    }
    public function getType(){
        return $this["type"];
    }
    public function setMode($mode){
        $s = $mode ?  (strtolower($mode) == "in-out"?"in-out" : "out-in") : null; 
        return $this->setAttribute("mode", $s ); 
    }
    public function getMode(){
        return $this["mode"];
    }
    public function getTag(){
        return $this["tag"];
    }
    /**
     * set the tag to use
     * @param mixed $tag 
     * @return CoreNode 
     */
    public function setTag($tag){
        return $this->setAttribute("tag", $tag);
    }
    public function __construct()
    {
        parent::__construct("transition-group");
    }
}