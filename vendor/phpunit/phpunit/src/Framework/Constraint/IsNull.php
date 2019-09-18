<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\MockObject\Invocation;

use PHPUnit\Framework\MockObject\Generator;
use PHPUnit\Framework\MockObject\Invocation;
use PHPUnit\Framework\SelfDescribing;
use ReflectionObject;
use SebastianBergmann\Exporter\Exporter;

/**
 * Represents a static invocation.
 */
class StaticInvocation implements Invocation, SelfDescribing
{
    /**
     * @var array
     */
    private static $uncloneableExtensions = [
        'mysqli'    => true,
        'SQLite'    => true,
        'sqlite3'   => true,
        'tidy'      => true,
        '