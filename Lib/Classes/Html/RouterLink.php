<?php

namespace igk\JS\VueJS\Html;

use IGKException;
use IGKHtmlItem;

/**
 * router link
 * @package igk\JS\VueJS\Html
 */
class RouterLink extends CoreNode{
    public function getCanAddChild()
    {
        return false;
    }
    public function __construct()
    {
        parent::__construct("router-link");
    }
    ///<summary>use to indicate the uri to be replaced</summary>
    public function replace($active = true){
        if ($active)
            $this->activate("replace");
        else 
            $this->deactivate("replace");
        return $this;
    }
}