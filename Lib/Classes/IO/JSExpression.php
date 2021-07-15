<?php

namespace igk\JS\VueJS\IO;

use JsonSerializable;

class JSExpression implements JsonSerializable{
    public $v;

    public function __construct($v){
        $this->v = $v;
    }
    public function getValue(){
        return $this->v;
    }
    public function jsonserialize(){
        return 'cont';
    }
    /**
     * convert array string to string expression
     */
    public static function stringify($tab){
        // TODO Stringify
        $s = "";
        $cp = [$tab];
        while($q = array_pop($cp)){
            $end = "";
            if (is_object($q)){
                $s .= "{";
                $end.= "}";
            }else if (is_array($q)) {
                $s .= "[";
                $end = "]";
            }
            $ch="";
            foreach($q as $k=>$v){
                if (!$v){

                }
                $s .= $ch;
                if($end != "]"){
                    $s .= '"'.$k.'":';
                }
                

                $t = "";
                if (is_object($v)){
                    if ($v instanceof JSExpression){
                        $t = $v->v;
                    }
                    else {
                        array_unshift($cp, $v);
                        continue;
                    }
                }
                else{
                    $t = $v;
                }
                $s.= $t;
                $ch=",";
            }
            $s.= $end;
            
        }

        return $s;
    }
}