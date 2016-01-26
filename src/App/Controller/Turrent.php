<?php

namespace App\Controller;

class Turrent
{
    public function __construct(\App\Bluechilli\Turretstatus $turrentstatus)
    {
        $this->turrentstatus = $turrentstatus;
    }

    public function Check($request, $response, $args)
    {

        $this->turrentstatus->fire = false;
        $this->turrentstatus->a = 4004;
        $this->turrentstatus->e = 2002;
        $this->turrentstatus->use_door = false;
        $this->turrentstatus->door_code = rand(0, 999);

        // Prepare Headers

        $response = $response->withHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response = $response->withHeader('Content-type', 'text/plain');
        // Payload
        $response->write($this->turrentstatus->generateString());
    }


}