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
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

class ChatBot extends WebSocket {
    function process($user, $msg){
        $this->say("<  ".$msg);
        switch ($msg){
            case 'hello':
                $this->send($user->socket,"Bonjour");
                break;
            case 'date':
                $this->send($user->socket,"Nous sommes le ".date("d/m/y"));
                break;
            case 'bye':
            case 'ciao':
                $this->send($user->socket,"Au revoir !");
                $this->disconnect($user->socket);
                break;
            default:
                $this->send($user->socket,"Je n'ai pas compris");
                break;
        }
    }
}

$master = new ChatBot("54.38.38.118",1337);
?>