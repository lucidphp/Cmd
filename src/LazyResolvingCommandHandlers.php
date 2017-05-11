<?php

/*
 * This File is part of the vufis2 package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Cmd;

/**
 * Class CommandHandlers
 * @package Lucid\Container
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
final class LazyResolvingCommandHandlers implements CommandHandlerManager {

    /**
     * @var CommandHandlerResolverInterface
     */
    private $resolver;
    /**
     * @var CommandHandlerManager
     */
    private $handlers;

    /**
     * CommandHandlers constructor.
     *
     * @param CommandHandlerManager           $handlers
     * @param CommandHandlerResolverInterface $resolver
     */
    public function __construct(CommandHandlerManager $handlers, CommandHandlerResolverInterface $resolver) {
        $this->resolver = $resolver;
        $this->handlers = $handlers;
    }

    /** {@inheritdoc} */
    public function register($commandClass, CommandHandlerInterface $handler) {
        $this->handlers->register($commandClass, $handler);
    }

    /** {@inheritdoc} */
    public function getHandler($commandClass) {
        try {
            return $this->resolver->resolve($commandClass);
        } catch (\InvalidArgumentException $e) {
            return $this->handlers->getHandler($commandClass);
        }
    }
}