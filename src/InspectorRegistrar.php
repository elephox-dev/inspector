<?php

namespace Elephox\Inspector;

use Elephox\Core\Contract\Registrar;
use Elephox\Core\Registrar as RegistrarTrait;
use Elephox\Inspector\Commands\Handlers;

class InspectorRegistrar implements Registrar
{
    use RegistrarTrait;

    public $classes = [
        Handlers::class,
    ];
}
