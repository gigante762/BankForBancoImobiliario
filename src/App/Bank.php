<?php
namespace App;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Bank implements MessageComponentInterface {
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
        /* $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's'); */

        
        $data = json_decode($msg);
        $name = $data->name ?? '';
        $text = $data->msg ?? '';
        $type = $data->type ?? '';

        echo ">{$name}: {$text} | {$type}\n";

        if($type === 'connected')
        {
            $account = new AccountModel;
            $account->find($name);
            $from->send($msg);

        }else if ($type === 'payBank')
        {
            (new AccountModel)->pagarBanco($name,(int) $text);
            $this->sendUpdateToall();
        
        }
        else if ($type === 'receiveBank')
        {
            (new AccountModel)->receberBanco($name,(int) $text);
            $this->sendUpdateToall();
        
        }
        else if ($type === 'tranfer')
        {
            (new AccountModel)->transferir($name, $text);
            $this->sendUpdateToall();
        
        }
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

    private function sendUpdateToall()
    {
        foreach ($this->clients as $client) {
            $client->send('update');
        }
    }
}