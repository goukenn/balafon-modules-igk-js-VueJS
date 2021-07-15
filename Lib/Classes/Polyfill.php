<?php

namespace igk\JS\VueJS;

/**
 * create a polyfill builder
 * @package igk\JS\VueJS
 */
abstract class Polyfill
{
    const VERSION_2 = 2;
    const VERSION_3 = 3;

    protected $version;
    public function getVersion()
    {
        return $this->version;
    }
    protected function __construct()
    {
    }
    /**
     * create a polyfill vue js Helper
     * @param int $version 
     * @return PolyfillV3|PolyfillV2 
     */
    public static function Create($version = self::VERSION_2)
    {
        if ($version == self::VERSION_3) {
            return new PolyfillV3();
        }
        return new PolyfillV2();
    }
    /**
     * build application
     * @return mixed 
     */
    abstract function BuildApp();
    /**
     * return installed CDN Script
     * @return mixed 
     */
    abstract function installCDN();
}
