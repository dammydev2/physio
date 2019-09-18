<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test\Exception;

use Psy\Exception\ErrorException;

class ErrorExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testInstance()
    {
        $e = new ErrorException();

        $this->assertInstanceOf('Psy\Exception\Exception', $e);
        $this->assertInstanceOf('ErrorException', $e);
        $this->assertInstanceOf('Psy\Exception\ErrorException', $e);
    }

    public function testMessage()
    {
        $e = new ErrorException('foo');

        $this->assertContains('foo', $e->getMessage());
        $this->assertSame('foo', $e->getRawMessage());
    }

    /**
     * @dataProvider getLevels
     */
    public function testErrorLevels($level, $type)
    {
        $e = new ErrorException('foo', 0, $level);
        $this->assertContains('PHP ' . $type, $e->getMessage());
    }

    /**
     * @dataProvider getLevels
     */
    public function testThrowException($level, $type)
    {
        try {
            ErrorException::throwException($level, '{whot}', '{file}', '13');
       