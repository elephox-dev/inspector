<?php

namespace Elephox\Inspector\Commands;

use Elephox\Core\ActionType;
use Elephox\Core\Contract\Registrar;
use Elephox\Core\Handler\Attribute\CommandHandler;
use Elephox\Core\Handler\Contract\HandlerBinding;
use Elephox\Core\Handler\Contract\HandlerContainer;
use Elephox\DI\Contract\Container;
use Elephox\Logging\ConsoleSink;
use Elephox\Logging\Contract\Logger;
use Elephox\Logging\Contract\Sink;
use Elephox\Logging\GenericSinkLogger;

#[CommandHandler('/^inspector:handlers(?:\s*--type=(?<types>[^\s]*))?$/')]
class Handlers implements Registrar
{
	public function __invoke(HandlerContainer $handlerContainer, Logger $logger, ?string $types = null)
	{
		$logger->info("Following handlers were registered:");

		$bindings = $handlerContainer
			->getHandlers()
			->orderByDescending(static fn(HandlerBinding $b) => $b->getHandlerMeta()->getWeight());

		if ($types !== null) {
			$requestedTypes = explode(',', strtolower($types));
			$filterTypes = [];
			foreach (ActionType::cases() as $actionType) {
				if (in_array(strtolower($actionType->getName()), $requestedTypes, true)) {
					$filterTypes[] = $actionType;
				}
			}

			$bindings = $bindings->where(static fn(HandlerBinding $b) => in_array($b->getHandlerMeta()->getType(), $filterTypes, true));
		}

		/** @var \Elephox\Core\Handler\Contract\HandlerBinding $binding */
		foreach ($bindings as $binding) {
			$logger->info(sprintf(
				"- [%s, %d] %s",
				$binding->getHandlerMeta()->getType()->getName(),
				$binding->getHandlerMeta()->getWeight(),
				$binding->getFunctionName()
			));
		}
	}

	public function registerAll(Container $container): void
	{
		if (!$container->has(Logger::class)) {
			$container->register(Logger::class, GenericSinkLogger::class);
		}

		if (!$container->has(Sink::class)) {
			$container->register(Sink::class, ConsoleSink::class);
		}
	}
}
