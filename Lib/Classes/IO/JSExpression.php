<?php

namespace igk\JS\VueJS\IO;

use igk\JS\VueJS\IJSStringify;
use IGKException;
use JsonSerializable;
///<summary>JSExpression class </Summary>
class JSExpression
{

    protected $value;

    protected function __construct()
    {
    }
    /**
     * CREATE A JS EXPRESSION TO USE IN VUEJS PHP
     * @param mixed $s 
     * @return static 
     */
    public static function Create($s)
    {
        $exp = new static;
        if (!is_string($s)){
            if (is_array($s)){
                $s = (object)$s;
            }
            $s = JSExpression::Stringify($s, (object)[
                "objectNotation"=>true
            ]);
        }
        $exp->value = $s;
        return $exp;
    }
    protected function setValue($value)
    {
        $this->value = $value;
    }
    public static function Import($src){        
        return self::Create("()=>import('".$src."')");
    }
    public static function CreateMethod($name, $expression){
        return self::Factory("Method", $name, $expression);
    }
    public static function Factory($type, ...$args)
    {
        $cl = __NAMESPACE__ . "\\JSExpression" . ucfirst($type);
        if (!class_exists($cl)) {
            igk_die("class not exists : " . $cl);
            return null;
        }
        $c = new $cl();
        $c->setValue(...$args);
        return $c;
    }
    ///<summary>create a property expression</summary>
    /**
     * create a property expression
     * @param mixed $name property name
     * @param mixed $args argument to pass
     * @return null|JSExpression return a property expression
     * @throws IGKException 
     */
    public static function Property($name, ...$args): JSExpression
    {
        return self::Factory("Property", $name, ...$args);
    }
    public function getValue()
    {
        if (is_array($this->value)){
            return json_encode($this->value);
        }
        return $this->value;
    }

    public static function Stringify($tab, $options = null)
    {
        $tq = [['n' => $tab, 'data' => null]];
        $s = "";
        $p = 0;
        $debug = 0;
        if ($options===null){
            $options = (object)[];
        }
        $notation = igk_getv($options, 'shortNotation');
        $objectNotation = igk_getv($options, 'objectNotation');
        $meth_detect = "/\((.+)?\)$/";
        while ($qt = array_shift($tq)) {

            $p++;
            $q = $qt['n'];
            $data = $qt['data'];
            $end = "";
            $ch = "";
            if ($data === null) {
                if ($objectNotation && is_array($q)){
                    $q = (object)$q;
                }
                if ($a = is_array($q)) {
                    $s .= "[";
                    $end = "]";
                    if (igk_array_is_assoc($q)) {
                        $q = (object)$q;
                        $a = 0;
                        $s .= "{";
                        $end = "}]";
                    }
                } else if (is_object($q)) {
                    $s .= "{";
                    $end = '}';
                }
            } else {
                $end = $data->end;
                $ch = $data->ch;
                $a = $data->a;
            }

            $ctab = $data && $data->ctab ? $data->ctab : (array)$q;
            $keys = $data && property_exists($data, 'keys') ? $data->keys : array_keys($ctab);
         

            while (($k = array_shift($keys)) !== null) {
                $tv = $ctab[$k];
                $s .= $ch;


                if (is_string($tv) && !is_numeric($k) && preg_match($meth_detect, $k)){
                    $tv = self::CreateMethod($k, $tv);
                    $k = -1;                    
                }
                if ((!$a) && !(is_numeric($k) && ($tv instanceof JSExpression))) {
                    $s .= "\"" . $k . "\":";
                }
                if ($tv === null) {
                    $s .= 'null';
                } else {
                    if (is_numeric($tv)) {
                        $s.= $tv;
                    } else if (is_string($tv)) {
                        $s .= "\"" . str_replace("/", "\\/", addslashes($tv)) . "\"";
                    } else if (is_bool($tv)){
                        $s .= $tv ?'true':'false';
                    }
                    else if (($is_a = is_array($tv)) || is_object($tv)) {
                        $ch = ",";
                        if ($is_a && igk_array_is_assoc($tv)) {
                            $tv = (object)$tv;
                        }
                        if ($tv instanceof JSExpression) { 
                            $options->express = (object)array_merge(["name"=>$k], compact("a", "end" , "ch"));                            
                            $s .= $tv->getValue($options); 
                            unset($options->express);
                            continue;
                        }
                        if ($tv instanceof IJSStringify) { 
                            $options->express = (object)array_merge(["name"=>$k], compact("a", "end" , "ch"));                            
                            $s .= $tv->stringify($options); 
                            unset($options->express); 
                            continue;
                        }
                        array_unshift($tq, ['n' => $q, 'data' => (object)compact("ctab", "keys", "end", "ch", "a")]);
                        array_unshift($tq, ['n' => $tv, 'data' => null]);
                        continue 2;
                    }
                }
                $ch = ",";
            }
            $s .= $end;
        }
        return $s;
    }
}
