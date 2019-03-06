<?php 

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

//create queue with queue-name => 'dat-ne'

// the third param of this function is True (always keep queue when rabbitmq server die)
$channel->queue_declare('dat-ne', false, true, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg){
    echo " [x] Received ", $msg->body, "\n";
    $job = json_decode($msg->body, $assocForm=true);
    sleep($job['sleep_period']);
    echo " [x] Done", "\n";
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

//The shared queue for all workers
$channel->basic_qos(null, 1, null);

$channel->basic_consume('dat-ne','',false,false,false,false,$callback);

while (count($channel->callbacks)) 
{
    $channel->wait();
}

$channel->close();
$connection->close();

?>