<?php

namespace App\Controller;

class Turret
{
    public function __construct(\App\Bluechilli\FireOrder $FireOrder)
    {
        $this->FireOrder = $FireOrder;
    }

    public function Check($request, $response, $args)
    {

        $this->FireOrder->fire = false;
        $this->FireOrder->a = 4004;
        $this->FireOrder->e = 2002;
        $this->FireOrder->use_door = false;
        $this->FireOrder->door_code = rand(0, 999);
        $this->FireOrder->available = true;

        // Prepare Headers
        $response = $response->withHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response = $response->withHeader('Content-type', 'text/plain');
        
        // Payload
        $response->write($this->FireOrder->toString());
    }


}