<?php declare(strict_types=1);

/*
 * This File is part of the Lucid package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Cmd;

/**
 * Class CommandHandlers
 * @package Lucid
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
class CommandHandlers implements CommandHandlerManager
{
    /** @var CommandHandlerInterface[] */
    private $handlers = [];

    /** {@inheritdoc} */
    public function register(string $commandClass, CommandHandlerInterface $handler) : void
    {
        $this->assertCommandClass($commandClass);
        $this->handlers[$commandClass] = $handler;
    }

    /** {@inheritdoc} */
    public function getHandler(string $commandClass) : ?CommandHandlerInterface
    {
        $this->assertCommandClass($commandClass);

        return isset($this->handlers[$commandClass]) ? $this->handlers[$commandClass] : null;
    }

    /**
     * @param $commandClass
     * @throws \InvalidArgumentException if $commandClass is an invalid command class.
     */
    private function assertCommandClass(string $commandClass) : void
    {
        if (is_subclass_of($commandClass, CommandInterface::class)) {
            return;
        }

        throw new \InvalidArgumentException(sprintf('"%s" is not a command class.', $commandClass));
    }
}