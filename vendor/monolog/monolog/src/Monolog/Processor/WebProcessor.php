y('host' => '127.0.0.1', 'port' => 1);
        $client = new Client($clientOpts);
        $handlerOpts = array('ignore_error' => $ignore);
        $handler = new ElasticSearchHandler($client, $handlerOpts);

        if ($expectedError) {
            $this->setExpectedException($expectedError[0], $expectedError[1]);
            $handler->handle($this->getRecord());
        } else {
            $this->assertFalse($handler->handle($this->getRecord()));
        }
    }

    /**
     * @return array
     */
    public function providerTestConnectionErrors()
    {
        return array(
            array(false, array('RuntimeException', 'Error sending messages to Elasticsearch')),
            array(true, false),
        );
    }

    /**
     * Integration test using localhost Elastic Search server
     *
     * @covers Monolog\Handler\ElasticSearchHandler::__construct
     * @covers Monolog\Handler\ElasticSearchHandler::handleBatch
     * @covers Monolog\Handler\ElasticSearchHandler::bulkSend
     * @covers Monolog\Handler\ElasticSearchHandler::getDefaultFormatter
     */
    public function testHandleIntegration()
    {
        $msg = array(
            'level' => Logger::ERROR,
            'level_name' => 'ERROR',
            'channel' => 'meh',
            'context' => array('foo' => 7, 'bar', 'class' => new \stdClass),
            'datetime' => new \DateTime("@0"),
            'extra' => array(),
            'message' => 'log',
        );

        $expected = $msg;
        $expected['datetime'] = $msg['datetime']->format(\DateTime::ISO8601);
        $expected['context'] = array(
            'class' => '[object] (stdClass: {})',
            'foo' => 7,
            0 => 'bar',
        );

        $client = new Client();
        $handler = new ElasticSearchHandler($client, $this->options);
        try {
            $handler->handleBatch(array($msg));
        } catch (\RuntimeException $e) {
            $this->markTestSkipped("Cannot connect to Elastic Search server on localhost");
        }

        // check document id from ES server response
        $documentId = $this->getCreatedDocId($client->getLastResponse());
        $this->assertNotEmpty($documentId, 'No elastic document id received');

        // retrieve document source from ES and validate
        $document = $this->getDocSourceFromElastic(
            $client,
            $this->options['index'],
            $this->options['type'],
            $documentId
        );
        $this->assertEquals($expected, $document);

        // remove test index from ES
        $client->request("/{$this->options['index']}", Request::DELETE);
    }

    /**
     * Return last created document id from ES response
     * @param  Response    $response Elastica Response object
     * @return string|null
     */
    protected function getCreatedDocId(Response $response)
    {
        $data = $response->getData();
        if (!empty($data['items'][0]['create']['_id'])) {
            return $data['items'][0]['create']['_id'];
        }
    }

    /**
     * Retrieve document by id from Elasticsearch
     * @param  Client $client     Elastica client
     * @param  string $index
     * @param  string $type
     * @param  string $documentId
     * @retur