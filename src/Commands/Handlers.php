<?php

namespace Elephox\Inspector\Commands;

use Elephox\Core\Handler\Attribute\CommandHandler;
use Elephox\Core\Handler\Contract\HandlerContainer;
use Elephox\Logging\Contract\Logger;

#[CommandHandler('inspector:handlers')]
class Handlers
{
    public function __invoke(HandlerContainer $handlerContainer, Logger $logger)
    {
        $logger->info("Following handlers were registered:");

        /** @var \Elephox\Core\Handler\Contract\HandlerBinding $binding */
        foreach ($handlerContainer->getHandlers() as $binding) {
            $logger->info("- [{$binding->getHandlerMeta()->getType()->getName()}]");
        }
    }
}
