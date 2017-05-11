<?php

/*
 * This File is part of the vufis2 package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Cmd\Tests;

use Lucid\Cmd\CommandHandlerInterface;
use Lucid\Cmd\CommandHandlerResolver;
use Interop\Container\ContainerInterface;

/**
 * Class CommandHandlerResolverTest
 * @package Lucid
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
class CommandHandlerResolverTest extends \PHPUnit_Framework_TestCase {
    /** @test */
    public function itShouldResolveAHandler() {
        $commandClass = 'Acme\Command';
        $handlerClass = 'Acme\CommandHandler';
        $container = $this->mockContainer();
        $handler   = $this->mockHandler();
        $container->expects($this->once())->method('has')->with($handlerClass)->willReturn(true);
        $container->expects($this->once())->method('get')->with($handlerClass)->willReturn($handler);

        $map = [
            $commandClass => $handlerClass
        ];

        $resolver = new CommandHandlerResolver($container, $map);
        $this->assertSame($handler, $resolver->resolve($commandClass));
    }

    /** @test */
    public function itShouldThrowIfNoHandlerIsRegisteredOnTheContainer() {
        $commandClass = 'Acme\Command';
        $handlerClass = 'Acme\CommandHandler';
        $container = $this->mockContainer();
        $container->expects($this->once())->method('has')->with($handlerClass)->willReturn(false);

        $map = [
            $commandClass => $handlerClass
        ];

        $resolver = new CommandHandlerResolver($container, $map);

        try {
            $resolver->resolve($commandClass);
        } catch (\InvalidArgumentException $e) {
            $this->assertSame('No handler configured for "Acme\Command".', $e->getMessage());
            return;
        }

        $this->fail('failed');
    }

    /** @test */
    public function itShouldThrowIfNoHandlerIsConfigured() {
        $commandClass = 'Acme\Command';
        $container = $this->mockContainer();

        $map = [];

        $resolver = new CommandHandlerResolver($container, $map);
        try {
            $resolver->resolve($commandClass);
        } catch (\InvalidArgumentException $e) {
            $this->assertSame('No handler configured for "Acme\Command".', $e->getMessage());
            return;
        }

        $this->fail('failed');
    }

    /** @test */
    public function itShouldThrowIfNoInvalidHandlerIsConfigured() {
        $commandClass = 'Acme\Command';
        $handlerClass = 'Acme\CommandHandler';
        $container = $this->mockContainer();

        $container->expects($this->once())->method('has')->with($handlerClass)->willReturn(true);
        $container->expects($this->once())->method('get')->with($handlerClass)->willReturn(new \stdClass);

        $map = [
            $commandClass => $handlerClass
        ];

        $resolver = new CommandHandlerResolver($container, $map);
        try {
            $resolver->resolve($commandClass);
        } catch (\InvalidArgumentException $e) {
            $this->assertSame('Invalid handler "stdClass" configured for "Acme\Command".', $e->getMessage());
            return;
        }

        $this->fail('failed');
    }

    private function mockHandler() {
        return $this->getMockBuilder(CommandHandlerInterface::class)->getMock();
    }

    private function mockContainer() {
        return $this->getMockBuilder(ContainerInterface::class)->setMethods(['get', 'has'])->getMock();
    }
}
