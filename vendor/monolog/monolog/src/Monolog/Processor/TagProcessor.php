<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Monolog\Handler;

use Monolog\TestCase;

class DynamoDbHandlerTest extends TestCase
{
    private $client;

    public function setUp()
    {
        if (!class_exists('Aws\DynamoDb\DynamoDbClient')) {
            $this->markTestSkipped('aws/aws-sdk-php not installed');
        }

        $this->client = $this->getMockBuilder('Aws\DynamoDb\DynamoDbClient')
            ->setMethods(array('formatAttributes', '__call'))
            ->disableOriginalConstructor()->getMock();
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('Monolog\Handler\DynamoDbHandler', new DynamoDb