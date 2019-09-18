<?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Traits;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Closure;
use DateTime;

/**
 * Trait Converter.
 *
 * Change date into different string formats and types and
 * handle the string cast.
 *
 * Depends on the following methods:
 *
 * @method Carbon|CarbonImmutable copy()
 */
trait Converter
{
    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string|Closure|null
     */
    protected static $toStringFormat = null;

    /**
     * Reset the format used to the default when type juggling a Carbon instance to a string
     *
     * @return void
     */
    public static function resetToStringFormat()
    {
        static::setToStringFormat(null);
    }

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather le