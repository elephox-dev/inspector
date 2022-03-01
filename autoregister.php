<?php
declare(strict_types=1);

use Elephox\Inspector\Commands\Handlers;
use Elephox\Inspector\Commands\Serve;

$GLOBALS['__elephox_autoregister_class'][] = Handlers::class;
$GLOBALS['__elephox_autoregister_class'][] = Serve::class;
