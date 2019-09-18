<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Runner;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestCaseTest;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;

/**
 * @group test-reorder
 */
class ResultCacheExtensionTest extends TestCase
{
    /**
     * @var TestResultCache
     */
    protected $cache;

    /**
     * @var ResultCacheExtension
     */
    protected $extension;

    /**
     * @var TestResult
     */
    protected $result;

    protected function setUp(): void
    {
        $this->cache     = new TestResultCache;
        $this->extension = new ResultCacheExtension($this->cache);

        $listener = new TestListenerAdapter;
        $listener->add($this->extension);

        $this->result   = new TestResult;
        $this->result->addListener($listener);
    }

    /**
     * @dataProvider longTestNamesDataprovider
     */
    public function testStripsDataproviderParametersFromTestName(string $testName, string $expectedTestName): void
    {
        $test = new TestCaseTest($testName);
        $test->run($this->result);

        $this->assertSame(BaseTestRunner::STATUS_ERROR, $this->cache->getState($expectedTestName));
    }

    public function longTestNamesDataprovider(): array
    {
        return [
            'ClassName::testMethod' => [
                'testSomething',
                TestCaseTest::class . '::testSomething', ],
            'ClassName::testMethod and data set number and vardump' => [
                '