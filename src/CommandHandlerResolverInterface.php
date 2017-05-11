<?php

/*
 * This File is part of the vufis2 package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Cmd;

/**
 * Interface CommandHandlerResolverInterface
 * @package Lucid
 */
interface CommandHandlerResolverInterface {
    /**
     * @param string $commandClass
     *
     * @throws \InvalidArgumentException
     * @return CommandHandlerInterface
     */
    public function resolve($commandClass) : ?CommandHandlerInterface;
}