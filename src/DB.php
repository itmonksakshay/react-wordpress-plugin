<?php

use MongoDB\Client;

class DB
{

    /**
     * Mongodb connection instance
     */
    private $connection = null;
    private $username = null;
    private $password = null;
    private $host = null;
    private $database = null;

    public function __construct()
    {

    }

    /**
     * start connection //true when successfully connected
     */
    public function start($options = [])
    {
        if (isset($options['username']) && $options['username'] === '') {
            return wp_send_json(
                array(
                    'connection' => false,
                    'message' => 'Username is required',
                )
            );
        }

        if (isset($options['password']) && $options['password'] === '') {
            return wp_send_json(
                array(
                    'connection' => false,
                    'message' => 'Password is required',
                )
            );
        }

        if (isset($options['host']) && $options['host'] === '') {
            return wp_send_json(
                array(
                    'connection' => false,
                    'message' => 'Host is required',
                )
            );
        }

        $this->username = $options['username'];
        $this->password = $options['password'];
        $this->host = $options['host'];
        $this->database = $options['database'];


        //admin
        //neF8ttcPmQ3QJFpj3qqq
        //adamkyc-db.deltaplata.com:27017
        //sanction
        // $this->url = "mongodb://$this->user:$this->password@$this->host/sanction";
        $uri = "mongodb://$this->username:$this->password@$this->host/$this->database";

        // $uri = "mongodb://$this->username:$this->password@$this->host/sanction";
        // Create a new client and connect to the server

        try {
            // Send a ping to confirm a successful connection
            $this->connection = new MongoDB\Client($uri);
            $this->connection->selectDatabase('admin')->command(['ping' => 1]);


            return wp_send_json(
                array(
                    'connection' => true,
                    'message' => 'Connection established',
                )
            );
        } catch (Exception $e) {
            return wp_send_json(
                array(
                    'status' => false,
                    'message' => $e->getMessage(),
                )
            );
        }

    }
    /**
     * close mongo connection
     */
    public function close()
    {
        if ($this->connection) {

            $this->connection = null;

            return wp_send_json(
                array(
                    'status' => true,
                    'message' => 'Connection disconnected',
                )
            );

        }
        return wp_send_json(
            array(
                'status' => false,
                'message' => 'not connection in progress',
            )
        );
    }

    public function me()
    {

        if ($this->connection) {

            return wp_send_json(
                array(
                    'status' => true,
                    'data' => array('username' => $this->username, 'connected' => true),
                )
            );

        } else {

            return wp_send_json(
                array(
                    'status' => false,
                    'message' => 'no user',
                )
            );

        }
    }
}