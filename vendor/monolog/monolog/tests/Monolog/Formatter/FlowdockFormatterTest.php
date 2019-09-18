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
use Monolog\Logger;

/**
 * @author Robert Kaufmann III <rok3@rok3.me>
 */
class LogEntriesHandlerTest extends TestCase
{
    /**
     * @var resource
     */
    private $res;

    /**
     * @var LogEntriesHandler
     */
    private $handler;

    public function testWriteContent()
    {
        $this->createHandler();
        $this->handler->handle($this->getRecord(Logger::CRITICAL, 'Critical write test'));

        fseek($this->res, 0);
        $content = fread($this->res, 1024);

        $this->assertRegexp('/testToken \[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] test.CRITICAL: Critical write test/', $content);
    }

    public function testWriteBatchContent()
    {
        $records = array(
            $this->getRecord(),
            $this->getRecord(),
            $this->getRecord(),
        );
        $this->createHandler();
        $this->handler->handleBatch($records);

        fseek($this->res, 0);
        $content = fread($this->res, 1024);

        $this->assertRegexp('/(testToken \[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] .* \[\] \[\]\n){3}/', $content);
    }

    private function createHandler()
    {
        $useSSL = extension_loaded('openssl');
        $args = array('testToken', $useSSL, Logger::DEBUG, true);
        $this->re