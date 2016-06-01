<?php

namespace App\Controller;

class Slack
{
    private $_db;

    public function __construct(\App\Bluechilli\FireOrder $FireOrder)
    {
        $this->FireOrder = $FireOrder;
        $this->_commandList = array('add', 'list','anyone','aim','fire');

        $storage = new \Flatbase\Storage\Filesystem('../src/db');
        $this->_db = new \Flatbase\Flatbase($storage);
    }

    public function Webhook($request, $response, $args)
    {

        // TODO: abstract slack return notification
        // TODO: provide switch on commands. 
        // turret shoot name
        // turret add name az el git door
        // turret aim name

        // Parse data
        $req = $request->getParsedBody();
        
        // check token
        if($req['token'] != 'Bwmk30e3mBXmuEKCFttgYRAe')
        {
           $payload = ["text" => "Invalid token"]; 
        }

        else if($req['text'] == 'help')
        {
            $payload = ["text" => "Enter /shoot followed by the username of your target. You'll need to do this in #turret"]; 
        }

        else if(substr($req['text'], 0, 3) == 'add')
        {
            // /shoot add username azimuth elevation (github)
            list($cmd, $username, $a, $e, $github) = explode (" ", $req['text']);

            if(in_array($username, $this->_commandList))
            {
                $payload = ["text" => $username . " is a protected command word and can't be used "];
            }
            else
            {

                $this->_db->delete()->in('users')->where('slack', '==', $username)->execute();
                $this->_db->insert()->in('users')
                    ->set(['slack' => $username, 'github' => $github, 'a' => $a, 'e' => $e])
                    ->execute();

                $payload = ["text" => $username . " added at azimuth: ". $a. ", elevation: ". $e];
            }
        }

        else if(substr($req['text'], 0, 3) == 'list')
        {
          
            $users = $this->_db->read()->in('users');

            $payload = ["text" => implode($users)];
            

        }

        else if(substr($req['text'], 0, 3) == 'aim')
        {
            list($cmd, $a, $e) = explode (" ", $req['text']);

            $this->FireOrder->fire = false;
            $this->FireOrder->a = $a;
            $this->FireOrder->e = $e;
            $this->FireOrder->use_door = false;
            $this->FireOrder->door_code = rand(0, 999);  // TODO
            $this->FireOrder->pending = true;  
            $this->FireOrder->message = $req['user_name']." shot someone!";

            // Save data
            $this->FireOrder->save();
            $payload = ["text"=> "Aimed at a:".$a." e:".$e];
        }

        else if(substr($req['text'], 0, 4) == 'fire')
        {
            list($cmd, $a, $e) = explode (" ", $req['text']);

            $this->FireOrder->fire = true;
            $this->FireOrder->a = $a;
            $this->FireOrder->e = $e;
            $this->FireOrder->use_door = false;
            $this->FireOrder->door_code = rand(0, 999);  // TODO
            $this->FireOrder->pending = true;  
            $this->FireOrder->message = $req['user_name']." shot someone!";

            // Save data
            $this->FireOrder->save();
            $payload = ["text"=> "Fired at a:".$a." e:".$e];
        }
        
        else if($req['channel_name'] != 'turret')
        {
            $payload = ["text" => "No ninja shooting for you! Turret Bot only works in #turret :ninja:"];
            
            $target = strtolower(str_replace('@', '', strtok(trim($req['user_name']), " ")));
            
            if($this->_db->read()->in('users')->where('slack', '=', $target)->count() > 0)
                $this->loadFireOrder($target);
        }
         
        else
        {
            $target = strtolower(str_replace('@', '', strtok(trim($req['text']), " ")));

            if($target == "anyone")
            {
                $count = $this->_db->read()->in('users')->count();
                $id = rand(0, $count);

                $output = $this->_db->read()->in('users')->where('id', '==', $id)->first();
                $this->loadFireOrder($output['slack']);

                $payload = ["response_type"=> "in_channel", "text"=> $req['user_name']." shot someone at random!"];

            }

            else if($this->_db->read()->in('users')->where('slack', '=', $target)->count() == 0)
            {
                $payload = ["response_type"=> "in_channel", "text"=> "Misfire! " . $req['user_name']." tried to shoot " .$target.", but they got out of the way! :ninja:"];
            }

            else
            {

                $this->loadFireOrder($target);

                // TOOD remove this and have deferred general message instead
                $payload = ["response_type"=> "in_channel", "text"=> $req['user_name']." shot someone!"];
            }
        }

        // Prepare Headers
        $response = $response->withHeader('Content-type', 'application/json');
        $response = $response->withStatus(200);
        
        // Payload
        return $response->write(json_encode($payload));
    }

    public function loadFireOrder($target)
    {
        // look up target in database
        $coordinates = $this->_db->read()->in('users')
            ->where('slack', '=', $target)
            ->first();

        $this->FireOrder->fire = true;
        $this->FireOrder->a = $coordinates['a'];
        $this->FireOrder->e = $coordinates['e'];
        $this->FireOrder->use_door = false;
        $this->FireOrder->door_code = rand(0, 999);  // TODO
        $this->FireOrder->pending = true;  // TODO
        $this->FireOrder->message = $req['user_name']." shot " .$target ."!";
        //$this->FireOrder->message = $req['user_name']." shot someone!";

        // Save data
        $this->FireOrder->save();
    }
}