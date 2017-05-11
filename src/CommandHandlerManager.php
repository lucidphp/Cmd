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

/**
 * Interface CommandHandlerManager
 * @package Lucid
 */
interface CommandHandlerManager {
    /**
     * @param string $commandClass FQN of a command.
     * @param CommandHandlerInterface $handler
     * @throws \InvalidArgumentException if $commandClass is an invalid command class.
     */
    public function register($commandClass, CommandHandlerInterface $handler);

    /**
     * @param $commandClass
     *
     * @throws \InvalidArgumentException if $commandClass is an invalid command class.
     * @return CommandHandlerInterface|null
     */
    public function getHandler($commandClass);
}