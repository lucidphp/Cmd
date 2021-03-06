<?php

/*
 * This File is part of the Lucid package
 *
 * (c) iwyg <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Lucid\Cmd\Middleware;

interface PoolInterface {
    /**
     * @return MiddlewareInterface|null
     */
    public function first();

    /** @return self */
    public function tail();
}