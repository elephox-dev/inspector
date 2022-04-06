<?php
declare(strict_types=1);

namespace Elephox\Inspector\Commands;

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
		$routes = $this->router->getRouteHandlers();

		foreach ($routes as $route) {
			$this->logger->info($route->getSourceAttribute()->getPath());
		}

		return 0;
	}
}
