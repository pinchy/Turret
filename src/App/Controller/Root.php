<?php

namespace App\Controller;

class Root
{
    public function Root($request, $response, $args)
    {
        $response->write("Hello World");
    }


}