<?php
namespace igk\JS\VueJS;

use IGK\Helper\Utility;
use igk\JS\VueJS\IO\JSExpression;
use igk\JS\VueJS\IJSStringify;

/** @package igk\JS\VueJS */
class JSData implements IJSStringify{
    public function stringify($option =null){
        $data = array_filter((array)$this);        
        return JSExpression::Stringify((object)$data);
    }
    public function __construct()
    {
        $tab = func_get_args();        
        if ($tab){
            foreach($tab[0] as $k=>$v){
                if (property_exists($this, $k)){
                    $this->$k = $v;
                }
            }
        }
    }
}