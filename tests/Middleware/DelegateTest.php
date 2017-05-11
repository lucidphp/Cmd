<?php

/*
 * This File is part of the Lucid package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Cmd\Tests\Middleware;

use Lucid\Cmd\CommandInterface;
use Lucid\Cmd\Middleware\Delegate;
use Lucid\Cmd\Middleware\PoolInterface;
use Lucid\Cmd\Tests\MockMiddlewareTrait;

/**
 * Class DelegateTest
 * @package Lucid
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
class DelegateTest extends \PHPUnit_Framework_TestCase {
    use MockMiddlewareTrait;
    /** @test */
    public function itShouldDelegateNewDelegate() {

        $delegate = null;
        $command = $this->mockCommand();

        $mw = $this->mockMiddlewares();
        $first = $this->mockMiddleware(function (CommandInterface $cmd, $next) use (&$delegate, $command) {
            $this->assertSame($cmd, $command);
            $this->assertNotSame($delegate, $next);
        });

        $mw->expects($this->once())->method('first')->willReturn($first);
        $mw->expects($this->once())->method('tail')->willReturnCallback(function () {
            return $this->mockMiddlewares();
        });

        $delegate = new Delegate($mw);
        $delegate($command);
    }

    private function mockCommand() {
        return $this
            ->getMockBuilder(CommandInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function mockMiddlewares() {
        $mw = $this->getMockBuilder(PoolInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['first', 'tail'])
            ->getMock();

        return $mw;
    }
}
