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

use Lucid\CommandHandlerInterface;
use Lucid\LazyClosureCommandHandlerResolver as Resolver;

class LazyClosureCommandHandlerResolverTest extends \PHPUnit_Framework_TestCase {
    /** @test */
    public function itShouldResolveUsingAClosure() {
        $resolver = new Resolver(function () {
            return $this->mockHandler();
        });

        $this->assertInstanceOf(CommandHandlerInterface::class, $resolver->resolve('command'));
    }

    /** @test */
    public function itShouldThrowIfClosureDoesntReturnAHandler() {
        $resolver = new Resolver(function () {
            return null;
        });

        try {
            $resolver->resolve('command');
        } catch (\InvalidArgumentException $e) {
            $this->assertSame('Invalid handler or handler not found for command "command".', $e->getMessage());
        }

        $resolver = new Resolver(function () {
            return new \stdClass;
        });

        try {
            $resolver->resolve('command');
        } catch (\InvalidArgumentException $e) {
            $this->assertSame('Invalid handler or handler not found for command "command".', $e->getMessage());
            return;
        }

        $this->fail();
    }

    private function mockHandler() {
        return $this->getMockBuilder(CommandHandlerInterface::class)->getMock();
    }
}
