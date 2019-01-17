<?php
namespace App\utils;
class DbConnect
{

    public $db = null;
    public function __construct()
    {
        $config = parse_ini_file("config.ini");

        $this->db = new \mysqli($config['server_name'] , $config['user'] , $config['pass'] , $config['db_name']);
        // $this->db = new mysqli("localhost", "kkroot", "veronica007KK!@#$%", "logon");
        if($this->db->connect_error)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function getConnect()
    {
        return $this->db;
    }

    public function dbFinish()
    {
        $this->db->close();
    }


}
// new DbConnect();
?>
