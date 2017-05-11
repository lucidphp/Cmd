<?php

/*
 * This File is part of the Lucid package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid;

use Lucid\Factory\CommandBusFactory;
use Lucid\Factory\CommandHandlersFactory;
use Lucid\Factory\HandleCommandMiddlewareFactory;
use Lucid\Middleware\HandleCommandMiddleware;

return [
    'service_manager' => [
        'factories' => [
            'simple_bus.command_bus'               => CommandBusFactory::class,
            'simple_bus.command_handlers'          => CommandHandlersFactory::class,
            'simple_bus.middleware.handle_command' => HandleCommandMiddlewareFactory::class
        ],
    ],
    'simple_bus' => [
        'middlewares' => [
           'simple_bus.middleware.handle_command'
        ],
        'command_handlers' => [
            'CommandClass' => 'CommandHandler'
        ]
    ]
];