<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Contracts\Cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * Covers most simple to advanced caching needs.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
interface CacheInterface
{
    /**
     * Fetches a value from the pool or computes it if not found.
     *
     * On cache misses, a callback is called that should return the missing value.
     * This callback is given a PSR-6 CacheItemInterface instance corresponding to the
     * req