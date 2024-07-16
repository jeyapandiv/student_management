<?php
require_once 'config.php';

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct()
    {
        //set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        //creating new PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log($this->error); // error log
            throw new Exception($this->error);
        }
    }

    //prepare sql query
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    //binding value to parameter in prepared statement
    public function bind($param, $value, $type = null)
    {

        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;    //Integer type
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;   //boolean type
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;   //null type
                    break;
                default:
                    $type = PDO::PARAM_STR;    //string type
            }
        }
        //binding value to parameter
        $this->stmt->bindValue($param, $value, $type);
    }

    //executing prepared statement
    public function execute()
    {
        return $this->stmt->execute();
    }

    //set of results - associative array
    public function resultSet()
    {
        $this->execute();  //statement execution
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);  //fetching all results
    }

    //single record - associative array
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);  //fetching one result
    }

    //number of rows
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    //last inserted ID
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

}








