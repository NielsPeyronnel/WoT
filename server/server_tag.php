<?php
/**
 * Created by IntelliJ IDEA.
 * User: niels
 * Date: 22/03/2019
 * Time: 18:28
 */

require './phpwebsocket/websocket.class.php';

$servername = "localhost";
$username = "niels";
$password = "niteversion";
$databse ="phpmyadmin";

// Create connection
$conn = new mysqli($servername, $username, $password,$databse);

// Check connection
if ($conn->connect_error) {
    die("[".date("H:i:s")."] --{MYSQL}-- Connection echec (" . $conn->connect_error.")\n\n");
}

echo "[".date("H:i:s")."] --{MYSQL}-- Connection établie\n\n";

$query = "SELECT COUNT(*) FROM Event";
$result = $conn->query($query);
$row = $result->current_field;

echo $row."\n\n";



class event extends WebSocket {
    function process($user){
        $this->send($user->socket,"3");
    }
}

$master = new event("54.38.38.118",1337);
?>