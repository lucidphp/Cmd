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

use Lucid\CommandBus;
use Interop\Container\ContainerInterface;
use Lucid\Middleware\Middlewares;
use Zend\ServiceManager\Factory\FactoryInterface;

class CommandBusFactory implements FactoryInterface {
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new CommandBus(new Middlewares($this->getMiddlewares($container)));
    }

    private function getMiddlewares(ContainerInterface $container) {
        $config = $container->get('Configuration');
        if (isset($config['simple_bus']['middlewares'])) {
            return array_map(function ($serviceId) use ($container) {
                return $container->get($serviceId);
            }, $config['simple_bus']['middlewares']);
        }
    }
}