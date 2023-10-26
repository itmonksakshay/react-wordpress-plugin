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
    private $url = null;


    public function __construct()
    {
        if (get_option('mongo_username') !== ''):
            $this->username = get_option('mongo_username');
        else:
            return false;
        endif;


        if (get_option('mongo_password') !== ''):
            $this->password = get_option('mongo_password');
        else:
            return false;
        endif;


        if (get_option('mongo_host') !== ''):
            $this->host = get_option('mongo_host');
        endif;


        if (get_option('mongo_database') !== ''):
            $this->database = get_option('mongo_database');
        endif;

        // create url
        $this->url = "mongodb://$this->username:$this->password@$this->host/$this->database";
        try {
            $this->connection = new MongoDB\Client($this->url);
        } catch (\Throwable $th) {
            //throw $th;
            return wp_send_json(
                array(
                    'connection' => true,
                    'message' => 'Invalid URL',
                )
            );
        }

    }

    /**
     * start connection //true when successfully connected
     */
    public function start($options = [])
    {


        try {
            // Send a ping to confirm a successful connection
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
            update_option('mongo_username', '');

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
                'message' => 'No connection in progress',
            )
        );
    }

    public function me()
    {
        if ($this->connection):

            return wp_send_json(
                array(
                    'status' => true,
                    'data' => array('username' => get_option('mongo_username'), 'connected' => true),
                )
            );
        endif;

        return wp_send_json(
            array(
                'status' => false,
                'message' => 'No user or connection not established',
            )
        );

    }
}