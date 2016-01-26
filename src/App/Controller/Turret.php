<?php

namespace App\Controller;

class Turret
{
    public function __construct(\App\Bluechilli\FireOrder $FireOrder)
    {
        $this->FireOrder = $FireOrder;
    }

    // Turret poll to check for fire order
    public function Check($request, $response, $args)
    {
        $this->FireOrder->save();
        $this->FireOrder->load();

        // Prepare Headers
        $response = $response->withHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response = $response->withAddedHeader('Content-type', 'text/plain');
        
        // Payload
        return $response->write($this->FireOrder->toString());
    }

    // on completion of fireorder, turret executes this command
    public function Splash($request, $response, $args)
    {
        // grab the last person who did it and push to slack
    }
}