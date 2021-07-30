<?php

namespace igk\JS\VueJS\Html;

use IGKException;
use IGKHtmlItem;

/**
 * abstract core node
 * @package igk\JS\VueJS\Html
 */
abstract class CoreNode extends IGKHtmlItem{
    const MEMORY_HISTORY = "memory";
    const HASH_HISTORY = "hash";
    const WEB_HISTORY = "web";
    /**
     * 
     * @param mixed $tagname 
     * @return void 
     */
    public function __construct($tagname)
    {
        parent::__construct($tagname);
    }
}