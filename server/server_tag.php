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

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("[".date("H:i:s")."] --{MYSQL}-- Connection echec\n\n" . $conn->connect_error);
}
echo "[".date("H:i:s")."] --{MYSQL}-- Connection établie\n\n";

$nb_event = "SELECT COUNT(id) FROM Event";
echo "[".date("H:i:s")."] --{MYSQL}-- Résultat requête\n\n".$conn->query($nb_event,MYSQLI_USE_RESULT);

class event extends WebSocket {
    function process($user){
        $this->send($user->socket,"3");
    }
}

$master = new event("54.38.38.118",1337);
?>