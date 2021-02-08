<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        parse_str($msg, $arr);
        $arg1 = $arr["arg1"];
        $arg2 = $arr["arg2"];
        $op = $arr["op"];
        if($op == '+') $res = $arg1 + $arg2;
        if($op == '-') $res = $arg1 - $arg2;
        if($op == '*') $res = $arg1 * $arg2;
        if($op == '/') $res = $arg1 / $arg2;
        if(!isset($res)) $res = "NULL";
        $from->send($res);
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}