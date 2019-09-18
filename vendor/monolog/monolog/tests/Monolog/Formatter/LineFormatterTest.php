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

use Exception;
use Monolog\ErrorHandler;
use Monolog\Logger;
use Monolog\TestCase;
use PhpConsole\Connector;
use PhpConsole\Dispatcher\Debug as DebugDispatcher;
use PhpConsole\Dispatcher\Errors as ErrorDispatcher;
use PhpConsole\Handler;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @covers Monolog\Handler\PHPConsoleHandler
 * @author Sergey Barbushin https://www.linkedin.com/in/barbushin
 */
class PHPConsoleHandlerTest extends TestCase
{
    /** @var  Connector|PHPUnit_Framework_MockObject_MockObject */
    protected $connector;
    /** @var  DebugDispatcher|PHPUnit_Framework_MockObject_MockObject */
    protected $debugDispatcher;
    /** @var  ErrorDispatcher|PHPUnit_Framework_MockObject_MockObject */
    protected $errorDispatcher;

    protected function setUp()
    {
        if (!class_exists('PhpConsole\Connector')) {
            $this->markTestSkipped('PHP Console library not found. See https://github.com/barbushin/php-console#installation');
        }
        $this->connector = $this->initConnectorMock();

        $this->debugDispatcher = $this->initDebugDispatcherMock($this->connector);
        $this->connector->setDebugDispatcher($this->debugDispatcher);

        $this->errorDispatcher = $this->initErrorDispatcherMock($this->connector);
        $this->connector->setErrorsDispatcher($this->errorDispatcher);
    }

    protected function initDebugDispatcherMock(Connector $connector)
    {
        return $this->getMockBuilder('PhpConsole\Dispatcher\Debug')
            ->disableOriginalConstructor()
            ->setMethods(array('dispatchDebug'))
            ->setConstructorArgs(array($connector, $connector->getDumper()))
            ->getMock();
    }

    protected function initErrorDispatcherMock(Connector $connector)
    {
        return $this->getMockBuilder('PhpConsole\Dispatcher\Errors')
            ->disableOriginalConstructor()
            ->setMethods(array('dispatchError', 'dispatchException'))
            ->setConstructorArgs(array($connector, $connector->getDumper()))
            ->getMock();
    }

    protected function initConnectorMock()
    {
        $connector = $this->getMockBuilder('PhpConsole\Connector')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'sendMessage',
                'onShutDown',
                'isActiveClient',
                'setSourcesBasePath',
                'setServerEncoding',
                'setPassword',
                'enableSslOnlyMode',
                'setAllowedIpMasks',
                'setHeadersLimit',
                'startEvalRequestsListener',
            ))
            ->getMock();

        $connector->expects($this->any())
            ->method('isActiveClient')
            ->will($this->returnValue(true));

        return $connector;
    }

    protected function getHandlerDefaultOption($name)
    {
        $handler = new PHPConsoleHandler(array(), $this->connector);
        $options = $handler->getOptions();

        return $options[$name];
    }

    protected function initLogger($handlerOptions = array(), $level = Logger::DEBUG)
    {
        return new Logger('test', array(
            new PHPConsoleHandler($handlerOptions, $this->connector, $level),
        ));
    }

    public function testInitWithDefaultConnector()
    {
        $handler = new PHPConsoleHandler();
        $this->assertEquals(spl_object_hash(Connector::getInstance()), spl_object_hash($handler->getConnector()));
    }

    public function testInitWithCustomConnector()
    {
        $handler = new PHPConsoleHandler(array(), $this->connector);
        $this->assertEquals(spl_object_hash($this->connector), spl_object_hash($handler->getConnector()));
    }

    public function testDebug()
    {
        $this->debugDispatcher->expects($this->once())->method('dispatchDebug')->with($this->equalTo('test'));
        $this->initLogger()->addDebug('test');
    }

    public function testDebugContextInMessage()
    {
        $message = 'test';
        $tag = 'tag';
        $context = array($tag, 'custom' => mt_rand());
        $expectedMessage = $message . ' ' . json_encode(array_slice($context, 1));
        $this->debugDispatcher->expects($this->once())->method('dispatchDebug')->with(
            $this->equalTo($expectedMessage),
            $this->equalTo($tag)
        );
        $this->initLogger()->addDebug($message, $context);
    }

    public function testDebugTags($tagsContextKeys = null)
    {
        $expectedTags = mt_rand();
        $logger = $this->initLogger($tagsContextKeys ? array('debugTagsKeysInContext' => $tagsContextKeys) : array());
        if (!$tagsContextKeys) {
            $tagsContextKeys = $this->getHandlerDefaultOption('debugTagsKeysInContext');
        }
        foreach ($tagsContextKeys as $key) {
            $debugDispatcher = $this->initDebugDispatcherMock($this->connector);
            $debugDispatcher->expects($this->once())->method('dispatchDebug')->with(
                $this->anything(),
                $this->equalTo($expectedTags)
            );
            $this->connector->setDebugDispatcher($debugDispatcher);
            $logger->addDebug('test', array($key => $expectedTags));
        }
    }

    public function testError($classesPartialsTraceIgnore = null)
    {
        $code = E_USER_NOTICE;
        $message = 'message';
        $file = __FILE__;
        $line = __LINE__;
        $this->errorDispatcher->expects($this->once())->method('dispatchError')->with(
            $this->equalTo($code),
            $this->equalTo($message),
            $this->equalTo($file),
            $this->equalTo($line),
            $classesPartialsTraceIgnore ?: $this->equalTo($this->getHandlerDefaultOption('classesPartialsTraceIgnore'))
        );
        $errorHandler = ErrorHandler::register($this->initLogger($classesPartialsTraceIgnore ? array('classesPartialsTraceIgnore' => $classesPartialsTraceIgnore) : array()), false);
        $errorHandler->registerErrorHandler(array(), false, E_USER_WARNING);
        $errorHandler->handleError($code, $message, $file, $line);
    }

    public function testException()
    {
        $e = new Exception();
        $this->errorDispatcher->expects($this->once())->method('dispatchException')->with(
            $this->equalTo($e)
        );
        $handler = $this->initLogger();
        $handler->log(
            \Psr\Log\LogLevel::ERROR,
            sprintf('Uncaught Exception %s: "%s" at %s line %s', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()),
            array('exception' => $e)
        );
    }

    /**
     * @expectedException Exception
     */
    public function testWrongOptionsThrowsException()
    {
        new PHPConsoleHandler(array('xxx' => 1));
    }

    public function testOptionEnabled()
    {
        $this->debugDispatcher->expects($this->never())->method('dispatchDebug');
        $this->initLogger(array('enabled' => false))->addDebug('test');
    }

    public function testOptionClassesPartialsTraceIgnore()
    {
        $this->testError(array('Class', 'Namespace\\'));
    }

    public function testOptionDebugTagsKeysInContext()
    {
        $this->testDebugTags(array('key1', 'key2'));
    }

    public function testOptionUseOwnErrorsAndExceptionsHandler()
    {
        $this->initLogger(array('useOwnErrorsHandler' => true, 'useOwnExceptionsHan