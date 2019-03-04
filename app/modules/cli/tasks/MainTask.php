<?php
namespace Store2\Modules\Cli\Tasks;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MainTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {


        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);
        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        $callback = function($msg) {
            echo " [x] Received ", $msg->body, "\n";
        };
        $channel->basic_consume('hello', '', false, true, false, false, $callback);
        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public function testAction(  )
    {



        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);

        $msg = new AMQPMessage('Hello World3!');
        $channel->basic_publish($msg, '', 'hello');

        echo " [x] Sent 'Hello World2!'\n";
        $channel->close();
        $connection->close();
    }

    public function test2Action(  )
    {
        $conf = [
            'host' => '127.0.0.1',
            'port' => 5672,
            'user' => 'guest',
            'pwd' => 'guest',
            'vhost' => '/',
        ];
        $exchangeName = 'kd_sms_send_ex'; //交换机名
        $queueName = 'kd_sms_send_q'; //队列名称
        $routingKey = 'sms_send'; //路由关键字(也可以省略)

        $conn = new AMQPStreamConnection( //建立生产者与mq之间的连接
            $conf['host'], $conf['port'], $conf['user'], $conf['pwd'], $conf['vhost']
        );

        $channel = $conn->channel(); //在已连接基础上建立生产者与mq之间的通道

        $channel->exchange_declare($exchangeName, 'direct', false, true, false); //声明初始化交换机
        $channel->queue_declare($queueName, false, true, false, false); //声明初始化一条队列
        $channel->queue_bind($queueName, $exchangeName, $routingKey); //将队列与某个交换机进行绑定，并使用路由关键字

        $msgBody = json_encode(["name" => "iGoo", "age" => 22]);
        var_export($msgBody, true);
        $msg = new AMQPMessage($msgBody, ['content_type' => 'text/plain', 'delivery_mode' => 2]); //生成消息
        $r = $channel->basic_publish($msg, $exchangeName, $routingKey); //推送消息到某个交换机
        $channel->close();
        $conn->close();

    }

    public function test3Action(  )
    {
        $exchangeName = 'kd_sms_send_ex'; //交换机名
        $queueName = 'kd_sms_send_q'; //队列名称
        $routingKey = 'sms_send'; //路由关键字(也可以省略)
        $connection = new AMQPStreamConnection('127.0.0.1', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->exchange_declare($exchangeName, 'direct', false, true, false); //声明初始化交换机
        $channel->queue_declare($queueName, false, true, false, false); //声明初始化一条队列
        $channel->queue_bind($queueName, $exchangeName, $routingKey); //将队列与某个交换机进行绑定，并使用路由关键字

        echo ' [*] Waiting 444 for messages. To exit press CTRL+C', "\n";
        $callback = function($msg) {
            echo " [x] Received ", $msg->body, "\n";
        };
        $channel->basic_consume($queueName, '', false, true, false, false, $callback);
        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public function consumeAction(  )
    {
//        $conf = [
//            'host' => '127.0.0.1',
//            'port' => 5672,
//            'user' => 'xuzan',
//            'pwd' => 'xuzan',
//            'vhost' => 'x',
//        ];

        $conf = [
            'host' => '192.168.0.78',
            'port' => 5672,
            'user' => 'root',
            'pwd' => 'Mq@0304',
            'vhost' => '/',
        ];
        $exchangeName = 'kd_sms_send_ex'; //交换机名
        $queueName = 'kd_sms_send_q'; //队列名称
        $routingKey = 'sms_send'; //路由关键字(也可以省略)

        $conn = new AMQPStreamConnection( //建立生产者与mq之间的连接
            $conf['host'], $conf['port'], $conf['user'], $conf['pwd'], $conf['vhost']
        );

        $channel = $conn->channel(); //在已连接基础上建立生产者与mq之间的通道

        $channel->exchange_declare($exchangeName, 'direct', false, true, false); //声明初始化交换机
        $channel->queue_declare($queueName, false, true, false, false); //声明初始化一条队列
        $channel->queue_bind($queueName, $exchangeName, $routingKey); //将队列与某个交换机进行绑定，并使用路由关键字

        for ($i=1;$i<=100000;$i++) {
            $msgBody = json_encode(["n、ame" => "iGoo", "age" => $i]);

            var_export($msgBody, true);
            $msg = new AMQPMessage($msgBody, ['content_type' => 'text/plain', 'delivery_mode' => 2]); //生成消息
            $r = $channel->basic_publish($msg, $exchangeName, $routingKey); //推送消息到某个交换机
        }
//        var_export($msgBody, true);
//        $msg = new AMQPMessage($msgBody, ['content_type' => 'text/plain', 'delivery_mode' => 2]); //生成消息
//        $r = $channel->basic_publish($msg, $exchangeName, $routingKey); //推送消息到某个交换机
        $channel->close();
        $conn->close();

    }

    public function productAction(  )
    {
        $exchangeName = 'kd_sms_send_ex'; //交换机名
        $queueName = 'kd_sms_send_q'; //队列名称
        $routingKey = 'sms_send'; //路由关键字(也可以省略)
        $connection = new AMQPStreamConnection('192.168.0.78', 5672, 'root', 'Mq@0304','/');
        $channel = $connection->channel();
        $channel->exchange_declare($exchangeName, 'direct', false, true, false); //声明初始化交换机
        $channel->queue_declare($queueName, false, true, false, false); //声明初始化一条队列
        $channel->queue_bind($queueName, $exchangeName, $routingKey); //将队列与某个交换机进行绑定，并使用路由关键字

        echo ' [*] Waiting 444 for messages. To exit press CTRL+C', "\n";
        $callback = function($msg) {
            echo " [x] Received ", $msg->body, "\n";
        };
        $channel->basic_consume($queueName, '', false, true, false, false, $callback);
        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
