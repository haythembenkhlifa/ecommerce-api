<?php

namespace Modules\Order\Console;

use Illuminate\Console\Command;
use Modules\Order\Services\MessageBrokerService;

class ProcessFallbackQueue extends Command
{
    protected $signature = 'queue:process-fallback';
    protected $description = 'Process the fallback Redis queue and republish messages to RabbitMQ';

    public function handle()
    {
        $messageService = new MessageBrokerService();
        $messageService->processFallbackQueue();
    }
}
