<?php

namespace igk\JS\VueJS\Html;

use igk\JS\VueJS\Utils;
use IGKException;
use IGKHtmlItem;

///<summary>support v2 and v3</summary>
/**
 * in template allow to retreive inner node definition
 * @package igk\JS\VueJS\Html
 */
class Slot extends CoreNode{
    public function getCanAddChild()
    {
        return false;
    }
    public function __construct()
    {
        parent::__construct("slot");
    }
}