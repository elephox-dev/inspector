<?php

namespace Elephox\Inspector;

use Elephox\Core\Registrar;
use Elephox\Inspector\Commands\Routes;

class InspectorRegistrar
{
    use Registrar;

    public $classes = [
        Routes::class,
    ];
}
