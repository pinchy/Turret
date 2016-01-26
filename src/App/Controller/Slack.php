<?php

namespace App\Controller;

class Slack
{
    private $_db;

    public function __construct(\App\Bluechilli\FireOrder $FireOrder)
    {
        $this->FireOrder = $FireOrder;

        $storage = new \Flatbase\Storage\Filesystem('../src/db');
        $this->_db = new \Flatbase\Flatbase($storage);
    }

    public function Webhook($request, $response, $args)
    {

        // TODO: abstract slack return notification

        // Parse data
        $req = $request->getParsedBody();
        
        // check token
        if($req['token'] != 'TOKEN')
        {
           // exit();
        }
        


        if($req['text'] == 'help')
        {
            $payload = ["text" => "Enter /shoot followed by the username of your target. You'll need to do this in #general"]; 
        }

        else if(substr($req['text'], 0, 3) == 'add')
        {
            // /shoot add username azimuth elevation (github)
            list($cmd, $username, $a, $e, $github) = explode (" ", $req['text']);

            $this->_db->delete()->in('users')->where('slack', '==', $username)->execute();
            $this->_db->insert()->in('users')
                ->set(['slack' => $username, 'github' => $github, 'a' => $a, 'e' => $e])
                ->execute();

            $payload = ["text" => $username . " added at azimuth: ". $a. ", elevation: ". $e];

        }

        else if($req['channel_name'] != 'general')
        {
            $payload = ["text" => "No ninja shooting for you! Turret Bot only works in #general :ninja:"];
        } 
        else
        {
            $target = strtolower(str_replace('@', '', strtok(trim($req['text']), " ")));

            if($this->_db->read()->in('users')->where('slack', '=', $target)->count() == 0)
            {
                $payload = ["response_type"=> "in_channel", "text"=> "Misfire! " . $req['user_name']." tried to shoot " .$target.", but they got out of the way! :ninja:"];
            }
            else
            {
                // look up target in database
                $coordinates = $this->_db->read()->in('users')
                    ->where('slack', '=', $target)
                    ->first();

                $this->FireOrder->fire = false;
                $this->FireOrder->a = $coordinates['a'];
                $this->FireOrder->e = $coordinates['e'];
                $this->FireOrder->use_door = false;
                $this->FireOrder->door_code = rand(0, 999);
                $this->FireOrder->pending = true;
                $this->FireOrder->message = $req['user_name']." shot " .$target ."!";

                // Save data
                $this->FireOrder->save();

                // TOOD remove this and have deferred general message instead
                $payload = ["response_type"=> "in_channel", "text"=> $req['user_name']." shot " .$target ."!"];

            }
        }

        // Prepare Headers
        $response = $response->withHeader('Content-type', 'application/json');
        $response = $response->withStatus(200);
        
        // Payload
        return $response->write(json_encode($payload));
    }
}