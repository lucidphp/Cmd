<?php

/*
 * This File is part of the Lucid package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Cmd;

use Lucid\Cmd\Middleware\Delegate;
use Lucid\Cmd\Middleware\PoolInterface;

/**
 * Class CommandBus
 * @package Lucid
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
class CommandBus implements CommandBusInterface {
    /** @var PoolInterface */
    private $middleware;

    /**
     * CommandBus constructor.
     *
     * @param PoolInterface $middlewares
     */
    public function __construct(PoolInterface $middlewares) {
        $this->middleware = $middlewares;
    }

    /** {@inheritdoc} */
    public function handle(CommandInterface $command) {
        $delegate = new Delegate($this->middleware);
        $delegate($command);
    }
}