<?php

namespace App\Bluechilli;

use Interop\Container\ContainerInterface;

class Config implements ContainerInterface {

    private $_configStack = array();

    function __construct($directory) {
        $dir = new \DirectoryIterator($directory);

        foreach($dir as $fileInfo) {
            if($fileInfo->getExtension() == "php") {
                $this->_configStack[ strtolower($fileInfo->getBasename('.php')) ] = require($fileInfo->getPathname());
            }
        }
    }

    function __invoke() {
        return $this->_configStack;
    }

    function get($key) {
        return (isset($this->_configStack[ $key ])) ? $this->_configStack[ $key ] : null;
    }

    function has($key) {
        return (isset($this->_configStack[ $key ])) ? true : false;
    }

    public function __call($method, $args) {

        // If there are no parameters, then return the whole config definition
        if(empty($args[0])) {
            return $this->get($method);
        }

        // Supports only flat associative arrays
        $arg = $args[0];

        return (isset($this->_configStack[ $method ][ $arg ])) ? $this->_configStack[ $method ][ $arg ] : null;


    }

    public function settings() {
        return array("settings" => $this->_configStack);
    }


}