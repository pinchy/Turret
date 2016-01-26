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
        $this->FireOrder->save();
        $this->FireOrder->load();

        // Prepare Headers
        $response = $response->withHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response = $response->withHeader('Content-type', 'text/plain');
        
        // Payload
        $response->write($this->FireOrder->toString());
    }
}