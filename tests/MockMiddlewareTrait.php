<?php

/*
 * This File is part of the Lucid package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Cmd\Tests;

use Lucid\Cmd\Middleware\MiddlewareInterface;

trait MockMiddlewareTrait {
    private function mockMiddleware(\Closure $handle = null) {
        $handle = $handle ?: function () {};
        $middleware = $this->getMockBuilder(MiddlewareInterface::class)
                           ->setMethods(['__invoke'])
                           ->disableOriginalConstructor()
                           ->getMock();

        $middleware->method('__invoke')->willReturnCallback($handle);
        return $middleware;
    }

    abstract protected function getMockBuilder($class);
}