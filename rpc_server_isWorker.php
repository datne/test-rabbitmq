<?php 

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

//declare queue 'rpc_queue'
$channel->queue_declare('rpc_queue', false, false, false, false);

echo " [x] Awaiting RPC requests\n";

$callback = function ($req) {

	echo 'Đã xử lý '.$req->get('correlation_id'),"\n";

	$msg = new AMQPMessage('Trả về cho: '.$req->get('reply_to') .'-'.$req->body,['correlation_id' => $req->get('correlation_id')]);
	$req->delivery_info['channel']->basic_publish($msg,'',$req->get('reply_to')/*key_routing(match vs $callback_queue (rpc_client.php))*/);
	$req->delivery_info['channel']->basic_ack($req->delivery_info['delivery_tag']);
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('rpc_queue', '', false, false, false, false, $callback);

while (count($channel->callbacks)) {
	$channel->wait();
}

$channel->close();
$connection->close();


?>