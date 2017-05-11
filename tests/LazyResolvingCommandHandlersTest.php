<?php

/*
 * This File is part of the vufis2 package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Tests;

use Interop\Container\ContainerInterface;
use Lucid\CommandHandlerInterface;
use Lucid\CommandHandlers;
use Lucid\LazyClosureCommandHandlerResolver;
use Lucid\LazyResolvingCommandHandlers;

class LazyResolvingCommandHandlersTest extends \PHPUnit_Framework_TestCase {
    /** @test */
    public function itShouldGetHandlersFromResolver() {
        $commandClass = 'Acme\Command';
        $handlerClass = 'Acme\CommandHandler';
        $container = $this->mockContainer();
        $handler   = $this->mockHandler();
        $container->expects($this->once())->method('has')->with($handlerClass)->willReturn(true);
        $container->expects($this->once())->method('get')->with($handlerClass)->willReturn($handler);

        $map = [
            $commandClass => $handlerClass
        ];

        $handlers = new LazyResolvingCommandHandlers(
            new CommandHandlers(),
            new LazyClosureCommandHandlerResolver(function ($commandClass) use ($container, $map) {
                return $container->has($map[$commandClass]) ? $container->get($map[$commandClass]) : null;
            })
        );

        $this->assertSame($handler, $handlers->getHandler($commandClass));
    }

    /** @test */
    public function itShouldTryInjectedManagerAfterResolverFailed() {
        $handler = $this->mockHandler();
        $manager = $this->getMockBuilder(CommandHandlers::class)->getMock();
        $manager->expects($this->once())->method('getHandler')->with('Acme\Command')->willReturn($handler);

        $handlers = new LazyResolvingCommandHandlers(
            $manager,
            new LazyClosureCommandHandlerResolver(function ()  {})
        );

        $handlers->getHandler('Acme\Command');
    }

    /** @test */
    public function registerShouldCallInjectedManager() {
        $handler = $this->mockHandler();
        $manager = $this->getMockBuilder(CommandHandlers::class)->getMock();
        $manager->expects($this->once())->method('register')->with('Acme\Command', $handler);
        $handlers = new LazyResolvingCommandHandlers(
            $manager,
            new LazyClosureCommandHandlerResolver(function ()  {})
        );

        $handlers->register('Acme\Command', $handler);
    }

    private function mockHandler() {
        return $this->getMockBuilder(CommandHandlerInterface::class)->getMock();
    }

    private function mockContainer() {
        return $this->getMockBuilder(ContainerInterface::class)->setMethods(['get', 'has'])->getMock();
    }
}
