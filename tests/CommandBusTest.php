<?php

/*
 * This File is part of the Lucid package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Tests;

use Lucid\CommandBus;
use Lucid\CommandInterface;
use Lucid\Middleware\DelegateInterface;
use Lucid\Middleware\MiddlewareInterface;
use Lucid\Middleware\Middlewares;

/**
 * Class CommandBusTest
 * @package Lucid
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
class CommandBusTest extends \PHPUnit_Framework_TestCase {
    use MockMiddlewareTrait;

    /** @test */
    public function itShouldHandleMiddlewares() {
        $res = [];
        $middlewares = [
            $this->mockMiddleware(function (CommandInterface $command, DelegateInterface $next) use (&$res) {
                $res[] = 'A';
                $next($command);
            }),
            $this->mockMiddleware(function (CommandInterface $command, DelegateInterface $next) use (&$res) {
                $res[] = 'B';
                $next($command);
            }),
            $this->mockMiddleware(function (CommandInterface $command, DelegateInterface $next) use (&$res) {
                $res[] = 'C';
                $next($command);
            }),
            $this->mockMiddleware(function (CommandInterface $command, DelegateInterface $next) use (&$res) {
                $res[] = 'D';
                $next($command);
            })
        ];

        $bus = new CommandBus(new Middlewares($middlewares));

        $this->assertSame([], $res);

        $bus->handle($command = $this->mockCommand());

        $this->assertSame(['A', 'B', 'C', 'D'], $res);
    }

    /** @test */
    public function middlewaresShouldBreakExecution() {
        $res = [];
        $middlewares = [
            $this->mockMiddleware(function (CommandInterface $command, DelegateInterface $next) use (&$res) {
                $res[] = 'A';
                $next($command);
            }),
            $this->mockMiddleware(function (CommandInterface $command, DelegateInterface $next) use (&$res) {
                $res[] = 'B';
                $next($command);
            }),
            $this->mockMiddleware(function (CommandInterface $command, DelegateInterface $next) use (&$res) {
                $res[] = 'C';
            }),
            $this->mockMiddleware(function (CommandInterface $command, DelegateInterface $next) use (&$res) {
                $res[] = 'D';
                $next($command);
            })
        ];

        $bus = new CommandBus(new Middlewares($middlewares));

        $this->assertSame([], $res);

        $bus->handle($command = $this->mockCommand());

        $this->assertSame(['A', 'B', 'C'], $res);
    }

    private function mockCommand() {
        return $this->getMockBuilder(CommandInterface::class)->getMock();
    }
}
