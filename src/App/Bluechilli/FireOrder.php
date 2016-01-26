<?php

namespace App\Bluechilli;

class FireOrder
{
    public $fire;
    public $a;
    public $e;
    public $use_door;
    public $door_code;
    public $pending;
    public $message;

    private $_db;

    public function __construct()
    {
        // https://packagist.org/packages/flatbase/flatbase
        $storage = new \Flatbase\Storage\Filesystem('../src/db');
        $this->_db = new \Flatbase\Flatbase($storage);
    }

    // load the contents of this model for persistence
    public function Save()
    {  
        $this->_db->insert()->in('orders')
            ->set([ 'fire' => $this->fire,
                    'a' => $this->a,
                    'e' => $this->e,
                    'use_door' => $this->use_door,
                    'door_code' => $this->door_code,
                    'pending' => $this->pending,
                    'message' => $this->message])
            ->execute();
    }

    // Save the contents of this model for persistence
    public function Load()
    {
        $orders = $this->_db->read()->in('orders')->first();
        $this->_db->delete()->in('orders')->execute();

        $this->fire = $orders['fire'];
        $this->a = $orders['a'];
        $this->e = $orders['e'];
        $this->use_door = $orders['use_door'];
        $this->door_code = $orders['door_code'];
        $this->pending = $orders['pending'];
        $this->message = $orders['message'];
    }

    // save who did it!
    public function log()
    {
        $this->_db->insert()->in('log')  
        ->set([ 'time' => time(),
                'message' => $this->message])
            ->execute();
    }

    public function toString()
    {
        if($this->pending)
        {
            $str = ($this->fire) ? '1' : '0';
            $str .= str_pad($this->a, 5, '0', STR_PAD_LEFT);
            $str .= str_pad($this->e, 5, '0', STR_PAD_LEFT);
            $str .= ($this->use_door) ? '1' : '0';
            $str .= str_pad($this->door_code, 3, '0', STR_PAD_LEFT);
            $str .= "\n";
        }
        else
        {
            $str = "No Command";
        }       
        return $str;  
    }


}