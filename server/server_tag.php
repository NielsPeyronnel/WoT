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
        $msg = json_decode($obj,TRUE);
        echo "\n\n[".date("H:i:s")."] --{DEBUG}-- ". $obj ."\n\n";
        print_r($obj);

        echo "\n\n[".date("H:i:s")."] --{DEBUG}-- ". $msg ."\n\n";
        print_r($msg);

        switch($msg['type']){
            case 'onloadDebug':
                $query_event = "SELECT * FROM Event";
                $query_user = "SELECT * FROM User";
                $result = $this->conn->query($query_event);
                $row_event = $result->num_rows;
                $result2 = $this->conn->query($query_user);
                $row_user = $result2->num_rows;
                $debug_array = array('type' => 'onload_debug', 'Event_line' => $row_event, 'User_line' => $row_user);
                $this->send($user->socket, json_encode($debug_array));
                break;
            case 'onloadEvent':
                $query_event = 'SELECT * FROM Event WHERE DATE(time) = "'.date("Y-M-D").'"';
                $result = $this->conn->query($query_event);
                $table = $result->fetch_all(MYSQLI_ASSOC);
                $array = array('type' => 'onload_event', 'table' => $table);
                $this->send($user->socket,json_encode($array));
                break;
            case 'login':
                $query_login = 'SELECT * FROM User WHERE User.name="'.$msg[user].'" AND User.password="'.$msg[pass].'"';
                $result = $this->conn->query($query_login);
                if ($result->num_rows){
                    $login_answer = array('type' => 'login', 'login' => 'OK');
                    $this->send($user->socket,json_encode($login_answer));
                } else {
                    $login_answer = array('type' => 'login', 'login' => 'NOK');
                    $this->send($user->socket,json_encode($login_answer));
                }
                break;
            default:
                $default = array('type' => 'default');
                $this->send($user->socket,$default);
        }


        //$this->send($user->socket,$row);
    }
}




$master = new event("54.38.38.118",1337);
?>