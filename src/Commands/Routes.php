<?php

namespace Elephox\Inspector\Commands;

use Elephox\Core\Handler\Attribute\CommandHandler;
use Elephox\Core\Handler\Contract\HandlerContainer;
use Elephox\Logging\Contract\Logger;

#[CommandHandler('inspector:routes')]
class Routes
{
    public function __invoke(HandlerContainer $handlerContainer, Logger $logger)
    {
        $logger->info("Following request handlers were registered:");

        // TODO: list request handlers from handler container
    }
}
