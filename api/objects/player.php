<?php

class Player
{
    private $conn = null;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // public function makeRequest($method, $uri)
    // {
    //     $this->method = $method;
    // }

    // public function getMethod()
    // {
    //     return $this->method;
    // }
}

?>