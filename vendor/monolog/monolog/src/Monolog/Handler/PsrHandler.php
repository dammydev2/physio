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

use Monolog\Logger;

class ChromePHPFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Monolog\Formatter\ChromePHPFormatter::format
     */
    public function testDefaultFormat()
    {
        $formatter = new ChromePHPFormatter();
        $record = array(
            'level' => Logger::ERROR,
            'level_name' => 'ERROR',
            'channel' => 'meh',
            'context' => array('from' => 'logger'),
            'datetime' => new \DateTime("@0"),
            'extra' => array('ip' => '127.0.0.1'),
            'message' => 'log',
        );

        $message = $formatter->format($record);

        $this->assertEquals(
            array(
                'meh',
                array(
                    'message' => 'log',
                    'context' => array('from' => 'logger'),
                    'extra' => array('ip' => '127.0.0.1'),
                ),
                'unknown',
                'error',
            ),
            $message
        );
    }

    /**
     * @covers Monolog\Formatter\ChromePHPFormatter::format
     */
    public function testFormatWithFileAndLine()
    {
        $formatter = new Ch