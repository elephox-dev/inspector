<?php
declare(strict_types=1);

namespace Elephox\Inspector\Commands;

use Elephox\Console\Command\CommandInvocation;
use Elephox\Console\Command\CommandTemplateBuilder;
use Elephox\Console\Command\Contract\CommandHandler;
use Elephox\Logging\Contract\Logger;
use InvalidArgumentException;
use RuntimeException;

class ServeCommand implements CommandHandler
{
	public function __construct(
		private readonly Logger $logger,
	)
	{
	}

	public function configure(CommandTemplateBuilder $builder): void
	{
		$builder
			->name('inspector:serve')
			->description('Starts the PHP built-in webserver for your application')
			->argument('host', 'Host to bind to', 'localhost', false)
			->argument('port', 'Port to bind to', '8000', false)
			->argument('root', 'Root directory to serve', APP_ROOT . '/public', false)
		;
	}

	public function handle(CommandInvocation $command): int|null
	{
		$host = $command->host;
		$port = $command->port;
		$root = $command->root;

		if (!ctype_digit($port)) {
			throw new InvalidArgumentException('Port must be a number');
		}

		$port = (int) $port;
		if ($port < 1024 || $port > 65535) {
			throw new InvalidArgumentException('Port must be between 1024 and 65535');
		}

		if (!is_dir($root)) {
			throw new InvalidArgumentException('Root directory (' . $root . ') does not exist');
		}

		$root = realpath($root);

		$this->logger->info('Starting PHP built-in webserver on ' . $host . ':' . $port);

		$process = proc_open(
			sprintf("%s -S %s:%d", escapeshellarg(PHP_BINARY), $host, $port),
			[STDIN],
			$pipes,
			$root,
			array_merge(getenv(), $_ENV),
		);

		if ($process === false) {
			throw new RuntimeException('Failed to start server');
		}

		$status = proc_get_status($process);
		if ($status['running'] === false) {
			$this->logger->error('Server exited unexpectedly');

			return -1;
		}

		register_shutdown_function(static fn(): bool => proc_terminate($process));

		$this->logger->info('Server process started', ['pid' => $status['pid'], 'command' => $status['command']]);

		while ($status = proc_get_status($process)) {
			if (!$status['running']) {
				break;
			}

			sleep(1);
		}

		$this->logger->warning(sprintf('Server process exited with %d', $status['exitcode']));

		return 0;
	}
}
