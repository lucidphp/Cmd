# Simple Command Bus

A simple command bus implementation.

## Installation

Install as usual via composer.

## Configuration

### Register CommandHandlers

<del>First, register an event listener in you Zend 3 Project. </del>

Use configuration to register command handlers.

```php
<?php
namespace Acme\Project

return [
// ...
'simple_bus' => [
        // ...
        'command_handlers' => [
            SignupUserCommand::class           => 'signup_user.command_handler',
            SendPasswordReminderCommand::class => 'send_password_reminder.command_handler',
            // ...
        ]
    ]
]
```

You can only register one handler per command. 
Make sure your handler implements a handle method that reflects the command name, e.g. a command `Acme\SignUpUserCommand` translates to `handleSignUpUserCommand()`;


```php
<?php
namespace Acme;

class SignupUserCommandHandler implements CommandHandlerInterface {
    public function handleSignUpUserCommand(SignUpUserCommand $command) {
    // ...
    }
}

```

### Middleware

You can add all sorts of middleware to the execution stack. Notice that `HandleCommandMiddleware` should always be the last in the chain, because it's responsible for the actual command dispatching.

Example below shows the default configuration.

```php
<?php

use Lucid\Cmd\Middleware\HandleCommandMiddleware;

return [
...
    'simple_bus' => [
        'middlewares' => [
            'simple_bus.middleware.handle_command'
        ]
    ]
];

```

## Usage

The command bus exposes one method `handle()` which takes an instance of `Lucid\Cmd\CommandInterface` as its only argument.

```php
<?php
namespace Acme\Domain\Command;

$commandBus->handle($command);

```

## Middleware

Internally, the command bus dispatches a middleware chain everytime the handle() method is executed.
Commandhandling itself is provided via a middleware that ships with this package. It must be configured to run as last in the middleware chain.

Below an example of a validating middleware:

```php
<?php
namespace Acme\Commanding;

use Lucid\Cmd\CommandInterface;
use Lucid\Cmd\Middleware\DelegateInterface;
use Lucid\Cmd\Middleware\MiddlewareInterface;

class MyFancyMiddleware implements MiddlewareInterface {
    public function __construct(\Acme\Validator $validator) {...}

    public function __invoke(CommandInterface $command, DelegateInterface $next) {
        if (!$this->validator->validate($command)) {
            throw new \Acme\ValidationException('command invalid');
        }  
        
        $next($command);
    }
}

```
