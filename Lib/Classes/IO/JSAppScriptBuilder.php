<?php
// @author: C.A.D. BONDJE DOUE
// @file: JSAppScriptBuilder.php
// @desc: 
// @date: 20210716 08:32:16
namespace igk\JS\VueJS\IO;


class JSAppScriptBuilder{
    const OPTIONS = "def|route|routerScript|bindDefinitions";
    public function __get($n){
        return null;
    }
   
    public function __call($n, $v){
        if (in_array($n, explode('|', self::OPTIONS)) && (count($v)==1)){
            $this->$n = $v[0];
        }
    }

    public function build(){
        $list = [];
        foreach(explode('|', self::OPTIONS) as $b){
            if ($c = $this->$b){
                $list[$b] =  $c;
                var_dump($list);
                igk_wln_e($c, $list);
            }
        }
        return $list;
    }
}