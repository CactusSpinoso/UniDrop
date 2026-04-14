<?php
class Database
{
    private $hostname;
    private $user;
    private $password;
    private $dbName;
    private $conn;

    public function __construct()
    {
        require_once __DIR__ . "/../configs/config.php";

        $this->hostname = Config::$hostname;
        $this->user = Config::$username;
        $this->password = Config::$password;
        $this->dbName = Config::$dnname;

        $this->conn = new mysqli($this->hostname, $this->user, $this->password, $this->dbName);

        if ($this->conn->connect_error) {
            die("Errore di connessione: " . $this->conn->connect_error);
        }
    }

    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    public function escape($str)
    {
        return $this->conn->real_escape_string($str);
    }

    public function lastInsertId()
    {
        return $this->conn->insert_id;
    }
}
