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

use Monolog\Formatter\ElasticaFormatter;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\TestCase;
use Monolog\Logger;
use Elastica\Client;
use Elastica\Request;
use Elastica\Response;

class ElasticSearchHandlerTest extends TestCase
{
    /**
     * @var Client mock
     */
    protected $client;

    /**
     * @var array Default handler options
     */
    protected $options = array(
        'index' => 'my_index',
        'type'  => 'doc_type',
    );

    public function setUp()
    {
        // Elastica lib required
        if (!class_exists("Elastica\Client")) {
            $this->markTestSkipped("ruflin/elastica not installed");
        }

        // base mock Elastica Client object
        $this->client = $this->getMockBuilder('Elastica\Client')
            ->setMethods(array('addDocuments'))
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @covers Monolog\Handler\ElasticSearchHandler::write
     *