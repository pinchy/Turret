<?php

namespace App\Bluechilli;

class Turretstatus
{

    var $fire;
    var $a;
    var $e;
    var $use_door;
    var $door_code;

    public function generateString()
    {
        $str = ($this->fire) ? '1' : '0';
        $str .= str_pad($this->a, 5, '0', STR_PAD_LEFT);
        $str .= str_pad($this->e, 5, '0', STR_PAD_LEFT);
        $str .= ($this->use_door) ? '1' : '0';
        $str .= str_pad($this->door_code, 3, '0', STR_PAD_LEFT);

        return $str;
    }


}