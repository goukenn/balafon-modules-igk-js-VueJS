<?php

namespace igk\JS\VueJS;

use igk\JS\VueJS\IO\JSAppScriptBuilder;
use igk\JS\VueJS\IO\JSExpression;
use igk\JS\VueJS\IO\OptionBuilder;
use IGK\System\IO\StringBuilder;
use IGK\System\IO\StringLogBuilder;
use IGKException;

class PolyfillV3 extends Polyfill
{
    const OPS_URI = "https://unpkg.com/browse/vue@3.1.4/dist/vue.global.prod.js";
    const DEV_PROTOTYPE = "https://unpkg.com/vue@3.1.4/dist/vue.global.js";

    protected $version = "3";
    protected static $sm_cdn = [
        "dev" => [
            "vuejs" => 'https://unpkg.com/vue@3.1.5/dist/vue.global.js',
            "vue-router" => 'https://unpkg.com/vue-router@4.0.5/dist/vue-router.global.js',
            "vue-ex" => "https://unpkg.com/vuex@4.0.2/dist/vuex.global.js"
        ],
        "ops" => [
            "vuejs" => self::OPS_URI,
            "vue-router" => 'https://unpkg.com/vue-router@4.0.5/dist/vue-router.global.prod.js',
            "vue-ex" => 'https://unpkg.com/vuex@4.0.2/dist/vuex.global.prod.js'
        ]
    ];

    public function BuildApp()
    {
    }


    protected function getComponentDefinition($c)
    {
        if ($c instanceof JSExpression){
            return  $c->getValue();
        }
        $defs = $c->getProperties();
        $rt = [];
        foreach ($defs as  $k) {
            if (!empty($c->$k)) {
                // if ($k === "methods"){
                //     igk_wln_e($c->$k);
                // }
                $rt[$k] = $c->$k;
                // $m .= JSExpression::Stringify($c->$k);
                // switch ($v) {
                //     case "json":
                //         $m .= json_encode($c->$k);
                //         break;
                //     default:
                //         $m .= "'" . $c->$k . "'";
                // }
                // $g[] = $m;
                // $ch = ",";
            }
        }
       // $g[] = "}";
        //igk_wln_e($defs, $g, "\n\n", JSExpression::Stringify((object)$rt));
        // return implode("", $g);
        return JSExpression::Stringify((object)$rt);
    }
    protected function getRouteDef($routes, $app)
    {
        $history = 'createWebHistory(';
        switch ($app->historyType) {
            case "hash":
                $history = 'createWebHashHistory(';
                break;
            case "memory":
                $history = 'createMemoryHistory(';
                break;
        }
        $history .= ')';
        $info =  (object)[
            "polyfill" => $this,
            "shortNotation" => true
        ];

        $s = is_string($routes) ? $routes : JSExpression::Stringify($routes, $info);
        $opts = "";
        if ($c = igk_getv($app->data, "routerOptions")) {
            $opts = "," . (is_string($c) ? $c : "..." . JSExpression::Stringify((object)$c, $info));
        }
        // $opts=", scrollBehavior(to, from, o){ return  o || {top: 0, el:'#router_transition'};  }\n";

        $const = "";
        $const .= "\nconst routes = " . $s . ";\n";
        $const .= "const router = VueRouter.createRouter({routes, history: VueRouter.{$history}{$opts}});\n";

        return $const;
    }
    protected function getRouteScriptDef($script, $app)
    {
        $s = "(function(router){";
        if (is_string($script)) {
            $s .=  $script;
        }
        if (is_array($script)) {
            foreach ($script as $k => $m) {
                $tk = is_numeric($k) ? "" : "router.$k";
                if ($m instanceof JSExpression) {
                    // $s .= "\n{$tk} = (".$m->getValue().");";
                } else {
                    $s .= "\n{$tk}" . $m;
                }
            }
        }
        $s .= "\n}).apply(router, [router]);\n";
        return $s;
    }

    protected function getVueExDef($app)
    {
    }

    /**
     * 
     * @param mixed $app 
     * @param mixed|null $options 
     * @return void 
     * @throws IGKException document not found
     */
    public function bindData($app, $options = null)
    {
        $src = "";
        $debug_builder = new StringLogBuilder($src, function () {
            return igk_environment()->is("DEV");
        });
        $sb = new StringBuilder($src);

        $builder = $this->bindScriptBuilder($app, $options);
        $data = $app->data;
        $id = $app["id"];
        $js_data = $method = $appjs = $const = "";

        // data special treatment
        $js_data = json_encode(igk_getv($data, "data") ?? (object)[]);
        $js_data .= ";";
        $init_app = "";
        $sep = "";
        unset($data["data"]);
        if ($js_data && ($js_data != '{};')) {
            $js_data =  "data(){ return {$js_data}}";
            $sep = ",";
        } else {
            $js_data = "";
        }
        Utils::BuildMethods($method, $data, $sep);
        if (!empty($method)){
            $js_data .= $method;
        }


        $components = null;
        if ($app->components) {
            foreach ($app->components as $k => $c) {
                if ($components === null) {
                    $components = (object)["def" => "", "app" => ""];
                }
                $def = $this->getComponentDefinition($c);
                $ns = igk_ns_name($k) . 'Component';
                $components->def .= "const " . $ns . " = $def;";
                $components->app .= "app.component('{$k}', $ns);";
            }
        } 

        if ($app->use_router && ($route = $builder->route)) {
            $appjs .= "app.use(router);";
            $const .= $route;
            // + | ROUTER SCRIPT
            if ($rscript = $builder->routerScript) {
                $const .= $rscript;

                igk_wln_e("router script : ".$const);
            }
        }
        if ($js = igk_getv($data, "appScripts")) {
            if ($js instanceof JSExpression) {
                $js = $js->getValue();
            }
            $appjs .= $js;
        }

        $debug_builder->appendLine("/* app options */");
        $sb->appendLine("const _def = {{$js_data}};");
        if (!empty($const)){
            $sb->appendLine($const);
        }
        if ($components) {
            $debug_builder->appendLine("/* components */");
            $sb->appendLine($components->def);
        }

        if ($cdef = $builder->bindDefinitions) {
            if (!empty($cdef->initApp))
                $init_app .= $cdef->initApp;
            $appjs .= $cdef->appScript;
        }
        if (!empty($init_app)){
            $sb->appendLine($init_app);
        }

        $sb->appendLine("const app = Vue.createApp(_def);");
        if ($components) {
            $debug_builder->appendLine("/* use components */");
            $sb->appendLine($components->app);
        }
        // init application 
        if (!empty($appjs)) {
            $sb->appendLine("(function(app){\n" . $appjs . "\n}).apply(app, [app]);");
        }

        // mount to application
        $sb->appendLine("app.mount(\"#{$id}\");"); 
         


        $src = "if (igk){igk.ready(function(){\n" .$src; 
        $sb->appendLine("setTimeout(function(){\$igk('#{$id}').rmClass('vuejs-init-hide');},200);");
        $sb->appendLine("});}");
 
        if (igk_environment()->is("OPS")){
            $src = igk_js_minify($src, 1);
        }

        if (igk_is_ajx_demand()) {
            $sc = igk_createnode("script")->setContent($src);
            igk_html_render_append_item($options, $sc);
        } else {
            if ($doc = $options->Document) {
                Utils::ConfigStartApp($app, $doc);
                $doc->body->addScriptContent("vuejs_" . $id, $src);
            }
        }
    }
}
