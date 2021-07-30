<?php

namespace igk\JS\VueJS\Html;

use igk\JS\VueJS\Utils;
use IGKException;
use IGKHtmlItem;

///<summary>support v2 and v3</summary>
/**
 * in template definition surround component with supense node
 * @package igk\JS\VueJS\Html
 */
class Suspense extends CoreNode{    
    public function __construct()
    {
        parent::__construct("Suspense");
    }
    
}