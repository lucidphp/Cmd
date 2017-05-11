<?php declare(strict_types=1);

/*
 * This File is part of the vufis2 package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Cmd;

use Interop\Container\ContainerInterface;

/**
 * Class HandlerResolver
 * @package Lucid\Container
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
final class CommandHandlerResolver implements CommandHandlerResolverInterface
{
    /** @var ContainerInterface */
    private $container;
    /** @var array */
    private $handlers;

    /**
     * HandlerResolver constructor.
     *
     * @param ContainerInterface $container
     * @param array              $handlers
     */
    public function __construct(ContainerInterface $container, array $handlers)
    {
        $this->container = $container;
        $this->handlers = $handlers;
    }

    /** @inheritdoc */
    public function resolve(string $commandClass) : ?CommandHandlerInterface
    {
        if (!isset($this->handlers[$commandClass]) || !$this->container->has($this->handlers[$commandClass])) {
            throw new \InvalidArgumentException(sprintf('No handler configured for "%s".', $commandClass));
        }

        if (!($handler = $this->container->get($this->handlers[$commandClass])) instanceof CommandHandlerInterface) {
            throw new \InvalidArgumentException(
                sprintf('Invalid handler "%s" configured for "%s".', get_class($handler), $commandClass)
            );
        }

        return $handler;
    }
}