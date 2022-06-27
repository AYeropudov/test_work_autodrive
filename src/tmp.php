<?php

class Flag
{
    public $flag;

    function __construct($flag = true) {
        $this->flag = $flag;
    }
}
$o = new Flag();
$p = new Flag();
$d = new Flag();
$z = [$o, $p];
$x = in_array($d, $z);