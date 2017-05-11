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

/**
 * Class Middlewares
 * @package Lucid
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
class Middlewares implements PoolInterface {
    /** @var MiddlewareInterface[] */
    private $middlewares;

    /**
     * Middlewares constructor.
     *
     * @param MiddlewareInterface[] $middlewares
     */
    public function __construct(array $middlewares) {
        $this->setMiddlewares(...$middlewares);
    }

    /** {@inheritdoc} */
    public function first() {
        return current($this->middlewares);
    }

    /** {@inheritdoc} */
    public function tail() {
        return new self(array_slice($this->middlewares, 1));
    }

    /** @param MiddlewareInterface[] ...$middleware */
    private function setMiddlewares(MiddlewareInterface ...$middleware) {
        $this->middlewares = $middleware;
    }
}