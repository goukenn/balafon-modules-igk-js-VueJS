<?php

namespace igk\JS\VueJS\Html;
 

///<summary>support v2 and v3</summary>
/**
 * in append a template definition
 * @package igk\JS\VueJS\Html
 */
class Template extends CoreNode{    
    public function __construct()
    {
        parent::__construct("template");
    }
    public function slotName($name, $value=null){
        $k = "v-slot:".$name;
        $this->deactivate($k);
        if ($value == null){
            $this->activate($k);
        }
        else {
            $this->setAttribute($k, $value);
        }
        return $this;
    }
}