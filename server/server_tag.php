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
        print_r("\n\n[".date("H:i:s")."] --{DEBUG}-- ". $msg ."\n\n");

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
                $query_event = 'SELECT * FROM Event WHERE DATE(time) = "'.date("Y-m-d").'"';
                $result = $this->conn->query($query_event);
                $table = $result->fetch_all(MYSQLI_ASSOC);
                $array = array('type' => 'onload_event', 'table' => $table);
                $this->send($user->socket,json_encode($array));
                break;
            case 'login':
                $query_login = 'SELECT * FROM User WHERE User.name="'.$msg[user].'" AND User.password="'.$msg[pass].'"';
                $result = $this->conn->query($query_login);
                $login = $result->fetch_row();
                if ($result->num_rows){
                    $query_signup = 'SELECT eventid from Signup Where userid = '.$login[0];
                    echo "\n\n[".date("H:i:s")."] --{DEBUG QUERY}-- ". $query_signup ."\n\n";
                    $result = $this->conn->query($query_signup);
                    $list_event = $result->fetch_all(MYSQLI_ASSOC);
                    $login_answer = array('type' => 'login', 'login' => 'OK', 'info' => $login, 'liste' => $list_event);
                    $this->send($user->socket,json_encode($login_answer));
                } else {
                    $login_answer = array('type' => 'login', 'login' => 'NOK');
                    $this->send($user->socket,json_encode($login_answer));
                }
                break;
            case 'required':
                $query_required = 'SELECT * FROM Signup Where eventid ="'.$msg['id'].'"';
                $result = $this->conn->query($query_required);
                $number = $result->num_rows;
                echo "\n\n[".date("H:i:s")."] --{DEBUG QUERY NUMBER}-- ". $number ." OK\n\n";
                $array = array ('type' => 'required', 'id' => $msg['id'], 'num' => $number );
                $this->send($user->socket,json_encode($array));
                break;
            case 'signin':
                echo "\n\n[".date("H:i:s")."] --{DEBUG EVENT}-- Inscription Evenement OK\n\n";
                $query_signin = 'INSERT INTO `Signup`(`userid`, `eventid`) VALUES ('.$msg['userid'].','.$msg['eventid'].')';
                if ($this->conn->query($query_signin)){
                    $array = array ('type' => 'signin', 'info' => 'OK');
                    $this->send($user->socket,json_encode($array));
                } else {
                    $array = array ('type' => 'signin', 'info' => 'NOK');
                    $this->send($user->socket,json_encode($array));
                }
                break;
            case 'signout':
                echo "\n\n[".date("H:i:s")."] --{DEBUG EVENT}-- Désinscription Evenement OK\n\n";
                $query_signout = 'DELETE FROM `Signup` WHERE userid = "'.$msg['userid'].'" AND eventid = "'.$msg['eventid'].'"';
                if ($this->conn->query($query_signout)){
                    $array = array ('type' => 'signout', 'info' => 'OK');
                    $this->send($user->socket,json_encode($array));
                } else {
                    $array = array ('type' => 'signout', 'info' => 'NOK');
                    $this->send($user->socket,json_encode($array));
                }
                break;
            default:
                $default = array('type' => 'default');
                $this->send($user->socket,$default);
        }
        $msg = NULL;

        //$this->send($user->socket,$row);
    }
}




$master = new event("54.38.38.118",1337);
?>