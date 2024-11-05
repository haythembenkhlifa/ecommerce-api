<?php

namespace Modules\Order\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MessageBrokerService
{
    protected $connection;

    public function __construct()
    {
        $this->connection = $this->checkRabbitMqConnection();
    }

    protected function checkRabbitMqConnection()
    {
        try {
            new Exception('connection failed');
            return new AMQPStreamConnection(
                config('queue.connections.rabbitmq.host'),
                config('queue.connections.rabbitmq.port'),
                config('queue.connections.rabbitmq.user'),
                config('queue.connections.rabbitmq.password'),
                config('queue.connections.rabbitmq.vhost')
            );
        } catch (\Exception $e) {
            Log::error('Could not connect to RabbitMQ: ' . $e->getMessage());
            return null;
        }
    }

    public function publish($messageBody)
    {
        if ($this->connection) {
            try {
                $channel = $this->connection->channel();
                $channel->queue_declare('default', false, true, false, false, false, []);

                $message = new AMQPMessage($messageBody);
                $channel->basic_publish($message, '', 'default');

                $channel->close();
                $this->connection->close();
            } catch (\Exception $e) {
                Log::error('RabbitMQ publishing failed: ' . $e->getMessage());
                $this->fallbackToRedis($messageBody);
            }
        } else {
            $this->fallbackToRedis($messageBody);
        }
    }

    protected function fallbackToRedis($messageBody)
    {
        Redis::lpush('fallback_queue', $messageBody);
        Log::info('Message queued in Redis: ' . $messageBody);
    }

    public function processFallbackQueue()
    {
        Log::info('processFallbackQueue ');

        while ($message = Redis::rpop('fallback_queue')) {
            Log::info('processFallbackQueue ');
            $this->retryPublish($message);
        }
    }

    protected function retryPublish($messageBody)
    {
        $this->connection = $this->checkRabbitMqConnection();
        if ($this->connection) {
            try {
                $this->publish($messageBody);
            } catch (\Exception $e) {
                Log::error('Failed to republish message: ' . $e->getMessage());
            }
        } else {
            Log::warning('RabbitMQ still down, message remains in Redis: ' . $messageBody);
        }
    }
}
