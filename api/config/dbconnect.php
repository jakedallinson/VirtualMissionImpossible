<?php

class Database
{
    private $conn = null;

    public function __construct()
    {
        $host       = '172.26.2.25';
        $username   = '';
        $password   = '';
        $dbname     = 'vmi';
        $port       = '3306';

        try {
            $this->connection = mysqli_connect($host, $username, $password, $dbname, $port);
        } catch (mysqli_sql_exception $e) {
            exit($e);
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}

?>