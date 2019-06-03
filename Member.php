<?php

class Member
{
    protected $id;
    protected $user;
    protected $pass;
    
    public function __construct($i, $u, $p)
    {
        $this->id = $i;
        $this->user = $u;
        $this->pass = $p;
    }
}

