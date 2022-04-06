<p align=center>
  <img src="https://raw.githubusercontent.com/elephox-dev/.github/main/profile/logo.svg" alt="Elephox Logo" height=100>
</p>

<p align=center>
  Inspector helps debugging your Elephox application.
</p>

# Installation

Simply add the `InspectorRegistrar` to your `Core` bootstrap:

```php
// bin/run

// create your console app builder
$builder = ConsoleApplicationBuilder::create()
	->addLogging()
	->addWhoops()
;

// load your app commands
$builder->commands->loadFromNamespace("App\\Commands");

// load the inspector commands
$builder->commands->loadFromNamespace("Elephox\\Inspector\\Commands");
```

# Commands

```bash
# list all application routes
bin/run routes

# serve your application on port 8080
bin/run serve --port=8080
```
