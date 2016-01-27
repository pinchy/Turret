<?php

namespace App\Controller;

class Root
{

    var $settings;

    public function __construct($appConfig)
    {
        $this->appConfig = $appConfig;
    }

    public function Root($request, $response, $args)
    {
        return $response->write("Hello World");
    }


}