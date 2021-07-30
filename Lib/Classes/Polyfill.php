<?php

namespace igk\JS\VueJS;

use igk\JS\VueJS\IO\JSAppDefinition;
use igk\JS\VueJS\IO\JSAppScriptBuilder;

/**
 * create a polyfill builder
 * @package igk\JS\VueJS
 */
abstract class Polyfill
{
    const VERSION_2 = 2;
    const VERSION_3 = 3;

    protected $version;
    protected static $sm_cdn;
    public function getVersion()
    {
        return $this->version;
    }
    protected function __construct()
    {
    }

    public function installCDN(){
        $key = igk_environment()->is("OPS") ? "ops" : "dev";
        $list = [static::$sm_cdn[$key]["vuejs"]];
        if (Utils::Module()->Configs->VueRouter){
            $list[] = static::$sm_cdn[$key]["vue-router"];
        }
        if (Utils::Module()->Configs->VueEx){
            $list[] = static::$sm_cdn[$key]["vue-ex"];
        }  
        return $list;
    }
    /**
     * create a polyfill vue js Helper
     * @param int $version 
     * @return PolyfillV3|PolyfillV2 
     */
    public static function Create($version = self::VERSION_2)
    {
        $cl = __NAMESPACE__."\\PolyfillV".$version;
        if (class_exists($cl)){
            return new $cl();
        }
        if ($version == self::VERSION_3) {
            return new PolyfillV3();
        }
        return new PolyfillV2();
    }

    protected abstract function getRouteDef($routes, $app);
    protected abstract function getRouteScriptDef($script, $app);
    /**
     * 
     * @param mixed $app 
     * @param mixed|null $option 
     * @return JSAppScriptBuilder 
     */
    protected function bindScriptBuilder($app, $option=null){
        $data = $app->data; 
        $use_router = $app->use_router;
        $builder = new JSAppScriptBuilder();
        $objectDefintion = (object)[ 
            "initApp"=>"",
            "appScript"=>""
        ];
        if ($use_router && ($routes = igk_getv($data, "routes"))){
            $builder->route($this->getRouteDef($routes, $app));
            if ($v_s = igk_getv($data, "routerScript")){               
                $builder->routerScript($this->getRouteScriptDef($v_s, $app));
            }
        }
        if($def = igk_getv($data, "library")){
            $rdef = [];
            foreach($def as $i){              
                $i->BuildDef($this, $objectDefintion);
            }
         
           $builder->bindDefinitions($objectDefintion);
          
        }
         
        return $builder;
    }
    
}
