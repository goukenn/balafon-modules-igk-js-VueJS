<?php
// @author: C.A.D BONDJE DOUE
// @file: %modules%/igk/JS/VueJS/global.php
// @desc: vue.js importer utility
// @date: 20210708 19:00:50

// + module entry file 

use igk\JS\VueJS\Html\App;
use igk\JS\VueJS\IO\OptionBuilder;

/**
 * 
 * @param mixed $id 
 * @param Array|OptionBuilder|null $data 
 * @return App 
 * @throws IGKException 
 */
function igk_html_node_vuejs_app($id, $data=null){
    $n = new igk\JS\VueJS\Html\App();
    $n->setId($id);
    $rf = igk_get_module("igk/js/VueJS");
    if ($data ===null){
        $data = [];
    } else if ($data instanceof OptionBuilder){
        $data = $data->build();
    }
    if (is_array($data)){
        $data["el"]= "#".$id;
    }
    $n->data = $data; 

    return $n;
}

function igk_html_node_vuejs_component( $tagname, array $args = null){
    $n = new igk\JS\VueJS\Html\Component($tagname, $args);   
    return $n;
} 