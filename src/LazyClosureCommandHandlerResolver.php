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

use Closure;

/**
 * Class LazyClosureCommandHandlerResolver
 * @package Lucid
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
final class LazyClosureCommandHandlerResolver implements CommandHandlerResolverInterface {
    /**
     * @var \Closure
     */
    private $closure;
    /**
     * @var array
     */
    private $map;

    /**
     * LazyClosureCommandHandlerResolver constructor.
     *
     * @param Closure $closure
     */
    public function __construct(Closure $closure) {
        $this->closure = $closure;
    }

    /** {@inheritdoc} */
    public function resolve($commandClass) {
        $cl = $this->closure;
        $handler = $cl($commandClass);

        if ($handler === null || !$handler instanceof CommandHandlerInterface) {
            throw new \InvalidArgumentException(
                sprintf('Invalid handler or handler not found for command "%s".', $commandClass)
            );
        }

        return $handler;
    }
}