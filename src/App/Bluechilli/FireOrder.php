<?php

namespace App\Bluechilli;

class FireOrder
{
    var $fire;
    var $a;
    var $e;
    var $use_door;
    var $door_code;

    var $pending;

    // load the contents of this model for persistence
    public function Save()
    {
        $storage = new \Flatbase\Storage\Filesystem('../src/db');
        $flatbase = new \Flatbase\Flatbase($storage);

        $flatbase->insert()->in('orders')
            ->set([ 'fire' => $this->fire,
                    'a' => $this->a,
                    'e' => $this->e,
                    'use_door' => $this->use_door,
                    'door_code' => $this->door_code,
                    'pending' => $this->pending])
            ->execute();
    }

    // Save the contents of this model for persistence
    public function Load()
    {
        $storage = new \Flatbase\Storage\Filesystem('../src/db');
        $flatbase = new \Flatbase\Flatbase($storage);

        $orders = $flatbase->read()->in('orders')->first();
        $flatbase->delete()->in('orders')->execute();

        $this->fire = $orders['fire'];
        $this->a = $orders['a'];
        $this->e = $orders['e'];
        $this->use_door = $orders['use_door'];
        $this->door_code = $orders['door_code'];
        $this->pending = $orders['pending'];
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