<?php
namespace igk\JS\VueJS;

use igk\JS\VueJS\IO\OptionBuilder;

class PolyfillV2 extends Polyfill{
	protected $version = "2";
    public function BuildApp() {
	}

    public function installCDN(){
		$js = "";
		if (igk_environment()->is("DEV")){
			$js = "https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js";
		}else {
			$js = "https://cdn.jsdelivr.net/npm/vue@2";
		}
		return $js;
    }
	public function bindData($node, $opt=null){
		$method="";
		$data = $node->data;
		$is_web = $opt && ($opt->Context == "html");
		foreach(OptionBuilder::METHODS as $m){
			$ms = igk_getv($data, $m);
			if (is_string($ms)){
				unset($data[$m]);
				$method .= "def.{$m} = {$ms}; ";
			}
		}
		$id = $node["id"];
		$js_data = json_encode($data);
		$src = "if (igk){igk.ready(function(){var def = {$js_data}; {$method}var app = new Vue(def); " .
			"setTimeout(function(){\$igk('#{$id}').rmClass('vuejs-init-hide');},200); });}";

		$src = implode(explode("\n", $src));

		if (!igk_is_ajx_demand()) {
            if ($src && ($doc = $opt->Document)) {
                $node->setClass("vuejs-init-hide");
                if (!($style = igk_environment()->get("VueJS/Style"))) {
                    $style = igk_createnode("style");
                    $style->Content = ".vuejs-app{ opacity:1.0; transition: .5s opacity;} .vuejs-app.vuejs-init-hide{ opacity: 0.0; visibility:hidden}";
                    $doc->body->getAppendContent()->add($style);
                    igk_environment()->set("VueJS/Style", $style);
                }
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