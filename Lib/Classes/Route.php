<?php

namespace igk\JS\VueJS;

class Route extends JSData
{
    /**
     * define an alias
     * @var string|stringlist
     */
    var $alias; 

    var $name;
    /**
     * path to js data 
     * @var mixed
     */
    var $path;
    /**
     * component object
     * @var mixed
     */
    var $component;


    /**
     * components list . for multiview
     * @var 
     */
    var $components;
    /**
     * children of subroute
     * @var mixed
     */
    var $children;

    /**
     * object de propriété [x=>1]
     * @var mixed
     */
    var $query;

    /**
     * hash value : #something
     * @var mixed
     */
    var $hash;

    /**
     * 
     * @var bool replace the value . so the history don't change
     */
    var bool $replace;

    /**
     * redirection handle
     * @var igk\JS\VueJS\redirect
     */
    var $redirect;

    /**
     * 
     * @var bool allow passwing props
     */
    var $props;

    /**
     * using meta
     * @var mixed
     */
    var $meta;

    /**
     * method to register before route enter
     * @var mixed
     */
    var $beforeEnter;
}
