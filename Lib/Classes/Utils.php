<?php
namespace igk\JS\VueJS;


final class Utils{
    private function __construct(){        
    }
    public static function module(){
        return igk_get_module(__NAMESPACE__);
    }
}