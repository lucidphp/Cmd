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

use Lucid\CommandHandlers;
use Interop\Container\ContainerInterface;
use Lucid\CommandHandlerResolver;
use Lucid\LazyResolvingCommandHandlers;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class CommandHandlersFactory
 * @package Lucid\Factory
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
class CommandHandlersFactory implements FactoryInterface {
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return LazyResolvingCommandHandlers
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $handlers   = $container->get('Configuration')['simple_bus']['command_handlers'];

        return new LazyResolvingCommandHandlers(
            new CommandHandlers(),
            new CommandHandlerResolver($container, $handlers ?: [])
        );
    }
}