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

use Lucid\CommandHandlerInterface;
use Lucid\CommandHandlers;
use Lucid\CommandInterface;

/**
 * Class CommandHandlersTest
 * @package Lucid
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
class CommandHandlersTest extends \PHPUnit_Framework_TestCase {
    /** @test */
    public function itShouldBePossibleToRegisterHandlers() {
        $commandClass = get_class($command = $this->getMockBuilder(CommandInterface::class)->getMock());
        $handlers = new CommandHandlers();
        $handlers->register($commandClass, $handler = $this->mockHandler());

        $this->assertSame($handler, $handlers->getHandler($commandClass));
    }

    /** @test */
    public function itShouldThrowIfCommandClassIsInvalid() {
        $handlers = new CommandHandlers();
        try {
            $handlers->getHandler(get_class($this));
        } catch (\InvalidArgumentException $e) {
            $this->assertSame(sprintf('"%s" is not a command class.', get_class($this)), $e->getMessage());
            return;
        }

        $this->fail('Should not have passed.');
    }

    private function mockHandler() {
        return $this->getMockBuilder(CommandHandlerInterface::class)->getMock();
    }
}
