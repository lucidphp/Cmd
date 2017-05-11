<?php

/*
 * This File is part of the Lucid package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Tests\Middleware;

use Lucid\CommandHandlerInterface;
use Lucid\CommandHandlerManager;
use Lucid\CommandInterface;
use Lucid\Middleware\Delegate;
use Lucid\Middleware\HandleCommandMiddleware;
use Lucid\Middleware\Middlewares;

class HandleCommandMiddlewareTest extends \PHPUnit_Framework_TestCase {

    /** @test */
    public function itShouldThrowIfNoCommandHandlerIsFound() {
        $handlers = new HandleCommandMiddleware($this->mockHandlers());
        $command = $this->mockCommand();
        try {
            $handlers($command, new Delegate(new Middlewares([])));
        } catch (\RuntimeException $e) {
            $this->assertSame(
                sprintf('No handler for command "%s".', get_class($command)),
                $e->getMessage()
            );
        }
    }

    /** @test */
    public function itShouldThrowIfHandlerDoesntImplementHandleMethod() {
        $handler = $this->mockHandler();
        $handlers = new HandleCommandMiddleware($h = $this->mockHandlers());
        $command = $this->mockCommand();
        $h->method('getHandler')->with(get_class($command))->willReturn($handler);

        try {
            $handlers($command, new Delegate(new Middlewares([])));
        } catch (\RuntimeException $e) {
            $this->assertSame(
                sprintf('Expected handler to implement %s().', 'handle'.ucfirst(get_class($command))),
                $e->getMessage()
            );
        }
    }

    /** @test */
    public function itShouldCallHandleMethodOnHandler() {
        $command = $this->mockCommand();
        $method = 'handle'.ucfirst(get_class($command));
        $handler = $this->mockHandler([$method]);
        $handlers = new HandleCommandMiddleware($h = $this->mockHandlers());
        $h->method('getHandler')->with(get_class($command))->willReturn($handler);
        $handler->expects($this->once())->method($method);

        $handlers($command, new Delegate(new Middlewares([])));
    }

    private function mockHandler(array $methods = null) {
        return $this->getMockBuilder(CommandHandlerInterface::class)
                    ->setMethods($methods)
                    ->getMock();
    }

    private function mockHandlers() {
        return $this->getMockBuilder(CommandHandlerManager::class)
            ->setMethods(['getHandler', 'register'])
            ->getMock();
    }

    private function mockCommand() {
        return $this->getMockBuilder(CommandInterface::class)->getMock();
    }
}
