<?php

/*
 * This File is part of the Lucid package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Cmd\Middleware;

use Lucid\Cmd\CommandHandlers;
use Lucid\Cmd\CommandInterface;
use Lucid\Cmd\CommandHandlerManager;

/**
 * Class HandleCommandMiddleware
 * @package Lucid
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
class HandleCommandMiddleware implements MiddlewareInterface {
    /** @var CommandHandlers */
    private $handlers;

    /**
     * HandleCommandMiddleware constructor.
     *
     * @param CommandHandlerManager $handlers
     */
    public function __construct(CommandHandlerManager $handlers) {
        $this->handlers = $handlers;
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException if no handler for the given command is found.
     * @throws \RuntimeException if a handler does not implement an appropriate handle method.
     */
    public function __invoke(CommandInterface $command, DelegateInterface $next) {
        if (!$handler = $this->handlers->getHandler($class = get_class($command))) {
            throw new \RuntimeException(sprintf('No handler for command "%s".', $class));
        }

        if (!method_exists($handler, $method = $this->getMethodName($class) )) {
            throw new \RuntimeException(sprintf('Expected handler to implement %s().', $method));
        }

        $handler->{$method}($command);

        $next($command);
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function getMethodName($class) {
        $parts = explode('\\', $class);
        return 'handle'.ucfirst(end($parts));
    }
}