<?php
declare(strict_types=1);

namespace Elephox\Inspector\Commands;

use Elephox\Collection\Enumerable;
use Elephox\Console\Command\CommandInvocation;
use Elephox\Console\Command\CommandTemplateBuilder;
use Elephox\Console\Command\Contract\CommandHandler;
use Elephox\Logging\Contract\Logger;
use Elephox\Web\Routing\Contract\Router;

class RoutesCommand implements CommandHandler
{
	public function __construct(
		private readonly Logger $logger,
		private readonly Router $router,
	)
	{
	}

	public function configure(CommandTemplateBuilder $builder): void
	{
		$builder
			->name('routes')
			->description('List all routes')
		;
	}

	public function handle(CommandInvocation $command): int|null
	{
		$routes = Enumerable::from($this->router->getRouteHandlers());

		$this->logger->info('Following routes where found:');

		foreach ($routes->orderByDescending(fn($r) => $r->getSourceAttribute()->getWeight()) as $route) {
			$attribute = $route->getSourceAttribute();
			$this->logger->info(sprintf(
				"(%d) [%s] %s",
				$attribute->getWeight(),
				implode(', ', $attribute->getRequestMethods()->select(fn($m) => (string)$m)->toList()),
				$attribute->getPath(),
			));
		}

		return 0;
	}
}
