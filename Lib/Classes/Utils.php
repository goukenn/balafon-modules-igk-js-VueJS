<?php
namespace igk\JS\VueJS;

use igk\JS\VueJS\Html\App;
use igk\JS\VueJS\IO\JSExpression;
use igk\JS\VueJS\IO\JSExpressionMethod;
use igk\JS\VueJS\IO\OptionBuilder;

final class Utils{
    const DEFAULT_VERSION = 3;
    private function __construct(){        
    }
    public static function SupportRoute(App $app){
        return $app->use_router && igk_getv($app->data, "routes");
    }
    public static function Module(){
        return igk_get_module(__NAMESPACE__);
    }
    public static function PolyfillVersion(){
        return igk_getv(self::Module()->Configs, "PolyfillVersion", self::DEFAULT_VERSION); 
    }
    public static function ConfigStartApp($app, $doc){
        $app->setClass("vuejs-init-hide");
        if (!($style = igk_environment()->get("VueJS/Style"))) {
            $style = igk_createnode("style");
            $style->Content = ".vuejs-app{ opacity:1.0; transition: .5s opacity;} .vuejs-app.vuejs-init-hide{ opacity: 0.0; visibility:hidden}";
            $doc->body->getAppendContent()->add($style);
            igk_environment()->set("VueJS/Style", $style);
        }
    }
    public static function BuildMethods(& $method, & $data, $sep=''){
        $defkey = [];
        foreach (OptionBuilder::METHODS as $m) {
            $ms = igk_getv($data, $m);
            if ($ms && !isset($defkey[$m])){
                if (is_string($ms)) {                
                    $method .= $sep."{$m}:{$ms}"; 
                } else if (is_array($ms)){
                    $ms = JSExpression::Stringify((object)$ms);
                    $method .= $sep."{$m}:{$ms}"; 
                } else if ($ms instanceof JSExpression){
                    $method .= $sep."{$m}:".$ms->getValue();
                } else {
                    igk_wln_e("bind .... not allowed", $m, $ms);
                }
                $sep =",";
                $defkey[$m] = 1;
            }
            unset($data[$m]);
        }
        foreach(array_keys($data) as $k){
            $v = $data[$k];
            if (($v instanceof JSExpressionMethod) && in_array($v->name, OptionBuilder::METHODS) && (!isset($defkey[$ms])) ){
                $method .= $sep.$v->getValue();
                $sep =","; 
                unset($data[$k]);
                $defkey[$k] = 1;
            }
        }
    }
}