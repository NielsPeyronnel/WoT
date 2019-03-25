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
        parent::__construct($address,$port);
        echo "coucou";

        $this->servername = "localhost";
        $this->username = "niels";
        $this->password = "niteversion";
        $this->database ="phpmyadmin";
        $this->conn = new mysqli($this->servername, $this->username, $this->password,$this->database);
        if ($this->conn->connect_error) {
            die("[".date("H:i:s")."] --{MYSQL}-- Connection echec (" . $this->conn->connect_error.")\n\n");
        }

        echo "[".date("H:i:s")."] --{MYSQL}-- Connection établie\n\n";
    }

    function process($user, $msg){

        $query_debug =null;
        $query_debug = "SELECT * FROM Event";
        $result = $this->conn->query($query_debug);
        $row = $result->num_rows;
        var_dump($msg);
        //$this->send($user->socket,$row);
    }
}




$master = new event("54.38.38.118",1337);
?>