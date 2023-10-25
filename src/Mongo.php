<?php

class Mongo
{

    /**
     * Mongodb connection instance
     */
    public $connection = null;
    /**
     * Mongodb url
     */
    public $url = '';
    /**
     * Connection user name
     */
    public $user = "admin";
    /**
     * Connection userpassword
     */
    public $password = 'neF8ttcPmQ3QJFpj3qqq';



    public function __construct($username = null, $password = null)
    {
        $this->url = "mongodb://$this->user:$this->password@adamkyc-db.deltaplata.com:27017/sanction";
    }

    /**
     * start connection //true when successfully connected
     */
    public function start()
    {
        $this->connection = new Mongo($this->url);

        $databases = $this->connection;
        if ($databases) {
            return true;
        }
        return false;
    }
    /**
     * close mongo connection
     */
    public function close()
    {
        return $this->connection->close();
    }
}