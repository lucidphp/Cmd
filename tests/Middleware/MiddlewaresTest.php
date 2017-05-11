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

use Lucid\Cmd\Middleware\Middlewares;
use Lucid\Cmd\Tests\MockMiddlewareTrait;

/**
 * Class MiddlewaresTest
 * @package Lucid
 * @author  Thomas Appel <mail@thomas-appel.com>
 */
class MiddlewaresTest extends \PHPUnit_Framework_TestCase {
    use MockMiddlewareTrait;
    /** @test */
    public function itShouldAlwaysReturnTheFirstElementInTheList() {
        $middlewares = new Middlewares([
            $a = $this->mockMiddleware(),
            $b = $this->mockMiddleware(),
            $c = $this->mockMiddleware(),
            $d = $this->mockMiddleware()
        ]);

        $this->assertSame($a, $middlewares->first());
        $this->assertSame($a, $middlewares->first());
    }

    /** @test */
    public function itShouldAlwaysReturnTheTailOfTheList() {
        $middlewares = new Middlewares([
            $a = $this->mockMiddleware(),
            $b = $this->mockMiddleware(),
            $c = $this->mockMiddleware(),
            $d = $this->mockMiddleware()
        ]);

        $this->assertSame($a, $middlewares->first());
        $middlewares = $middlewares->tail();

        $this->assertSame($b, $middlewares->first());
        $middlewares = $middlewares->tail();

        $this->assertSame($c, $middlewares->first());
        $middlewares = $middlewares->tail();

        $this->assertSame($d, $middlewares->first());
    }
}
