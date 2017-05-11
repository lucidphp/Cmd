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

use Lucid\Cmd\CommandInterface;

/**
 * Class Delegate
 * @package Lucid
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
final class Delegate implements DelegateInterface {
    /** * @var PoolInterface */
    private $middlewares;

    /**
     * Delegate constructor.
     *
     * @param PoolInterface $middlewares
     */
    public function __construct(PoolInterface $middlewares) {
        $this->middlewares = $middlewares;
    }

    /** {@inheritdoc} */
    public function __invoke(CommandInterface $command) {
        if (!$middleware = $this->middlewares->first()) {
            return;
        }

        $middleware($command, new self($this->middlewares->tail()));
    }
}