<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Monolog\Formatter;

use Monolog\Utils;

/**
 * Formats a record for use with the MongoDBHandler.
 *
 * @author Florian Plattner <me@florianplattner.de>
 */
class MongoDBFormatter implements FormatterInterface
{
    private $exceptionTraceAsString;
    private $maxNestingLevel;

    /**
     * @param int  $maxNestingLevel        0 means infinite nesting, the $record itself is level 1, $record['context'] is 2
     * @param bool $exceptionTraceAsString set to false to log exception traces as a sub docume