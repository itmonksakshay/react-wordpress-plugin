<?php

class DB
{

    /**
     * Mongodb connection instance
     */
    private $connection = null;
    /**
     * Mongodb url
     */
    private $url = '';
    /**
     * Connection user name
     */
    private $user = "admin";
    /**
     * Connection userpassword
     */
    private $password = 'neF8ttcPmQ3QJFpj3qqq';



    public function __construct($username = null, $password = null)
    {
        
        $this->url = "mongodb://$this->user:$this->password@adamkyc-db.deltaplata.com:27017/sanction";

    }

    /**
     * start connection //true when successfully connected
     */
    public function start()
    {
        if (class_exists('Mongo')) {
            $this->connection = new Mongo($this->url);
            $databases = $this->connection;
            if ($databases) {
                return true;
            }
        }
        return wp_send_json(
            array(
                'connection' => false,
                'message' => 'Can not connect to database',
            )
        );
    }
    /**
     * close mongo connection
     */
    public function close()
    {
        if (class_exists('Mongo')) {
            $this->connection = new Mongo($this->url);
            return $this->connection->close();

        }
        return wp_send_json(
            array(
                'status' => false,
                'message' => 'Connection not established',
            )
        );
    }
}