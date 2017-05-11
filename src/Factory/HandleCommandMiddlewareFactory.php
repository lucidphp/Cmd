<?php

/*
 * This File is part of the Lucid package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Factory;

use Lucid\CommandHandlerManager;
use Interop\Container\ContainerInterface;
use Lucid\Middleware\HandleCommandMiddleware;
use Zend\ServiceManager\Factory\FactoryInterface;

class HandleCommandMiddlewareFactory implements FactoryInterface {
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new HandleCommandMiddleware($container->get('simple_bus.command_handlers'));
    }
}