<?php

namespace App\Controller;

class Slack
{
    public function __construct(\App\Bluechilli\FireOrder $FireOrder)
    {
        $this->FireOrder = $FireOrder;
    }

    public function Webhook($request, $response, $args)
    {

        // Parse data

        // dummy data below
        
        $this->FireOrder->fire = false;
        $this->FireOrder->a = 4004;
        $this->FireOrder->e = 2002;
        $this->FireOrder->use_door = false;
        $this->FireOrder->door_code = rand(0, 999);
        $this->FireOrder->pending = true;


        // Save data
        $this->FireOrder->save();

        $payload = json_encode(["response_type"=> "in_channel", "text"=>"A shot B!"]);
        

        // Prepare Headers
        $response = $response->withHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response = $response->withHeader('Content-type', 'application/json');
        
        // Payload
        $response->write($payload);
    }
}