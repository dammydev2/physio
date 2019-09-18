<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\Tests\Session\Storage\Handler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MongoDbSessionHandler;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 * @group time-sensitive
 * @requires extension mongodb
 */
class MongoDbSessionHandlerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mongo;
    private $storage;
    public $options;

    protected function setUp()
    {
        parent::setUp();

        if (!class_exists(\MongoDB\Client::class)) {
            $this->markTestSkipped('The mongodb/mongodb package is required.');
        }

        $this->mongo = $this->getMockBuilder(\MongoDB\Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->options = [
            'id_field' => '_id',
            'data_field' => 'data',
            'time_field' => 'time',
            'expiry_field' => 'expires_at',
            'database' => 'sf-test',
            'collection' => 'session-test',
        ];

        $this->storage = new MongoDbSessionHandler($this->mongo, $this->options);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test