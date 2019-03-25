<?php
/**
 * Created by IntelliJ IDEA.
 * User: niels
 * Date: 22/03/2019
 * Time: 18:28
 */

require './phpwebsocket/websocket.class.php';


class event extends WebSocket {

    var $servername;
    var $username;
    var $password;
    var $database;
    var $conn;

    function __construct($address,$port)
    {
        $this->servername = "localhost";
        $this->username = "niels";
        $this->password = "niteversion";
        $this->database ="phpmyadmin";
        $this->conn = new mysqli($this->servername, $this->username, $this->password,$this->database);
        if ($this->conn->connect_error) {
            die("\n\n[".date("H:i:s")."] --{MYSQL}-- Connection echec (" . $this->conn->connect_error.")\n\n");
        }

        echo "\n\n[".date("H:i:s")."] --{MYSQL}-- Connection établie\n\n";

        parent::__construct($address,$port);

    }

    function process($user, $obj){
        $msg = json_decode($obj);
        echo "\n\n[".date("H:i:s")."] --{DEBUG}-- ". $msg ."\n\n";
        switch($msg->type){
            case 'debug':
                $query_event = "SELECT * FROM Event";
                $result = $this->conn->query($query_event);
                $row = $result->num_rows;
                $debug_array = array('type' => 'debug', 'Event' => $row);
                $this->send($user,$debug_array);
                break;
        }


        //$this->send($user->socket,$row);
    }
}




$master = new event("54.38.38.118",1337);
?>