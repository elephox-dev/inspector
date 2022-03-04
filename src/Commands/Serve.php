<?php
declare(strict_types=1);

namespace Elephox\Inspector\Commands;

use Elephox\Core\Context\Contract\CommandLineContext;
use Elephox\Core\Handler\Attribute\CommandHandler;
use Elephox\Files\Directory;
use Elephox\Logging\Contract\Logger;
use InvalidArgumentException;
use RuntimeException;

#[CommandHandler("/^inspector:serve(?:\s.*)?$/")]
class Serve
{
	/**
	 * @throws \Throwable
	 */
	public function __invoke(CommandLineContext $context, Logger $logger): int
	{
		$args = $context->getArgs()
			->select(fn(string $arg) => explode('=', $arg, 2))
			->select(fn(array &$arg) => $arg[0] = strtolower(ltrim($arg[0], '-')));

		$host = 'localhost';
		$port = 8000;
		$cwd = new Directory(getcwd());
		do {
			$public = $cwd->getChild('public');
		} while ((!$public->exists() && $cwd = $cwd->getParent()) || $cwd->isRoot());
		$root = $public->exists() ? $public : $cwd;

		foreach ($args as $arg) {
			switch ($arg[0]) {
				case 'host':
					$host = $arg[1];
					break;
				case 'port':
					if (!ctype_digit($arg[1])) {
						throw new InvalidArgumentException('Port must be a number');
					}

					$port = (int)$arg[1];

					if ($port < 1024 || $port > 65535) {
						throw new InvalidArgumentException('Port must be between 1024 and 65535');
					}
					break;
				case 'root':
					$root = new Directory($arg[1]);

					if (!$root->exists()) {
						throw new InvalidArgumentException('Root directory does not exist');
					}
					break;
			}
		}

		$process = proc_open(
			sprintf("%s -S %s:%d", escapeshellarg(PHP_BINARY), $host, $port),
			[STDIN],
			$pipes,
			$root->getPath(),
			array_merge(getenv(), $_ENV),
		);

		register_shutdown_function(static fn (): bool => proc_terminate($process));

		if ($process === false) {
			throw new RuntimeException('Failed to start server');
		}

		$status = proc_get_status($process);
		if ($status['running'] === false) {
			$logger->error('Server exited unexpectedly', ['source' => 'control']);

			return -1;
		}

		$logger->info('Server process started', ['source' => 'control', 'pid' => $status['pid'], 'command' => $status['command']]);

		while ($status = proc_get_status($process)) {
			if (!$status['running']) {
				break;
			}

			sleep(1);
		}

		$logger->warning(sprintf('Server process exited with %d', $status['exitcode']));

		return 0;
	}
}
