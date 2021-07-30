<?php

namespace igk\JS\VueJS\Html;

use igk\JS\VueJS\Utils;
use IGKException;
use IGKHtmlItem;

///<summary>support v2 and v3</summary>
/**
 * in template definition surround Component with keep alive node
 * @package igk\JS\VueJS\Html
 */
class KeepAlive extends CoreNode{    
    public function __construct()
    {
        parent::__construct("keep-alive");
    }
    protected function _AddChild($item, $index=null){
        igk_wln_e("try to add child", $item);
        return false;
    }
}