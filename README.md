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

// use your own Core class or use the default one
$core = Core::create();


// make sure you either register handlers from global scope...
$core->registerGlobal();

// ... or register the handlers yourself:
$core->getHandlerContainer()->loadFromClass(\Elephox\Inspector\Commands\Handlers::class);


// return the core instance
return $core;
```

# Commands

## `inspector:handlers`

```bash
# list all registered handlers
elephox inspector:handlers

# ...is the same as filtering by all available types
elephox inspector:handlers --type=request,command,event,exception

# list all registered route handlers by filtering by route type
elephox inspector:handlers --type=request
```
