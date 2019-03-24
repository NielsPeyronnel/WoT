<?php
/**
 * Created by IntelliJ IDEA.
 * User: niels
 * Date: 22/03/2019
 * Time: 18:28
 */

require './phpwebsocket/websocket.class.php';

class event extends WebSocket {

    function process($user, $msg){
        $servername = "localhost";
        $username = "niels";
        $password = "niteversion";
        $databse ="phpmyadmin";
        $conn = new mysqli($servername, $username, $password,$databse);
        if ($conn->connect_error) {
            die("[".date("H:i:s")."] --{MYSQL}-- Connection echec (" . $conn->connect_error.")\n\n");
        }

        echo "[".date("H:i:s")."] --{MYSQL}-- Connection établie\n\n";
        $query =null;
        $query = "SELECT * FROM Event";
        $result = $conn->query($query);
        $row = $result->num_rows;
        var_dump($msg);
        //$this->send($user->socket,$row);
    }
}




$master = new event("54.38.38.118",1337);
?>