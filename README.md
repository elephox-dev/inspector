<p align=center>
  <img src="https://raw.githubusercontent.com/elephox-dev/.github/main/profile/logo.svg" alt="Elephox Logo" height=100>
</p>

<p align=center>
  Inspector helps debugging your Elephox application.
</p>

# Installation

Simply add the `InspectorRegistrar` to your `Core` bootstrap:

```php
// bootstrap.php

use Elephox\Core\Core;
use Elephox\Inspector\InspectorRegistrar;

// use your own Core class or use the default one
$core = Core::create();

// register the InspectorRegistrar along with your other registrars
$core->checkRegistrar(InspectorRegistrar::class);

// return the core instance
return $core;
```

# Commands

```bash
# list all registered handlers
elephox inspector:handlers

# ...is the same as filtering by all available types
elephox inspector:handlers --type=route,command,event,exception

# list all registered route handlers by filtering by route type
elephox inspector:handlers --type=route
```
