<?php

namespace App\Controller;

class Turret
{
    public function __construct(\App\Bluechilli\TurretStatus $turrentStatus)
    {
        $this->turrentStatus = $turrentStatus;
    }

    public function Check($request, $response, $args)
    {

        $this->turrentStatus->fire = false;
        $this->turrentStatus->a = 4004;
        $this->turrentStatus->e = 2002;
        $this->turrentStatus->use_door = false;
        $this->turrentStatus->door_code = rand(0, 999);

        // Prepare Headers
        $response = $response->withHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response = $response->withHeader('Content-type', 'text/plain');
        
        // Payload
        $response->write($this->turrentStatus->toString());
    }


}