<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
session_start();
//session_destroy();die;
if (!is_array($_SESSION["data"])) {
	$_SESSION["data"] = [];
}
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

$channel = $connection->channel();

//create queue with queue-name => 'dat-ne'
$channel->queue_declare('dat-ne', false, false, false, false);

// create message with body => 'đạt nè'
$msg = new AMQPMessage('hello, đạt nè');

// add message to queue 'dat-ne'
$channel->basic_publish($msg, '', 'dat-ne');

array_push($_SESSION["data"], $msg->body);
echo 'Đã gửi '.'"'.$msg->body.'" lần thứ '.sizeof($_SESSION['data']) .'<br />';

$channel->close();
$connection->close();
?>