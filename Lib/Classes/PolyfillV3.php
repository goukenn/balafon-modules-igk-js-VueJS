<?php
namespace igk\JS\VueJS;

use igk\JS\VueJS\IO\JSExpression;
use igk\JS\VueJS\IO\OptionBuilder;
use IGKException;

class PolyfillV3 extends Polyfill{
    const OPS_URI = "https://unpkg.com/browse/vue@3.1.4/dist/vue.global.prod.js"; 
    const DEV_PROTOTYPE = "https://unpkg.com/vue@3.1.4/dist/vue.global.js"; 
    // const DEV_PROTOTYPE = 'https://unpkg.com/vue@next';
    protected $version = "3";
    
    public function BuildApp() {
		
	}

    public function installCDN(){
        $js = "";
        if (igk_environment()->is("DEV")){
            $js = self::DEV_PROTOTYPE;
        }else {
            $js = self::OPS_URI; // "https://cdn.jsdelivr.net/npm/vue@2";
        }
        return $js;
    }
    protected function getComonentDefinition($c){
        $g[] = "{";
        $ch = "";
        foreach(["template"=>0, "props"=>"json"] as  $k=>$v){
            if (!empty($c->$k)){
                $m= $ch."$k:";
                switch($v){
                    case "json":
                        $m.= json_encode($c->$k);
                    break;
                    default:
                        $m .= "'".$c->$k."'";
                }
                $g[] = $m;
                $ch = ",";
            } 
        }
        $g[] = "}"; 
        return implode("", $g);
    }
    /**
     * 
     * @param mixed $app 
     * @param mixed|null $options 
     * @return void 
     * @throws IGKException document not found
     */
    public function bindData($app, $options=null){
        $data = $app->data; 

        $src = "";
        $js_data = $method= "";
        $id = $app["id"];
        
        $js_data = json_encode(igk_getv($data, "data") ?? (object)[]);
        $js_data.= ";";
        $init_app = "";
        unset ($data["data"]); 
        foreach(OptionBuilder::METHODS as $m){
            $ms = igk_getv($data, $m);
            if (is_string($ms)){
                unset($data[$m]);
                $method .= ", {$m}:{$ms} ";
            }
        } 
        
        $const = "";

        
 

        if ($app->components){
            
            foreach($app->components as $k=>$c){
                $def = $this->getComonentDefinition($c);
                $ns = igk_ns_name($k).'Component';
                $const .= "const ".$ns." = $def;\n";
                $init_app .= ";\napp.component('{$k}', $ns);";
            }
        }
        if ($routes = igk_getv($data, "routes")){
            $const .= "const routes = ". (is_string($routes) ? $routes : JSExpression::stringify((object)$routes )).";";
            $const .= "const router = new VueRouter({routes});";
            $method .= ", router"; 
        }

        if (!empty($const)){
            $const = "(function(){ {$const} \nvar app = ";
            $init_app = $init_app.";\nreturn app; })()" ; 
        }
        $src = "if (igk){igk.ready(function(){ ".
            "{$const}".
            "Vue.createApp({data(){ return {$js_data}}{$method}}){$init_app}.mount('#{$id}');" .
            "setTimeout(function(){\$igk('#{$id}').rmClass('vuejs-init-hide');},200);".
       "\n});}"; 
 

//        $src = <<<EOF
//        if (igk){igk.ready(function(){ 
//        const { createApp, h } = Vue;

//        const NotFoundComponent = { template: '<p>Page not found</p>' }
//        const HomeComponent = { template: '<p>Home page</p>' }
//        const AboutComponent = { template: '<p>About page</p>' }
       
//        const routes = {
//          '/testapi/test_vue_js_router': HomeComponent,
//          '/testapi/test_vue_js_router/about': AboutComponent
//        }
//        console.debug(window.location.pathname);
//        const SimpleRouter = {
//          data: () => ({
//            currentRoute: window.location.pathname
//          }),
       
//          computed: {
//            CurrentComponent() {
//              return routes[this.currentRoute] || NotFoundComponent
//            }
//          },
       
//         //  render() {
//         //    return h(this.CurrentComponent)
//         //  }
//        }
       
//        createApp(SimpleRouter).mount('#{$id}');
//     });
// }
// EOF;

        if (igk_is_ajx_demand()){
            $sc = igk_createnode("script")->setContent($src); 
            igk_html_render_append_item($options, $sc);
        }else {
            if ($doc = $options->Document){
                $doc->body->addScriptContent("vuejs_" . $id, $src);
            }
        }

    }
}