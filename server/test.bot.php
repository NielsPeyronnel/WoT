#!/usr/bin/php -q

<?php
/**
 * Created by IntelliJ IDEA.
 * User: niels
 * Date: 19/03/2019
 * Time: 10:38
 */


// inclusion de la classe
require './phpwebsocket/websocket.class.php';


// extension de la classe websocket
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

$master = new ChatBot("0.0.0.0",1337);

?>

