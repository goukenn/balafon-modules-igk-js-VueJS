<?php
namespace igk\JS\VueJS;

use igk\JS\VueJS\IO\JSExpression;
use igk\JS\VueJS\IO\OptionBuilder;

class PolyfillV2 extends Polyfill{
	protected $version = "2";
	protected static $sm_cdn = [
		"dev"=>[
			"vuejs"=>'https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js',
			"vue-router"=>'https://unpkg.com/vue-router@3.5.2/dist/vue-router.js',
			"vue-ex"=>"https://unpkg.com/vuex@3.6.2/dist/vuex.js"
		],
		"ops"=>[
			"vuejs"=>'https://cdn.jsdelivr.net/npm/vue@2/dist/vue.min.js',
			"vue-router"=>'https://unpkg.com/vue-router@3.5.2/dist/vue-router.min.js',
			"vue-ex"=>"https://unpkg.com/vuex@3.6.2/dist/vuex.js"
		]
	];
    public function BuildApp() {
	}
	protected function getRouteScriptDef($script, $app){
		return '// no '.__METHOD__.' implement\n';
	}
	protected function getRouteDef($routes, $app){
		$m = "";
		if ($routes){
			// + | setup routes
			$m .= "const routes = ". (is_string($routes) ? $routes : json_encode($routes )).";";
			$m .= "const router = new VueRouter({routes:routes});\n";
			$m .= "def.router = router;\n"; 
		}
		return $m;
	}
  
	public function bindData($node, $opt=null){
		$builder = $this->bindScriptBuilder($node, $opt);
		$method="";
		$data = $node->data;
		$is_web = $opt && ($opt->Context == "html");		 
		foreach(OptionBuilder::METHODS as $m){
			$ms = igk_getv($data, $m);
			if (is_string($ms)){
				unset($data[$m]);
				$method .= "def.{$m} = {$ms};\n";
			}
		} 
		if ($route = $builder->route){
			$method .= $route;
			unset($data["route"]); 
		} 
		unset($data["library"]); 

		$id = $node["id"];
		$js_data = json_encode($data);
		$appjs = "";

		if ($js = igk_getv($data, "appScripts")){
            if ($js instanceof JSExpression){
                $js = $js->getValue();
            }
            $appjs .= $js;            
        }
		if ($cdef = $builder->bindDefinitions){
            if (!empty($cdef->initApp))
                $method .= "\n".$cdef->initApp."";            
            $appjs.= $cdef->appScript;
        }
	

		if (!empty($appjs)){
            $$appjs .=";\n(function(app){\n".$appjs."\n}).apply(app, [app])";
        }

		$src = "if (igk){igk.ready(function(){var def = {$js_data}; {$method}var app = new Vue(def); " .
			$appjs.
			 "setTimeout(function(){\$igk('#{$id}').rmClass('vuejs-init-hide');},200);".
			"});} "; 
		$src = implode("", explode("\n", $src)); 
		// if (igk_is_ajx_demand()){
		// 	igk_io_w2file("/Volumes/Data/temp/poly2.js", $src);
		// 	igk_wln_e($src);
		// }

		if (!igk_is_ajx_demand()) {
            if ($src && ($doc = $opt->Document)) {
				Utils::ConfigStartApp($node, $doc);              
                $doc->body->addScriptContent("vuejs_" . $id, $src);
            }
        } else {             
            if ($is_web && $src) {               
                $sc = igk_createnode("script")->setContent($src); 
                igk_html_render_append_item($opt, $sc);
            } 
        }
        if ($is_web && $node->components){
            foreach($node->components as $c){
                igk_html_render_append_item($opt, $c); 
				// igk_wln_e("reg component");             
            }
        }
	}
}